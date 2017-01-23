<?php

namespace InYota\Middleware\User\Change;

use InYota\Traits\Container;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class Shape
{
    use Container;

    protected $shapes = ['壮熊', '狒狒', '肌肉', '普通', '偏瘦'];

    public function __invoke(Request $request, Response $response, callable $next)
    {
        $shape = $request->getParsedBodyParam('shape');

        if ($shape) {
            if (!in_array($shape, $this->shapes)) {
                return with(new \InYota\Common\Message($response, false, '设置的角色非法'))
                    ->withJson();
            }

            $user = $this->ci->get('user');
            $user->shape = $shape;
        }

        return $next($request, $response);
    }
}
