<?php
namespace bricksasp\shop;

use Yii;

/**
 * shop module definition class
 */
class Module extends \bricksasp\base\BaseModule
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'bricksasp\shop\controllers';

    public $navbar;
    /**
     * 模块页面布局
     * @var string
     */
    // public $layout = '@bricksasp/shop/views/layouts/main.php';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if ($this->navbar === null && Yii::$app instanceof \yii\web\Application) {
            $this->navbar = [
                ['label' => 'Help', 'url' => ['default/index']],
                ['label' => 'Home', 'url' => Yii::$app->homeUrl],
            ];
        }
        
        // custom initialization code goes here
    }

}
