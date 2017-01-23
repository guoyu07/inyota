<?php

// Application middleware
// e.g: app()->add(new \Slim\Csrf\Guard);
//

// Trailing slash
\InYota\Application::add(InYota\Middleware\TrailingSlash::class);
