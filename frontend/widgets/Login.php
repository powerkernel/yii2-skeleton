<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2016 Power Kernel
 */

namespace frontend\widgets;


use common\models\LoginForm;
use common\models\Setting;
use Yii;
use yii\base\Widget;

/**
 * Class Login
 * @package frontend\widgets
 */
class Login extends Widget
{
    /**
     * @inheritdoc
     * @return string
     */
    public function run()
    {
        $model = new LoginForm();
        $model->setScenario('default');
        $passLess = Setting::getValue('passwordLessLogin');
        if ($passLess) {
            $model->setScenario('passwordLess');
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            //$model->login();
            //Yii::$app->controller->refresh();
            if($model->login()){
                //Yii::$app->session->setFlash('success', Yii::t('app', 'You have been successfully logged in.'));
                Yii::$app->controller->goBack();
                Yii::$app->end();
            }
        }

        return $this->render('login', [
            'model' => $model,
        ]);

    }
}