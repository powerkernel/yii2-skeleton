<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2016 Power Kernel
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
        $baseUrl=Yii::$app->request->baseUrl;
        $iconImageUrl = Yii::$app->params['iconImageUrl'];
        $url=empty($iconImageUrl)?$baseUrl:$iconImageUrl;
        $themeColor = Setting::getValue('androidThemeColor');
        $safariMaskColor = Setting::getValue('safariMaskColor');
        $data = <<<EOB
<link rel="apple-touch-icon" sizes="180x180" href="{$url}/favicon/apple-touch-icon.png">
<link rel="icon" type="image/png" href="{$url}/favicon/favicon-32x32.png" sizes="32x32">
<link rel="icon" type="image/png" href="{$url}/favicon/favicon-16x16.png" sizes="16x16">
<link rel="manifest" href="{$baseUrl}/manifest.json">
<link rel="mask-icon" href="{$url}/favicon/safari-pinned-tab.svg" color="{$safariMaskColor}">
<meta name="msapplication-config" content="{$baseUrl}/browserconfig.xml">
<meta name="theme-color" content="{$themeColor}">
EOB;
        return $data;
    }
}