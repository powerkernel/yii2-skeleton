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
            'flickr-upload' => [
                'class' => 'common\components\FlickrUploadAction',
            ],
            'flickr-photo' => [
                'class' => 'common\components\FlickrPhotoAction',
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


        return $this->render('index', ['files'=>$files]);
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
