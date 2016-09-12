<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */
namespace common\components;


use common\models\Setting;
use Yii;
use yii\base\Object;

/**
 * Class GAnalytics
 * @package common\widgets
 */
class GAnalytics extends Object
{

    public static function register()
    {

        $js = Setting::getValue('googleAnalytics');
        if (!empty($js)) {
            $view = Yii::$app->getView();
            $view->registerJs($js);
        }
    }
}