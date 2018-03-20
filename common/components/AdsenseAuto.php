<?php

namespace common\components;

use common\models\Setting;
use yii\base\BaseObject;
use yii\web\View;

/**
 * Adsense auto ads asset.
 */
class AdsenseAuto extends BaseObject
{
    /**
     * register
     */
    public static function register()
    {
        $adClient = Setting::getValue('adsense');
        if (!empty($adClient)) {
            self::registerJsFile();
            $js = <<<EOB
(adsbygoogle = window.adsbygoogle || []).push({
      google_ad_client: "{$adClient}",
      enable_page_level_ads: true
});
EOB;
            $view = \Yii::$app->getView();
            $view->registerJs($js, View::POS_HEAD);
        }
    }

    /**
     * register adsense js file
     */
    public static function registerJsFile()
    {
        \Yii::$app->view->registerJsFile(
            '//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js',
            ['position' => View::POS_HEAD, 'async' => true]
        );
    }
}
