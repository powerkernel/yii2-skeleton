<?php
namespace frontend\models;

use Yii;
use yii\base\Model;


/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $password;
    public $passwordConfirm;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password', 'passwordConfirm'], 'required'],
            [['password', 'passwordConfirm'], 'string', 'min' => 6],
            [['passwordConfirm'], 'compare', 'compareAttribute' => 'password'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'password' => Yii::t('app', 'Password'),
            'passwordConfirm' => Yii::t('app', 'Confirm Password'),
        ];
    }


    /**
     * set new password
     * @param $account
     * @return bool
     */
    public function setPassword($account){
        if(!$account){
            return false;
        }
        $account->setPassword($this->password);
        $account->removePasswordResetToken();
        return $account->save(false);
    }
}
