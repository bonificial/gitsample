<?php
spl_autoload_register(function($class_name) {
    $dirs = array(
        'app/controllers/'
    );
    $subDirs = array(
        '/authenticate/',
        '/finances/',
        '/messages/',
    );
    foreach( $dirs as $dir ) {
        foreach ($subDirs as $sub) {
        if (file_exists($dir.$sub.strtolower($class_name).'.php')) {
            require_once($dir.$sub.strtolower($class_name).'.php');
            return;
        }
    }
    }
});