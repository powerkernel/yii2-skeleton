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

            ['exist' => false, 'file' => Yii::getAlias('@frontend').'/web/apple-touch-icon-57x57.png'],
            ['exist' => false, 'file' => Yii::getAlias('@frontend').'/web/apple-touch-icon-60x60.png'],
            ['exist' => false, 'file' => Yii::getAlias('@frontend').'/web/apple-touch-icon-72x72.png'],
            ['exist' => false, 'file' => Yii::getAlias('@frontend').'/web/apple-touch-icon-76x76.png'],
            ['exist' => false, 'file' => Yii::getAlias('@frontend').'/web/apple-touch-icon-114x114.png'],
            ['exist' => false, 'file' => Yii::getAlias('@frontend').'/web/apple-touch-icon-120x120.png'],
            ['exist' => false, 'file' => Yii::getAlias('@frontend').'/web/apple-touch-icon-144x144.png'],
            ['exist' => false, 'file' => Yii::getAlias('@frontend').'/web/apple-touch-icon-152x152.png'],
            ['exist' => false, 'file' => Yii::getAlias('@frontend').'/web/apple-touch-icon-180x180.png'],
            ['exist' => false, 'file' => Yii::getAlias('@frontend').'/web/favicon-16x16.png'],
            ['exist' => false, 'file' => Yii::getAlias('@frontend').'/web/favicon-32x32.png'],
            ['exist' => false, 'file' => Yii::getAlias('@frontend').'/web/favicon-96x96.png'],
            ['exist' => false, 'file' => Yii::getAlias('@frontend').'/web/favicon-194x194.png'],
            ['exist' => false, 'file' => Yii::getAlias('@frontend').'/web/android-chrome-192x192.png'],
            ['exist' => false, 'file' => Yii::getAlias('@frontend').'/web/manifest.json'],
            ['exist' => false, 'file' => Yii::getAlias('@frontend').'/web/safari-pinned-tab.svg'],
            ['exist' => false, 'file' => Yii::getAlias('@frontend').'/web/favicon.ico'],

            ['exist' => false, 'file' => Yii::getAlias('@backend').'/web/apple-touch-icon-57x57.png'],
            ['exist' => false, 'file' => Yii::getAlias('@backend').'/web/apple-touch-icon-60x60.png'],
            ['exist' => false, 'file' => Yii::getAlias('@backend').'/web/apple-touch-icon-72x72.png'],
            ['exist' => false, 'file' => Yii::getAlias('@backend').'/web/apple-touch-icon-76x76.png'],
            ['exist' => false, 'file' => Yii::getAlias('@backend').'/web/apple-touch-icon-114x114.png'],
            ['exist' => false, 'file' => Yii::getAlias('@backend').'/web/apple-touch-icon-120x120.png'],
            ['exist' => false, 'file' => Yii::getAlias('@backend').'/web/apple-touch-icon-144x144.png'],
            ['exist' => false, 'file' => Yii::getAlias('@backend').'/web/apple-touch-icon-152x152.png'],
            ['exist' => false, 'file' => Yii::getAlias('@backend').'/web/apple-touch-icon-180x180.png'],
            ['exist' => false, 'file' => Yii::getAlias('@backend').'/web/favicon-16x16.png'],
            ['exist' => false, 'file' => Yii::getAlias('@backend').'/web/favicon-32x32.png'],
            ['exist' => false, 'file' => Yii::getAlias('@backend').'/web/favicon-96x96.png'],
            ['exist' => false, 'file' => Yii::getAlias('@backend').'/web/favicon-194x194.png'],
            ['exist' => false, 'file' => Yii::getAlias('@backend').'/web/android-chrome-192x192.png'],
            ['exist' => false, 'file' => Yii::getAlias('@backend').'/web/manifest.json'],
            ['exist' => false, 'file' => Yii::getAlias('@backend').'/web/safari-pinned-tab.svg'],
            ['exist' => false, 'file' => Yii::getAlias('@backend').'/web/favicon.ico'],

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
}
