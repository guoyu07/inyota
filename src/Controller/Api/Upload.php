<?php

namespace InYota\Controller\Api;

use InYota\Controller;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * 上传控制器.
 *
 * @author Seven Du <lovevipdsw@outlook.com>
 **/
class Upload extends Controller
{
    public function attach(Request $request, Response $response)
    {
        return with(new \InYota\Common\Message($response, true, '上传成功', $this->ci->get('attach')->attach_id))
            ->withJson();
    }

    public function avatar(Request $request, Response $response)
    {
        $attach = $this->ci->get('attach');
        $user = $this->ci->get('user');
        $user->avatar = $attach->attach_id;

        $user->save();

        return with(new \InYota\Common\Message($response, true, '更新成功', $user))
            ->withJson();
    }
}
