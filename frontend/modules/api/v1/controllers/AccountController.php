<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2018 Power Kernel
 */


namespace frontend\modules\api\v1\controllers;


use yii\filters\auth\HttpBasicAuth;
use yii\filters\VerbFilter;


/**
 * Class AccountController
 * @package frontend\modules\api\v1\controllers
 */
class AccountController extends \yii\rest\Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'profile' => ['GET'],
            ],
        ];
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::class,
        ];
        return $behaviors;
    }


    /**
     * profile
     * @return array
     */
    public function actionProfile()
    {
        return [
            'success' => true,
            'data' => [
                'id' => (string)\Yii::$app->user->identity->getId(),
                'fullname' => \Yii::$app->user->identity->fullname,
                'email' => \Yii::$app->user->identity->email,
                'phone' => \Yii::$app->user->identity->phone,
            ]
        ];
    }
}
