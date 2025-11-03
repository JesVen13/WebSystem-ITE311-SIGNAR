<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class SecurityFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing the web master
     * to customize the error page.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $uri = $request->getUri();
        $path = $uri->getPath();
        
        // Get the raw request URI for better detection
        $rawUri = $_SERVER['REQUEST_URI'] ?? $path;
        
        // Block XAMPP Dashboard Access - Check multiple URI formats
        $xamppPaths = ['/xampp', '/webalizer', '/security', '/licenses', '/application', '/dashboard'];
        
        foreach ($xamppPaths as $xamppPath) {
            // Check both normalized path and raw URI
            $pathsToCheck = [$path, $rawUri];
            
            foreach ($pathsToCheck as $checkPath) {
                // Match if path starts with the XAMPP path at root level
                if (stripos($checkPath, $xamppPath) === 0 || preg_match('#^/' . preg_quote($xamppPath, '#') . '(/|$)#i', $checkPath)) {
                    // Allow if it's part of our legitimate app routes
                    if (!preg_match('#/ITE311-SIGNAR/(admin|student|teacher)/dashboard#i', $checkPath) &&
                        !preg_match('#^/ITE311-SIGNAR/(admin|student|teacher)/dashboard#i', $checkPath)) {
                        log_message('warning', 'XAMPP dashboard access attempt blocked: ' . $checkPath . ' from IP: ' . $request->getIPAddress());
                        return redirect()->to('/ITE311-SIGNAR/login');
                    }
                }
            }
        }
        
        return $request;
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Add security headers
        $response->setHeader('X-Content-Type-Options', 'nosniff');
        $response->setHeader('X-Frame-Options', 'DENY');
        $response->setHeader('X-XSS-Protection', '1; mode=block');
        $response->setHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        return $response;
    }
}
