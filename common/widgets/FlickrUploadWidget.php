<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

namespace common\widgets;


use common\models\Service;
use Yii;
use yii\authclient\OAuthToken;
use yii\base\Widget;

/**
 * Class FlickrUploadWidget
 * @package common\widgets
 */
class FlickrUploadWidget extends Widget
{
    /**
     * @inheritdoc
     * @return string
     */
    public function run(){

        /* @var \common\components\FlickrPhoto $client */


        $flickr=Service::find()->where(['name'=>'flickr-photo'])->one();
        if($flickr){
            $client=Yii::$app->authClientCollection->getClient('flickr-photo');
            if($client){
                $data=json_decode($flickr->data);
                $token = new OAuthToken([
                    'token' => $data->token,
                    'tokenSecret' => $data->tokenSecret
                ]);
                $client->setAccessToken($token);
                /* test */
                $r=$client->api('', 'GET', ['method' => 'flickr.test.login']);
                if($r['stat']=='fail'){
                    $this->alertError();
                }
                else {

                    return $this->render('flickr-upload-widget', ['client'=>$client, 'flickr'=>$flickr]);
                }
            }
            else {
                $this->alertError();
            }
        }
        else {
            $this->alertError();
        }


    }

    /**
     * set alert session
     */
    protected function alertError(){
        Yii::$app->session->setFlash('warning', 'To use Flickr Photo Uploader, you will need configure Flickr Photo Service in Backend.');
    }
}