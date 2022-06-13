<?php

/**
 * This is the front controller, which routes all requests to their controllers.
 */

use Expo\Routes\Router;

// display all errors and warnings on the webpage (should be disabled for release)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// autoloader
spl_autoload_register(function ($class) {
    // project-specific namespace prefix
    $prefix = 'Expo\\';

    // base directory for the namespace prefix
    $base_dir = __DIR__ . '/../';

    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name, which starts after the prefix (on the $len-th symbol)
    $relative_class = substr($class, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

// $routeMain determines which controller we use next, $routeSecondary is given to this controller as an argument
list($pathMain, $pathSecondary) = array_pad(explode("/", $_GET['page']), 2, null);

Router::route('', 'FrontpageController');

Router::execute($pathMain, $pathSecondary);
