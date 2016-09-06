<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

namespace common\components;


use common\models\Service;
use Yii;
use yii\authclient\OAuthToken;
use yii\base\Action;

/**
 * Class FlickrAction
 * @package common\components
 */
class FlickrAction extends Action
{
    /**
     * @return mixed
     */
    protected function getFlickr(){
        $client=Yii::$app->authClientCollection->getClient('flickr-photo');
        $flickr=Service::findOne('flickr-photo');
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