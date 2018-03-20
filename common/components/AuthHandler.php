<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2016 Power Kernel
 */


namespace common\components;

use common\models\Account;
use common\models\Setting;
use Yii;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;

/**
 * AuthHandler handles successful authentication via Yii auth component
 */
class AuthHandler
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * AuthHandler constructor.
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * handle
     */
    public function handle()
    {
        $attributes = $this->client->getUserAttributes();

        // common
        $continue = false;
        $fullname = '';
        $email = '';

        // google
        if ($this->client->getName() == 'google') {
            $fullname = ArrayHelper::getValue($attributes, 'displayName');
            $emails = ArrayHelper::getValue($attributes, 'emails');
            $email = $emails[0]['value'];
            $continue = true;
        }
        // facebook
        if ($this->client->getName() == 'facebook') {
            $fullname = ArrayHelper::getValue($attributes, 'name');
            $email = ArrayHelper::getValue($attributes, 'email');
            $continue = true;
        }

        if (!$continue) {
            return;
        }

        /* login base on email */
        if (!empty($email)) {
            $account = Account::find()->where(['email' => $email])->one();
            if ($account) {
                $this->existingAccountLogin($account);
            } else {
                $this->newAccountLogin($fullname, $email);
            }
        }
        else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'The system cannot log you on at this time!'));
        }
    }

    /**
     * existing account login
     * @param $account
     */
    protected function existingAccountLogin($account){
        Yii::$app->user->login($account, Setting::getValue('rememberMeDuration'));
    }

    /**
     * new account login
     * @param $fullname
     * @param $email
     */
    protected function newAccountLogin($fullname, $email)
    {
        $account = new Account();
        $account->fullname = $fullname;
        $account->email = $email;
        $account->save();
        Yii::$app->user->login($account, Setting::getValue('rememberMeDuration'));
    }
}
