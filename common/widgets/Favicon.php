<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */
namespace common\widgets;


use common\models\Setting;
use Yii;
use yii\base\Widget;

/**
 * Class Favicon
 * @package common\widgets
 */
class Favicon extends Widget
{
    /**
     * @inheritdoc
     */
    public function run()
    {

        $baseUrl = Yii::$app->request->baseUrl;
        $themeColor = Setting::getValue('androidThemeColor');
        $data = <<<EOB
<link rel="apple-touch-icon" sizes="180x180" href="{$baseUrl}/favicon/apple-touch-icon.png">
<link rel="icon" type="image/png" href="{$baseUrl}/favicon/favicon-32x32.png" sizes="32x32">
<link rel="icon" type="image/png" href="{$baseUrl}/favicon/favicon-16x16.png" sizes="16x16">
<link rel="manifest" href="{$baseUrl}/favicon/manifest.json">
<link rel="mask-icon" href="{$baseUrl}/favicon/safari-pinned-tab.svg" color="{$themeColor}">
<meta name="theme-color" content="{$themeColor}">
<link rel="shortcut icon" href="{$baseUrl}/favicon.ico" type="image/x-icon" />
EOB;
        return $data;


    }
}