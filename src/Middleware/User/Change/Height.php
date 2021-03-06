<?php

namespace InYota\Middleware\User\Change;

use InYota\Traits\Container;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class Height
{
    use Container;

    public function __invoke(Request $request, Response $response, callable $next)
    {
        $height = (int) $request->getParsedBodyParam('height');
        $user = $this->ci->get('user');

        if ($height) {
            if ($height < 20 || $height > 320) {
                return with(new \InYota\Common\Message($response, false, '不合法的身高范围'))
                    ->withJson();
            }

            $user->height = $height;
        }

        return $next($request, $response);
    }
}
