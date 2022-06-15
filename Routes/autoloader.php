<?php

spl_autoload_register(function ($class) {
    $prefix = 'Expo\\';
    $baseDir = __DIR__ . '/../';
    $len = strlen($prefix);

    if (0 !== strncmp($prefix, $class, $len)) {
        // the class doesn't have project-specific prefix
        return;
    }

    $relativeClass = substr($class, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});
