<?php

namespace InYota\Controller\Api;

use Geohash\Geohash;
use InYota\Controller;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use InYota\Model\Feed as FeedModel;
use InYota\Common\Message;

class Feed extends Controller
{
    /**
     * 发布分享
     *
     * @param Request $request
     * @param Response $response
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function send(Request $request, Response $response)
    {
        $latitude = $request->getParsedBodyParam('latitude');
        $longitude = $request->getParsedBodyParam('longitude');

        if (!$latitude || !$longitude) {
            return with(new \InYota\Common\Message($response, false, '请设置当前经纬度'))
                ->withJson();
        }
        $geohash = Geohash::encode($latitude, $longitude);

        $attach = $this->ci->get('attach');
        $user = $this->ci->get('user');
        $content = $request->getParsedBodyParam('content');

        $feed = new FeedModel();
        $feed->content = $content;
        $feed->geohash = $geohash;

        $user->feeds()->save($feed);
        $feed->attachs()->sync([$attach->attach_id], false);

        $response = new Message($response, true, '发布成功', $feed->id);

        return $response->withJson();
    }
}
