<?php
use CodeIgniter\Boot;
use Config\Paths;

/*
 *---------------------------------------------------------------
 * SECURITY: BLOCK DIRECTORY TRAVERSAL ATTEMPTS
 *---------------------------------------------------------------
 */

// Check for directory traversal attempts in the request URI
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
$queryString = $_SERVER['QUERY_STRING'] ?? '';

// Patterns to detect directory traversal attempts
$traversalPatterns = [
    '..',
    '%2e%2e',
    '%2E%2E',
    '%252e%252e',
    '%252E%252E',
    '..%2f',
    '..%5c',
    '%2e%2e%2f',
    '%2e%2e%5c'
];

// Check both URI and query string for traversal patterns
foreach ($traversalPatterns as $pattern) {
    if (strpos($requestUri, $pattern) !== false || strpos($queryString, $pattern) !== false) {
        // Log the attempt
        error_log('Directory traversal attempt blocked: ' . $requestUri . ' Query: ' . $queryString . ' from IP: ' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
        
        // Redirect to home page
        header('Location: /ITE311-SIGNAR/', true, 301);
        exit;
    }
}

// Block access to sensitive directories
$sensitivePaths = ['/app/', '/system/', '/vendor/', '/writable/', '/tests/', '/.git/'];
foreach ($sensitivePaths as $sensitivePath) {
    if (strpos($requestUri, $sensitivePath) !== false) {
        error_log('Sensitive directory access attempt blocked: ' . $requestUri . ' from IP: ' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
        header('Location: /ITE311-SIGNAR/', true, 301);
        exit;
    }
}

/*
 *---------------------------------------------------------------
 * CHECK PHP VERSION
 *---------------------------------------------------------------
 */

$minPhpVersion = '8.1'; // If you update this, don't forget to update spark.
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    $message = sprintf(
        'Your PHP version must be %s or higher to run CodeIgniter. Current version: %s',
        $minPhpVersion,
        PHP_VERSION,
    );

    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo $message;

    exit(1);
}

/*
 *---------------------------------------------------------------
 * SET THE CURRENT DIRECTORY
 *---------------------------------------------------------------
 */

// Path to the front controller (this file)
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Ensure the current directory is pointing to the front controller's directory
if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

/*
 *---------------------------------------------------------------
 * BOOTSTRAP THE APPLICATION
 *---------------------------------------------------------------
 * This process sets up the path constants, loads and registers
 * our autoloader, along with Composer's, loads our constants
 * and fires up an environment-specific bootstrapping.
 */

// LOAD OUR PATHS CONFIG FILE
// This is the line that might need to be changed, depending on your folder structure.
require __DIR__ . '/app/Config/Paths.php';
require __DIR__ . '/vendor/autoload.php';
// ^^^ Change this line if you move your application folder

$paths = new Paths();

// LOAD THE FRAMEWORK BOOTSTRAP FILE
require $paths->systemDirectory . '/Boot.php';

exit(Boot::bootWeb($paths));