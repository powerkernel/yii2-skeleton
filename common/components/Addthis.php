<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2016 Power Kernel
 */
namespace common\components;


use common\Core;
use common\models\Setting;
use Yii;
use yii\base\Object;

/**
 * Class Addthis
 * @package common\widgets
 */
class Addthis extends Object
{

    public static function register()
    {
        if(!Core::isInMemberAreaPage() && Yii::$app->id!='app-backend'){
            if ($id = Setting::getValue('addthis')) {
                Yii::$app->view->registerJsFile('//s7.addthis.com/js/300/addthis_widget.js#pubid=' . $id);
            }
        }


    }
}