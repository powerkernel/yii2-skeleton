<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2018 Power Kernel
 */

namespace common\forms;

use common\models\CodeVerification;
use Yii;
use yii\base\Model;

/**
 * Class CodeVerificationForm
 * @package common\models
 */
class CodeVerificationForm extends Model
{
    public $vid;
    public $code;
    public $identifier;
    public $type;
    public $message;

    /**
     * @inheritdoc
     * @return array
     */
    public function rules()
    {
        return [
            [['vid', 'code'], 'required'],
            [['vid'], 'string'],
            [['code'], 'string', 'length' => 6],

            ['code', 'match', 'pattern' => '/^[0-9]{6}$/'],
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
        //$model = CodeVerification::findOne($this->vid);
        $model = CodeVerification::find()->where(['_id'=>$this->vid, 'status'=>CodeVerification::STATUS_NEW])->one();
        if ($model) {
            $this->identifier = $model->identifier;
            $this->type = $model->getType();
            if ($this->code != $model->code) {
                $model->attempts++;
                $this->addError($attribute, Yii::t('app', 'Wrong code. Please try again.'));
            } else {
                $model->status = CodeVerification::STATUS_USED;
            }
            $model->save();
        } else {
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
            'vid' => \Yii::t('app', 'VID'),
            'code' => \Yii::t('app', 'Verification code'),
        ];
    }
}
