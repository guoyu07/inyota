<?php

namespace InYota\Controller\Api;

use InYota\Controller;
use InYota\Model\Area as AreaModel;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class Area extends Controller
{
    public function get(Request $request, Response $response)
    {
        $pid = (int) $request->getAttribute('pid') ?: 1;
        $areas = AreaModel::where('pid', $pid)->get();

        if (!$areas) {
            return with(new \InYota\Common\Message($response, false, '获取失败'))
            ->withJson();
        }

        return with(new \InYota\Common\Message($response, true, '获取成功', $areas))
            ->withJson();
    }
}
