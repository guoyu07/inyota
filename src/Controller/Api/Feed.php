<?php

namespace InYota\Controller\Api;

use Geohash\Geohash;
use InYota\Common\Message;
use InYota\Controller;
use InYota\Model\Feed as FeedModel;
use InYota\Model\FeedDigg;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class Feed extends Controller
{
    /**
     * 发布分享.
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return mixed
     *
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

    public function get(Request $request, Response $response)
    {
        $type = $request->getAttribute('type');

        switch (strtolower($type)) {
            case 'new':
                return $this->getNew($request, $response);
        }

        return $this->getHot($request, $response);
    }

    protected function getNew(Request $request, Response $response)
    {
        $min = $request->getQueryParam('min');
        $take = 20;
        $userNum = 9;

        $feeds = FeedModel::where(function ($query) use ($min) {
                if ($min) {
                    $query->where('id', '<', $min);
                }
            })
            ->orderBy('id', 'desc')
            ->take($take)
            ->withCount('diggUsers')
            ->with([
                'diggUsers' => function ($query) use ($userNum) {
                    $query->take($userNum);
                }
            ])
            ->get();

        if ($feeds->isEmpty()) {
            return with(new Message($response, false, '无数据'))->withJson();
        }

        return with(new Message($response, true, '获取成功', $feeds->toArray()))->withJson();
    }

    protected function getHot(Request $request, Response $response)
    {
        $day = 7;
        $now = $this->ci->carbon->copy();
        $sub = $now->copy()->subDays($day);
        $take = 20;
        $userNum = 9;
        $min = $request->getQueryParam('min');

        $feeds = FeedModel::distinct()
            ->whereHas('diggs', function ($query) use ($sub, $now) {
                $query->whereBetween('created_at', [$sub, $now])
                    ->groupBy('feed_id');
            })
            ->where(function ($query) use ($min) {
                if ($min) {
                    $query->where('id', '<', $min);
                }
            })
            ->orderBy('id', 'desc')
            ->take($take)
            ->with([
                'diggUsers' => function ($query) use ($userNum) {
                    $query->take($userNum);
                }
            ])
            ->withCount('diggUsers')
            ->get();

        if ($feeds->isEmpty()) {
            return with(new Message($response, false, '无数据'))->withJson();
        }

        return with(new Message($response, true, '获取成功', $feeds->toArray()))->withJson();
    }

    /**
     * digg feed.
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function sendDigg(Request $request, Response $response, array $args)
    {
        $id = $args['id'];
        $feed = FeedModel::find($id);

        if (!$feed) {
            return with(new Message($response, false, '分享不存在'))->withJson();
        }

        $user = $this->ci->get('user');
        $demo = $user->diggFeeds()->sync([$feed->id], false);

        return with(new Message($response, true, '操作成功'))->withJson();
    }
}
