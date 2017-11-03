<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */

namespace common\widgets;


use common\models\PassLoginForm;
use Yii;
use yii\base\Widget;

/**
 * Class PassSignIn
 * @package common\widgets
 */
class PassSignIn extends Widget
{
    /**
     * @inheritdoc
     * @return string
     */
    public function run()
    {
        $model = new PassLoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->login()) {
                Yii::$app->controller->goBack();
                Yii::$app->end();
            }
        }
        $client=false;
        if(Yii::$app->authClientCollection->hasClient('facebook') or Yii::$app->authClientCollection->hasClient('google')){
            $client=true;
        }

        return $this->render('pass-sign-in', [
            'model' => $model,
            'client'=>$client
        ]);

    }
}