<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */

namespace common\actions;


use common\models\Service;
use Yii;
use yii\authclient\OAuthToken;
use yii\base\Action;

/**
 * Class FlickrAction
 * @package common\actions
 */
class FlickrAction extends Action
{
    /**
     * @return mixed
     */
    protected function getFlickr(){
        $client=Yii::$app->authClientCollection->getClient('flickr-photo');
        $flickr=Service::find()->where(['name'=>'flickr-photo'])->one();
        if($flickr){
            $data=json_decode($flickr->data);
            $token = new OAuthToken([
                'token' => $data->token,
                'tokenSecret' => $data->tokenSecret
            ]);
            $client->setAccessToken($token);
            /* test */
            $r=$client->api('', 'GET', ['method' => 'flickr.test.login']);
            if($r['stat']=='ok'){
                return $client;
            }
            return false;
        }
        return false;
    }
}