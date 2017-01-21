<?php

// This file allows us to emulate Apache's "mod_rewrite" functionality from the
// built-in PHP web server. This provides a convenient way to test a Zank
// application without having installed a "real" web server software here.
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $uri = urldecode(
        parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
    );

    $filename = __DIR__.$uri;
    if ($uri !== '/' && is_file($filename)) {
        return false;
    }
}

require dirname(__DIR__).'/bootstrap.php';
