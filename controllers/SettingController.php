<?php
namespace bricksasp\shop\controllers;

use bricksasp\base\BaseController;
use bricksasp\base\models\Setting;
use bricksasp\base\models\File;
use bricksasp\base\Config;
use bricksasp\helpers\Tools;
use Yii;

/**
 * SettingController implements the CRUD actions for Setting model.
 */
class SettingController extends BaseController {

	/**
     * shop设置项 
     */
	public function actionIndex() {

        if ($data = Setting::getSetting($this->dataOwnerUid(), 'shop_')) {
        	$data['logoItem'] = isset($data['shop_logo']) ? Tools::format_array(File::find()->select(['id','file_url'])->where(['id' => $data['shop_logo']])->one(), ['file_url' => ['implode', ['', [Config::instance()->web_url, '###']], 'array']]) : (object)[];
        	$data['defaultImgItem'] = isset($data['shop_default_img']) ? Tools::format_array(File::find()->select(['id','file_url'])->where(['id' => $data['shop_default_img']])->one(), ['file_url' => ['implode', ['', [Config::instance()->web_url, '###']], 'array']]) : (object)[];
            $wx = Setting::getSetting($this->dataOwnerUid(), 'wx_');
            $data = array_merge($data, $wx);
        }
		return $this->success($data ? $data : (object) []);
	}

	/**
	 * 更新设置项
	 */
	public function actionUpdate() {
		$data = Yii::$app->request->post();
        if (Setting::saveData($data,$this->dataOwnerUid(), 'shop_')) {
        	Setting::saveData($data,$this->dataOwnerUid(), 'wx_');
            return $this->success();
        }
        return $this->fail();
	}
}
