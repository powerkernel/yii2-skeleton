<?php
namespace backend\controllers;

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
        /* check files */
        $files = [
            ['exist' => false, 'file' => Yii::getAlias('@frontend').'/web/images/logo.png'],
            ['exist' => false, 'file' => Yii::getAlias('@frontend').'/web/images/banner.svg'],
            ['exist' => false, 'file' => Yii::getAlias('@frontend').'/web/images/logo-mini.svg'],
            ['exist' => false, 'file' => Yii::getAlias('@frontend').'/web/images/logo-lg.svg'],
            ['exist' => false, 'file' => Yii::getAlias('@frontend').'/web/images/logo-1024.png'],
            ['exist' => false, 'file' => Yii::getAlias('@frontend').'/web/images/logo-120.png'],

            ['exist' => false, 'file' => Yii::getAlias('@frontend').'/web/favicon/android-chrome-192x192.png'],
            ['exist' => false, 'file' => Yii::getAlias('@frontend').'/web/favicon/android-chrome-512x512.png'],
            ['exist' => false, 'file' => Yii::getAlias('@frontend').'/web/favicon/apple-touch-icon.png'],
            ['exist' => false, 'file' => Yii::getAlias('@frontend').'/web/favicon/favicon-16x16.png'],
            ['exist' => false, 'file' => Yii::getAlias('@frontend').'/web/favicon/favicon-32x32.png'],
            ['exist' => false, 'file' => Yii::getAlias('@frontend').'/web/favicon/mstile-150x150.png'],
            ['exist' => false, 'file' => Yii::getAlias('@frontend').'/web/favicon/safari-pinned-tab.svg'],
            ['exist' => false, 'file' => Yii::getAlias('@frontend').'/web/favicon.ico'],
        ];

        foreach($files as $i=>$file){
            if(file_exists($file['file'])){
                $files[$i]['exist']=true;
            }
        }

        /* version */
        $version=file_get_contents(Yii::$app->basePath.'/../version.json');
        $v=json_decode($version, true);

        /* check version */
        $checkVersion = Yii::$app->cache->get('check-version');
        if ($checkVersion === false) {
            $url='https://raw.githubusercontent.com/powerkernel/yii2-skeleton/master/version.json';
            $checkVersion=@file_get_contents($url);
            Yii::$app->cache->set('check-version', $checkVersion, 60);
        }

        $latestVersion=json_decode($checkVersion, true);
        $newVersion=false;
        if($v['version']!=$latestVersion['version']){
            $newVersion=true;
        }

        return $this->render('index', ['files'=>$files, 'v'=>$v, 'newVersion'=>$newVersion]);
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
    public function actionToggleSidebar($classname){
        if(Yii::$app->request->isAjax){
            if(preg_match('/sidebar-collapse/', $classname)){
                Yii::$app->session['sidebar-collapse']=false;
            }
            else {
                Yii::$app->session['sidebar-collapse']=true;
            }
        }
    }
}
