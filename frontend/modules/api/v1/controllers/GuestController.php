<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2018 Power Kernel
 */


namespace frontend\modules\api\v1\controllers;


use common\forms\CodeVerificationForm;
use common\models\Account;
use common\models\CodeVerification;
use common\models\Setting;
use Yii;
use yii\filters\VerbFilter;


/**
 * Class GuestController
 * @package frontend\modules\api\v1\controllers
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
                'success' => true,
                'data' => [
                    'message' => 'Verification code has been sent',
                    'vid' => (string)$model->id
                ]
            ];
        }
        return [
            'success' => false,
            'data' => [
                'message' => 'Verification code cannot be sent',
            ]
        ];
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
                    'success' => true,
                    'data' => [
                        'token' => $token,
                    ]
                ];
            }
        }
        return [
            'success' => false,
            'data' => [
                'message' => 'Cannot get access token',
            ]
        ];
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
                        Yii::$app->response->content = json_encode([
                            'success' => false,
                            'data' => [
                                'message' => Yii::t('app', 'Invalid api key provided')
                            ]
                        ]);
                        Yii::$app->end(401, Yii::$app->response);
                    }
                }
            }
        }
        if (!empty($missing)) {
            Yii::$app->response->content = json_encode([
                'success' => false,
                'data' => [
                    'message' => Yii::t('app', 'Missing required parameters: {PARAMS}', ['PARAMS' => implode(',', $missing)])
                ]
            ]);
            Yii::$app->end(400, Yii::$app->response);
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
