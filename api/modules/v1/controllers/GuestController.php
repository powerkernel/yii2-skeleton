<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2018 Power Kernel
 */


namespace api\modules\v1\controllers;


use common\forms\CodeVerificationForm;
use common\models\Account;
use common\models\CodeVerification;
use common\models\Setting;
use Yii;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;


/**
 * Class GuestController
 * @package api\modules\v1\controllers
 */
class GuestController extends \yii\rest\Controller
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
                'send-login-code' => ['POST'],
                'get-access-token' => ['POST'],
            ],
        ];
        return $behaviors;
    }

    /**
     * send login code
     * @return array
     */
    public function actionSendLoginCode()
    {
        $p = $this->checkParams(['identifier', 'api']);
        /* send code */
        $model = new CodeVerification();
        $model->identifier = $p['identifier'];
        if ($model->validate() && $model->save()) {
            return [
                'message' => 'Verification code has been sent',
                'vid' => (string)$model->id
            ];
        } else {
            $error='';
            foreach ($model->getFirstErrors() as $attr=>$message){
                $error.=$attr.': '.$message;
            }
            throw new BadRequestHttpException($error);
        }
    }

    /**
     * get account access token
     * @return array
     */
    public function actionGetAccessToken()
    {
        $p = $this->checkParams(['vid', 'identifier', 'code']);
        $model = new CodeVerificationForm();
        $model->identifier = $p['identifier'];
        $model->code = $p['code'];
        $model->vid = $p['vid'];
        if ($model->validate()) {
            $token = $this->getAccountToken($model->identifier);
            if ($token) {
                return [
                    'token' => $token,
                ];
            }
        }
        else {
            $error='';
            foreach ($model->getFirstErrors() as $attr=>$message){
                $error.=$attr.': '.$message;
            }
            throw new BadRequestHttpException($error);
        }
    }

    /**
     * check POST params
     * @param $params
     * @return array
     */
    protected function checkParams($params)
    {
        $p = [];
        $missing = [];
        foreach ($params as $param) {
            if (empty(trim(Yii::$app->request->post($param)))) {
                $missing[] = $param;
            } else {
                $p[$param] = Yii::$app->request->post($param);
                if ($param == 'api') {
                    /* check api */
                    if ($p[$param] != Setting::getValue('appApi')) {
                        throw new BadRequestHttpException(Yii::t('app', 'Invalid api key provided.'));
                    }
                }
            }
        }
        if (!empty($missing)) {
            throw new BadRequestHttpException(Yii::t('app', 'Missing required parameters: {PARAMS}', ['PARAMS' => implode(',', $missing)]));
        }
        return $p;
    }

    /**
     * Get account token
     * @param $identifier
     * @return boolean|string
     */
    protected function getAccountToken($identifier)
    {
        if (Account::getIdentifierType($identifier) == 'email') {
            $account = Account::findByEmail($identifier);
        } else {
            $account = Account::findByPhone($identifier);
        }
        if ($account) {
            if (empty($account->access_token)) {
                $account->generateAccessToken();
                if ($account->save()) {
                    return $account->access_token;
                } else {
                    return false;
                }
            } else {
                return $account->access_token;
            }
        } else {
            /* create new account */
            $account = new Account();
            $account->fullname = $identifier;
            if (Account::getIdentifierType($identifier) == 'email') {
                $account->email = $identifier;
            } else {
                $account->phone = $identifier;
            }
            $account->generateAccessToken();
            if ($account->save()) {
                return $account->access_token;
            } else {
                return false;
            }
        }
    }
}
