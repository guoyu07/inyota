<?php

namespace InYota\Middleware\User\Change;

use InYota\Traits\Container;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class Kg
{
    use Container;

    public function __invoke(Request $request, Response $response, callable $next)
    {
        $user = $this->ci->get('user');
        $kg = (int) $request->getParsedBodyParam('kg');

        if ($kg > 0) {
            $user->kg = $kg;
        }

        return $next($request, $response);
    }
}
