<?php

namespace backend\controllers;

use common\models\LoginForm;
use Yii;
use yii\web\Controller;

/**
 * Class AccountController
 * @package backend\controllers
 */
class AccountController extends Controller
{



    /**
     * login
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        $this->layout='login';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }



}