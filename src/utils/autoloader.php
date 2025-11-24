<?php

spl_autoload_register(function ($class) {

    $relativePath = str_replace('\\', '/', $class);

    $file = __DIR__ . '/../classes/' . $relativePath . '.php';

    if (file_exists($file)) {

        require_once $file;
    }
});