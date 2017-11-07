<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */

namespace frontend\models;

use common\Core;
use himiklab\yii2\recaptcha\ReCaptchaValidator;
use modernkernel\sms\components\AwsSMS;
use Yii;
use yii\base\Model;

/**
 * Class ChangePhoneForm
 * @package frontend\models
 */
class ChangePhoneForm extends Model {

    public $phone;
    public $code;
    public $captcha;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $captcha=[];
        if(Core::isReCaptchaEnabled()){
            $captcha=[
                ['captcha', 'required', 'message' => Yii::t('app', 'Prove you are NOT a robot')],
                ['captcha', ReCaptchaValidator::className(), 'message' => Yii::t('app', 'Prove you are NOT a robot')]
            ];
        }

        $default= [
            [['phone'], 'required'],
            [['code'], 'required', 'on'=>['validation']],

            [['phone'], 'match', 'pattern'=>'/^\+[1-9][0-9]{9,14}$/'],
            [['code'], 'match', 'pattern'=>'/^[0-9]{6}$/'],
            [['code'], 'validateCode', 'skipOnError'=>true],
            [['phone'], 'unique', 'targetAttribute'=>'phone', 'targetClass' => 'common\models\Account', 'message' => Yii::t('app', 'This phone number has already been taken.')],
        ];

        return array_merge($default, $captcha);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'phone' => Yii::t('app', 'Enter your phone number'),
            'captcha' => Yii::t('app', 'Verify Code'),
            'code'=> \Yii::t('app', 'Verification code'),
        ];
    }

    /**
     * set new phone
     * @return bool
     */
    public function setNewPhone()
    {
        if(!empty(\modernkernel\sms\models\Setting::getValue('aws_access_key') && !empty(\modernkernel\sms\models\Setting::getValue('aws_secret_key')))){
            $model=Yii::$app->user->identity;
            $model->new_phone=$this->phone;
            $model->new_phone_code=rand(100000,999999);
            /* send sms */
            $smsSent=(new AwsSMS())->send($model->new_phone, Yii::t('app', '{APP}: Your verification code is {CODE}', ['APP'=>Yii::$app->name, 'CODE'=>$model->new_phone_code]));
            if($smsSent){
                return $model->save();
            }
        }
        return false;
    }

    /**
     * update phone
     * @return mixed
     */
    public function updatePhone()
    {
        $model=Yii::$app->user->identity;
        $model->phone=$this->phone;
        $model->phone_verified=1;
        $model->new_phone=null;
        $model->new_phone_code=null;
        return $model->save();
    }

    /**
     * @param $attribute
     * @param $params
     * @param $validator
     */
    public function validateCode($attribute, $params, $validator)
    {
        $account=Yii::$app->user->identity;
        if($this->code!=$account->new_phone_code){
            $this->addError($attribute, Yii::t('app', 'Wrong code. Please try again.'));
        }
        unset($params, $validator);
    }


}
