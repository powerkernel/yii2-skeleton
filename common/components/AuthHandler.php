<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */


namespace common\components;

use common\models\Auth;
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
        $continue=false;
        $id = ArrayHelper::getValue($attributes, 'id');
        $fullname='';
        $email='';

        // google
        if($this->client->getName()=='google'){
            $fullname= ArrayHelper::getValue($attributes, 'displayName');
            $emails = ArrayHelper::getValue($attributes, 'emails');
            $email=$emails[0]['value'];
            $continue=true;
        }
        // facebook
        if($this->client->getName()=='facebook'){
            $fullname = ArrayHelper::getValue($attributes, 'name');
            $email = ArrayHelper::getValue($attributes, 'email');
            $continue=true;
        }

        if(!$continue){
//            Yii::$app->getSession()->setFlash('info', [
//                Yii::t('app', 'Flickr'),
//            ]);
            //Yii::$app->user->setReturnUrl(Yii::$app->request->referrer);
            return;
        }

        /* @var Auth $auth */
        $auth = Auth::find()->where([
            'source' => $this->client->getId(),
            'source_id' => $id,
        ])->one();


        if (Yii::$app->user->isGuest) {

            if ($auth) { // login
                /* @var Account $user */
                $user = $auth->user;
                $this->updateUserInfo($user);
                Yii::$app->user->login($user, Setting::getValue('rememberMeDuration'));
            } else { // signup
                if ($email !== null && Account::find()->where(['email' => $email])->exists()) {
                    Yii::$app->getSession()->setFlash('error', [
                        Yii::t('app', "User with the same email as in {client} account already exists but isn't linked to it. Login using email first to link it.", ['client' => $this->client->getTitle()]),
                    ]);
                } else {

                    $password = Yii::$app->security->generateRandomString(6);
                    $user = new Account([
                        'fullname' => $fullname,
                        'email' => $email,
                        'password' => $password,
                    ]);
                    $user->generateAuthKey();
                    $user->generatePasswordResetToken();


                    $transaction = Account::getDb()->beginTransaction();
                    //file_put_contents('D:\log', json_encode($transaction));

                    if ($user->save()) {

                        $auth = new Auth([
                            'user_id' => $user->id,
                            'source' => $this->client->getId(),
                            'source_id' => (string)$id,
                        ]);
                        if ($auth->save()) {
                            $transaction->commit();

                            Yii::$app->user->login($user, Setting::getValue('rememberMeDuration'));
                        } else {
                            $transaction->rollBack();
                            Yii::$app->getSession()->setFlash('error', [
                                Yii::t('app', 'Unable to save {client} account: {errors}', [
                                    'client' => $this->client->getTitle(),
                                    'errors' => json_encode($auth->getErrors()),
                                ]),
                            ]);
                        }
                    } else {
                        $transaction->rollBack();
                        Yii::$app->getSession()->setFlash('error', [
                            Yii::t('app', 'Unable to save user: {errors}', [
                                'client' => $this->client->getTitle(),
                                'errors' => json_encode($user->getErrors()),
                            ]),
                        ]);
                    }
                }
            }
        }
        else { // user already logged in
            Yii::$app->user->setReturnUrl(Yii::$app->request->referrer);
            if (!$auth) { // add auth provider
                $auth = new Auth([
                    'user_id' => Yii::$app->user->id,
                    'source' => $this->client->getId(),
                    'source_id' => (string)$attributes['id'],
                ]);
                if ($auth->save()) {
                    /** @var Account $user */
                    $user = $auth->user;
                    $this->updateUserInfo($user);
                    Yii::$app->getSession()->setFlash('success', [
                        Yii::t('app', 'Linked {client} account.', [
                            'client' => $this->client->getTitle()
                        ]),
                    ]);
                } else {
                    Yii::$app->getSession()->setFlash('error', [
                        Yii::t('app', 'Unable to link {client} account: {errors}', [
                            'client' => $this->client->getTitle(),
                            'errors' => json_encode($auth->getErrors()),
                        ]),
                    ]);
                }
            }
            else { // there's existing auth
                Yii::$app->getSession()->setFlash('error', [
                    Yii::t('app',
                        'Unable to link {client} account. There is another user using it.',
                        ['client' => $this->client->getTitle()]),
                ]);
            }
        }
    }

    /**
     * @param Account $user
     */
    private function updateUserInfo(Account $user)
    {
        $attributes = $this->client->getUserAttributes();
//        $github = ArrayHelper::getValue($attributes, 'login');
//        if ($user->github === null && $github) {
//            $user->github = $github;
//            $user->save();
//        }
    }
}