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
        $query = $uri->getQuery();
        
        // Block directory traversal attempts - Check both path and query
        $traversalPatterns = ['..', '%2e%2e', '%2E%2E', '%252e%252e', '%252E%252E'];
        foreach ($traversalPatterns as $pattern) {
            if (strpos($path, $pattern) !== false || strpos($query, $pattern) !== false) {
                // Log the attempt
                log_message('warning', 'Directory traversal attempt blocked: ' . $path . ' Query: ' . $query . ' from IP: ' . $request->getIPAddress());
                
                // Redirect to home page
                return redirect()->to('/ITE311-SIGNAR/');
            }
        }
        
        // Block access to sensitive directories
        $sensitivePaths = ['/app/', '/system/', '/vendor/', '/writable/', '/tests/', '/.git/'];
        foreach ($sensitivePaths as $sensitivePath) {
            if (strpos($path, $sensitivePath) !== false) {
                log_message('warning', 'Sensitive directory access attempt blocked: ' . $path . ' from IP: ' . $request->getIPAddress());
                return redirect()->to('/ITE311-SIGNAR/');
            }
        }
        
        // Block access to parent directories
        if (strpos($path, '/../') !== false || strpos($path, '\\..\\') !== false) {
            log_message('warning', 'Parent directory access attempt blocked: ' . $path . ' from IP: ' . $request->getIPAddress());
            return redirect()->to('/ITE311-SIGNAR/');
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
