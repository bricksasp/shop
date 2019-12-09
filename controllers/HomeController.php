<?php
namespace bricksasp\shop\controllers;

use bricksasp\base\models\Setting;
use bricksasp\cms\models\AdvertPosition;
use bricksasp\base\Config;
use bricksasp\helpers\Tools;

class HomeController extends \bricksasp\base\BaseController
{
    /**
     * 免登录可访问
     * @return array
     */
    public function allowNoLoginAction()
    {
        return [
            'index',
            'detail',
            'setting',
            'banner',
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @OA\Get(path="/shop/home/banner",
     *   summary="横幅列表",
     *   tags={"shop模块"},
     *   @OA\Response(
     *     response=200,
     *     description="响应数据",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(ref="#/components/schemas/bannerlist"),
     *     ),
     *   ),
     * )
     *
     * @OA\Schema(
     *   schema="bannerlist",
     *   description="横幅列表结构",
     *   allOf={
     *     @OA\Schema(
     *       @OA\Property(property="name", type="string", description="广告位名称"),
     *       @OA\Property(property="code", type="string", description="详情调用代码，详情接口在cms模块"),
     *     )
     *   }
     * )
     */
    public function actionBanner()
    {
        $data = AdvertPosition::find($this->dataOwnerUid())->select(['name','code'])->andWhere(['status'=>1])->all();

        return $this->success($data);
    }

    /**
     * @OA\Get(path="/shop/home/setting",
     *   summary="shop设置",
     *   tags={"shop模块"},
     *   @OA\Parameter(
     *     description="开启平台功能后，访问商户对应的数据标识，为空默认为用户1的数据，未开启忽略此参数",
     *     name="access-token",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *       type="string",
     *       default=""
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="响应数据",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(ref="#/components/schemas/setting"),
     *     ),
     *   ),
     * )
     *
     */
    public function actionSetting()
    {
        $data = array_column(Setting::find($this->dataOwnerUid())->andWhere(['type'=>1])->andWhere(['like', 'key', 'shop_'])->asArray()->all(),'val', 'key');
        if ($data) {
            $data['shop_logo'] = $data['shop_logo'] ? Tools::format_array($data['shop_logo'], ['file_url' => ['implode', ['', [Config::instance()->web_url, '###']], 'array']]) : '';
            $data['shop_default_img'] = $data['shop_default_img'] ? Tools::format_array($data['shop_default_img'], ['file_url' => ['implode', ['', [Config::instance()->web_url, '###']], 'array']]) : '';
        }
        return $this->success($data ? $data : (object) []);
    }
}
