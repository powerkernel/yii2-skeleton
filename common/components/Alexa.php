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
 * Class Alexa
 * @package common\widgets
 */
class Alexa extends Object
{

    public static function register()
    {
        $js = Setting::getValue('alexa');
        if (!empty($js)) {
            $view = Yii::$app->getView();
            $view->registerJs($js, View::POS_HEAD);
        }
    }
}