<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */


namespace common\models;


use Yii;
use yii\base\Model;

/**
 * Class SignInValidationForm
 * @package common\models
 */
class SignInValidationForm extends Model
{
    public $sid;
    public $code;
    public $login;
    public $type;
    public $message;

    /**
     * @inheritdoc
     * @return array
     */
    public function rules()
    {
        return [
            [['sid', 'code'], 'required'],
            [['sid'], 'string'],
            [['code'], 'string', 'length'=>6],

            ['code', 'match', 'pattern'=>'/^[0-9]{6}$/'],
            ['code', 'validateCode'],
            ['message', 'safe']
        ];
    }

    /**
     * validate code
     * @param $attribute
     * @param $params
     * @param $validator
     */
    public function validateCode($attribute, $params, $validator)
    {
        $model=SignIn::findOne($this->sid);
        if($model){
            $this->login=$model->login;
            $this->type=$model->getType();
            if($this->code!=$model->code){
                $model->attempts++;
                $this->addError($attribute, Yii::t('app', 'Wrong code. Please try again.'));
            }
            else {
                $model->status=SignIn::STATUS_USED;
            }
            $model->save();
        }
        else {
            $this->addError($attribute, Yii::t('app', 'Wrong code. Please try again.'));
        }

        unset($params, $validator);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sid' => \Yii::t('app', 'SID'),
            'code'=> \Yii::t('app', 'Verification code'),
        ];
    }




}