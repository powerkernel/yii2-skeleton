<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */

namespace common\actions;


use common\models\Setting;
use Yii;
use yii\base\Action;

/**
 * Class ManifestAction
 * @package common\actions
 */
class ManifestAction extends Action
{

    /**
     * run action
     */
    public function run()
    {
        $iconImageUrl = Yii::$app->params['iconImageUrl'];
        $themeColor=Setting::getValue('androidThemeColor');
        $backgroundColor=Setting::getValue('backgroundColor');
        //$color = Setting::getValue('androidThemeColor');
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $manifest = [
            'name' => Yii::$app->name,
            'icons' => [
                [
                    'src' => $iconImageUrl . '/favicon/android-chrome-192x192.png',
                    'sizes' => '192x192',
                    'type' => 'image/png'
                ],
                [
                    'src' => $iconImageUrl . '/favicon/android-chrome-512x512.png',
                    'sizes' => '512x512',
                    'type' => 'image/png'
                ],
            ],
            'theme_color' => empty($themeColor)?'#ffffff':$themeColor,
            'background_color' => empty($backgroundColor)?'#ffffff':$themeColor,
            'display' => 'standalone'
        ];
        echo json_encode($manifest);
        //echo $manifest;
    }


}