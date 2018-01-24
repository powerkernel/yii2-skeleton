<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2016 Power Kernel
 */
namespace common\components;


use common\models\Setting;
use Yii;
use yii\base\BaseObject;
use yii\web\View;

/**
 * Class HeaderJS
 * @package common\widgets
 */
class HeaderJS extends BaseObject
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