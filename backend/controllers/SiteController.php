<?php

namespace backend\controllers;

use common\Core;
use Yii;


/**
 * Site controller
 */
class SiteController extends BackendController
{

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'browser-config' => [
                'class' => 'common\actions\BrowserConfigAction',
            ],
            'manifest' => [
                'class' => 'common\actions\ManifestAction',
            ],
            'login' => [
                'class' => 'common\actions\LoginAction',
            ],
            'flickr-upload' => [
                'class' => 'common\actions\FlickrUploadAction',
            ],
            'flickr-photo' => [
                'class' => 'common\actions\FlickrPhotoAction',
            ],
            'flickr-delete' => [
                'class' => 'common\actions\FlickrDeleteAction',
            ],
        ];
    }

    /**
     * Index
     * @return string
     */
    public function actionIndex()
    {
        /* check favicon */
        $favicon = [
            ['exist' => false, 'file' => Yii::getAlias('@frontend') . '/web/android-chrome-192x192.png'],
            ['exist' => false, 'file' => Yii::getAlias('@frontend') . '/web/android-chrome-512x512.png'],
            ['exist' => false, 'file' => Yii::getAlias('@frontend') . '/web/apple-touch-icon.png'],
            ['exist' => false, 'file' => Yii::getAlias('@frontend') . '/web/favicon-16x16.png'],
            ['exist' => false, 'file' => Yii::getAlias('@frontend') . '/web/favicon-32x32.png'],
            ['exist' => false, 'file' => Yii::getAlias('@frontend') . '/web/mstile-150x150.png'],
            ['exist' => false, 'file' => Yii::getAlias('@frontend') . '/web/safari-pinned-tab.svg'],
            ['exist' => false, 'file' => Yii::getAlias('@frontend') . '/web/favicon.ico'],

            ['exist' => false, 'file' => Yii::getAlias('@backend') . '/web/android-chrome-192x192.png'],
            ['exist' => false, 'file' => Yii::getAlias('@backend') . '/web/android-chrome-512x512.png'],
            ['exist' => false, 'file' => Yii::getAlias('@backend') . '/web/apple-touch-icon.png'],
            ['exist' => false, 'file' => Yii::getAlias('@backend') . '/web/favicon-16x16.png'],
            ['exist' => false, 'file' => Yii::getAlias('@backend') . '/web/favicon-32x32.png'],
            ['exist' => false, 'file' => Yii::getAlias('@backend') . '/web/mstile-150x150.png'],
            ['exist' => false, 'file' => Yii::getAlias('@backend') . '/web/safari-pinned-tab.svg'],
            ['exist' => false, 'file' => Yii::getAlias('@backend') . '/web/favicon.ico'],
        ];
        foreach ($favicon as $i => $icon) {
            if (file_exists($icon['file'])) {
                $favicon[$i]['exist'] = true;
            }
        }


        /* version */
        $version = file_get_contents(Yii::$app->basePath . '/../version.json');
        $v = json_decode($version, true);

        /* check version */
        $checkVersion = Yii::$app->cache->get('check-version');
        if ($checkVersion === false) {
            $url = 'https://raw.githubusercontent.com/powerkernel/yii2-skeleton/master/version.json';
            $checkVersion = @file_get_contents($url);
            Yii::$app->cache->set('check-version', $checkVersion, 60);
        }

        $latestVersion = json_decode($checkVersion, true);
        $newVersion = false;
        if ($v['version'] != $latestVersion['version']) {
            $newVersion = true;
        }

        return $this->render('index', [
            //'urls' => $urls,
            'favicon' => $favicon,
            'v' => $v,
            'newVersion' => $newVersion
        ]);
    }


    /**
     * Logout
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * toggle sidebar
     * @param $classname string
     */
    public function actionToggleSidebar($classname)
    {
        if (Yii::$app->request->isAjax) {
            if (preg_match('/sidebar-collapse/', $classname)) {
                Yii::$app->session['sidebar-collapse'] = false;
            } else {
                Yii::$app->session['sidebar-collapse'] = true;
            }
        }
    }


}
