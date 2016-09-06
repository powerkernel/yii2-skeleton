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
        return $this->render('index');
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
