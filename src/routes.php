<?php

use InYota\Application;
use InYota\Controller\Api;
use InYota\Middleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

Application::any('/test', function (Request $request, Response $response) {
    $response = $response->withJson([1, 2, 3]);

    return $response;
})
->add(\InYota\Middleware\InitDb::class);

Application::group('/api', function () {
    // index
    $this->any('', function (Request $request, Response $response): Response {
        $apiList = [
            '/api/sign'    => '用户注册｜登陆',
            '/api/captcha' => '验证码',
            '/api/upload'  => '上传相关',
            '/api/user'    => '用户相关',
        ];

        return $response->withJson($apiList);
    });

    // 用户注册｜登陆
    $this->group('/sign', function () {
        // 索引
        $this->any('', \InYota\Controller\Api\Sign::class);

        // 基本信息注册
        $this
            ->post('/up/base', \InYota\Controller\Api\Sign::class.':stepRegisterBase')
            ->add(\InYota\Middleware\Sign\Up\ValidateUserInviteCode::class)
            ->add(\InYota\Middleware\Captcha\ValidateByPhoneCaptcha::class)
            ->add(\InYota\Middleware\Sign\Up\ValidateUserByPhone::class)
            ->add(\InYota\Middleware\InitDb::class);

        // 登陆
        $this
            ->post('/in', \InYota\Controller\Api\Sign::class.':in')
            ->add(\InYota\Middleware\Sign\In\ValidateUserByPhone::class)
            ->add(\InYota\Middleware\InitDb::class);

        // 刷新token
        $this
            ->post('/refresh-token', \InYota\Controller\Api\Sign::class.':refreshToken')
            ->add(\InYota\Middleware\InitDb::class);
    });

    // 验证码相关
    $this->group('/captcha', function () {
        // 索引
        $this->any('', function (Request $request, Response $response): Response {
            $apiList = [
                '/api/captcha/phone/get/register' => '获取手机号码验证码',
                '/api/captcha/phone/has'          => '验证手机号码验证码',
            ];

            return $response->withJson($apiList);
        });

        // 获取手机号码验证码
        $this
            ->post('/phone/get/register', \InYota\Controller\Api\Captcha\Phone::class.':get')
            ->add(\InYota\Middleware\Sign\Up\ValidateUserByPhone::class)
            ->add(\InYota\Middleware\InitDb::class);

        // 验证手机号码验证码
        $this
            ->post('/phone/has', \InYota\Controller\Api\Captcha\Phone::class.':has')
            ->add(\InYota\Middleware\Captcha\ValidateByPhoneCaptcha::class)
            ->add(\InYota\Middleware\InitDb::class);
    });

    // 上传附件相关
    $this
        ->group('/upload', function () {
            // 索引
            $this->any('', function (Request $request, Response $response) {
                $apiList = [
                    '/api/upload/attach' => '上传附件',
                    '/api/uplaod/avatar' => '上传头像',
                ];

                return $response->withJson($apiList);
            });

            // 上传附件
            $this
                ->post('/attach', \InYota\Controller\Api\Upload::class.':attach')
                ->add(\InYota\Middleware\AttachUpload::class);

            // 上传头像
            $this
                ->post('/avatar', \InYota\Controller\Api\Upload::class.':avatar')
                ->add(\InYota\Middleware\AttachUpload::class);
        })
        ->add(\InYota\Middleware\InitAliyunOss::class)
        ->add(\InYota\Middleware\AuthenticationUserToken::class)
        ->add(\InYota\Middleware\InitDb::class);

    // 用户相关
    $this
        ->group('/user', function () {
            // api 索引
            $this->any('', \InYota\Controller\Api\User::class);

            // change data
            $this
                ->post('/change', \InYota\Controller\Api\User::class.':changeDate')
                ->add(\InYota\Middleware\User\Change\Love::class)
                ->add(\InYota\Middleware\User\Change\Shape::class)
                ->add(\InYota\Middleware\User\Change\Role::class)
                ->add(\InYota\Middleware\User\Change\Kg::class)
                ->add(\InYota\Middleware\User\Change\Height::class)
                ->add(\InYota\Middleware\User\Change\Age::class)
                ->add(\InYota\Middleware\User\Change\Username::class);

            //  搜索用户接口
            $this->post('/search', \InYota\Controller\Api\User::class.':search');
        })
        ->add(\InYota\Middleware\AuthenticationUserToken::class)
        ->add(\InYota\Middleware\InitDb::class);

    // 首页用户
    $this->post('/users', \InYota\Controller\Api\User::class.':gets')
        ->add(\InYota\Middleware\AuthenticationUserToken::class)
        ->add(\InYota\Middleware\InitDb::class);

    // 更新用户经纬度
    $this->patch('/users/{user}/geohash', Api\User::class.':updateGeohash')
        ->add(Middleware\AuthenticationUserToken::class)
        ->add(Middleware\InitDb::class);

    // 地区接口
    $this->any('/areas[/{pid:\d+}]', \InYota\Controller\Api\Area::class.':get')
        ->add(\InYota\Middleware\InitDb::class);

    // 分享接口相关
    $this->group('/feeds', function () {
        // 发布分享
        $this->post('', \InYota\Controller\Api\Feed::class.':send')
             ->add(\InYota\Middleware\AttachUpload::class);

        // 获取分享
        $this->get('[/{type}]', \InYota\Controller\Api\Feed::class.':get');

        // 赞分享
        $this->post('/{id:\d+}/diggs', \InYota\Controller\Api\Feed::class.':sendDigg');
    })
    ->add(\InYota\Middleware\AuthenticationUserToken::class)
    ->add(\InYota\Middleware\InitDb::class);
})
->add(\InYota\Middleware\ExceptionHandle2API::class);

// 附件相关
Application::get('/attach/{id:\d+}[/{type:[0|1]}]', function (Request $request, Response $response, $args) {
    $attach = \InYota\Model\Attach::find($args['id']);

    // 先不用判断是非存在oss中，如果是迁移，可能也有可能回源的附件。
    if (!$attach/* || file_exists(($ossPath = 'oss://'.$attach->path)) === false*/) {
        return $response
            ->withStatus(404)
            ->write('not found.');
    }

    $url = attach_url($attach->path);

    if ((bool) $request->getAttribute('type') === true) {
        return with(new \InYota\Common\Message($response, true, '', $url))
            ->withJson();
    }

    return $response
        ->withStatus(307)
        ->withRedirect($url);
})
->add(\InYota\Middleware\InitAliyunOss::class)
->add(\InYota\Middleware\InitDb::class);
