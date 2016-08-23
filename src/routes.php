<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->group('/api', function () {
    // index
    $this->any('', function(Request $request, Response $response) {
        $apiList = [
            '/api/sign/' => '用户注册｜登陆',
        ];
        $response->withJson($apiList);

        return $response;
    });


    // 用户注册｜登陆
    $this->group('/sign', function () {
        // 索引
        $this->any('', Zank\Controller\Api\Sign::class);

        // 注册
        $this
            ->any('/up', Zank\Controller\Api\Sign::class.':up')
            ->add(Zank\Middleware\InitDb::class)
        ;

        // 登陆
        $this
            ->post('/in', Zank\Controller\Api\Sign::class,':in')
            ->add(Zank\Middleware\InitDb::class)
        ;
    });
});
