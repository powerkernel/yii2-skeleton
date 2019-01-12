<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2016 Power Kernel
 */
namespace common\widgets;


use common\Core;
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
        $themeColor = Setting::getValue('androidThemeColor');
        $safariMaskColor = Setting::getValue('safariMaskColor');
        $msTileColor = Setting::getValue('msTileColor');
        $data = <<<EOB
<link rel="apple-touch-icon" sizes="180x180" href="{$baseUrl}/apple-touch-icon.png">
<link rel="icon" type="image/png" href="{$baseUrl}/favicon-32x32.png" sizes="32x32">
<link rel="icon" type="image/png" href="{$baseUrl}/favicon-16x16.png" sizes="16x16">
<link rel="manifest" href="{$baseUrl}/manifest.json">
<link rel="mask-icon" href="{$baseUrl}/safari-pinned-tab.svg" color="{$safariMaskColor}">
<meta name="msapplication-TileColor" content="{$msTileColor}">
<meta name="theme-color" content="{$themeColor}">
EOB;
        return $data;
    }
}
