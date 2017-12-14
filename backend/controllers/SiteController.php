<?php

namespace backend\controllers;

use Yii;
use yii\httpclient\Client;


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
        /* check favicon/images */
        $baseUrl = Yii::$app->request->baseUrl;
        $gitHubPage = Yii::$app->params['gitHubPage'];
        $url = empty($gitHubPage) ? $baseUrl : $gitHubPage;
        $urls = [
            ['exist' => false, 'url' => $url . '/images/logo.png'],
            ['exist' => false, 'url' => $url . '/images/banner.svg'],
            ['exist' => false, 'url' => $url . '/images/logo-mini.svg'],
            ['exist' => false, 'url' => $url . '/images/logo-lg.svg'],
            ['exist' => false, 'url' => $url . '/images/logo-1024.png'],
            ['exist' => false, 'url' => $url . '/images/logo-120.png'],

            ['exist' => false, 'url' => $url . '/favicon/android-chrome-192x192.png'],
            ['exist' => false, 'url' => $url . '/favicon/android-chrome-512x512.png'],
            ['exist' => false, 'url' => $url . '/favicon/apple-touch-icon.png'],
            ['exist' => false, 'url' => $url . '/favicon/favicon-16x16.png'],
            ['exist' => false, 'url' => $url . '/favicon/favicon-32x32.png'],
            ['exist' => false, 'url' => $url . '/favicon/mstile-150x150.png'],
            ['exist' => false, 'url' => $url . '/favicon/safari-pinned-tab.svg'],
        ];
        foreach ($urls as $i => $url) {
            if ($this->isUrlExist($url['url'])) {
                $urls[$i]['exist'] = true;
            }
        }

        /* check favicon */
        $favicon = [
            ['exist' => false, 'file' => Yii::getAlias('@frontend') . '/web/favicon.ico'],
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
            'urls' => $urls,
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

    /**
     * check url exist
     * @param $url
     * @return bool
     */
    protected function isUrlExist($url)
    {
        $exist = Yii::$app->cache->getOrSet(md5($url), function () use ($url) {
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_NOBODY, true);
            $result = curl_exec($curl);
            if ($result !== false) {
                $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                if ($statusCode == 404) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return false;
            }
        }, 60);
        return $exist;
    }
}
