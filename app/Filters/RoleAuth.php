<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleAuth implements FilterInterface
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
        $session = session();
        
        // Check if user is logged in
        if (!$session->get('isLoggedIn')) {
            log_message('warning', 'Unauthorized access attempt to protected route: ' . $request->getUri()->getPath() . ' from IP: ' . $request->getIPAddress());
            return redirect()->to('/ITE311-SIGNAR/login');
        }
        
        // If role arguments are provided, check role
        if (!empty($arguments)) {
            $userRole = $session->get('role');
            $allowedRoles = $arguments;
            
            if (!in_array($userRole, $allowedRoles)) {
                log_message('warning', 'Unauthorized role access attempt. User role: ' . $userRole . ' Required roles: ' . implode(', ', $allowedRoles) . ' from IP: ' . $request->getIPAddress());
                return redirect()->to('/ITE311-SIGNAR/login')->with('error', 'You do not have permission to access this page.');
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
        return $response;
    }
}
