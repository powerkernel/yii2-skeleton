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
use yii\web\View;

/**
 * Class HeaderJS
 * @package common\widgets
 */
class HeaderJS extends Object
{

    public static function register()
    {
        $js = Setting::getValue('headJs');
        if (!empty($js)) {
            $view = Yii::$app->getView();
            $view->registerJs($js, View::POS_HEAD);
        }
    }
}