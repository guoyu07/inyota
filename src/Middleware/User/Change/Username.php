<?php

namespace InYota\Middleware\User\Change;

use InYota\Model;
use InYota\Traits\Container;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class Username
{
    use Container;

    public function __invoke(Request $request, Response $response, callable $next)
    {
        $user = $this->ci->get('user');
        $username = $request->getParsedBodyParam('username');

        if ($username) {
            if ($this->chackUsernameForMe($username) === false) {
                return with(new \InYota\Common\Message($response, false, '该用户名不可用！'))
                ->withJson();
            }

            $user->username = $username;
        }

        return $next($request, $response);
    }

    protected function chackUsernameForMe(string $username): bool
    {
        $user_id = $this->ci->get('user')->user_id;
        $old_username = $this->ci->get('user')->username;

        $user = Model\User::byUserName($username)->first();

        if ($old_username != $username && !$user) {
            return true;
        }

        return $user_id === $user->user_id;
    }
}
