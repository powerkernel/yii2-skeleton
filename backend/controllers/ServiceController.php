<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

namespace backend\controllers;

use common\widgets\FlickrPhoto;
use nirvana\jsonld\JsonLDHelper;
use Yii;
use common\models\Service;
use common\models\ServiceSearch;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ServiceController implements the CRUD actions for Service model.
 */
class ServiceController extends BackendController
{


    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    /**
     * @param $client
     */
    public function onAuthSuccess($client)
    {
        if ($client->getName() == 'flickr-photo') {
            /* @var FlickrPhoto $client */
            $service = Service::find()->where(['name' => $client->getName()])->one();
            if (!$service) {
                $service = new Service();
            }

            $user=$client->getUserAttributes();

            $service->name = $client->getName();
            $service->title = 'Flickr Photo';
            $service->token = json_encode($client->accessToken);
            $service->data = json_encode([
                'token' => $client->accessToken->token,
                'tokenSecret' => $client->accessToken->tokenSecret,
                'userid'=>$user['user']['id'],
                'username'=>json_encode($user['user']['username'])
            ]);

            $service->save(false);
        }
        Yii::$app->user->setReturnUrl(Yii::$app->urlManager->createUrl(['/service/index']));
    }

    /**
     * Lists all Service models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->view->title = Yii::t('app', 'Services');

        $models = Service::find()->asArray()->all();

        $services = [];
        foreach ($models as $model) {
            $services[$model['name']] = $model;
        }

        return $this->render('index', [
            'services' => $services,
        ]);
    }



    /**
     * Finds the Service model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Service the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Service::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * register metaTags and JsonLD info
     * @param array $data
     */
    protected function registerMetaTagJsonLD($data = [])
    {
        if (!empty($data['jsonLd'])) {
            JsonLDHelper::add($data['jsonLd']);
        }
        if (!empty($data['metaTags'])) {
            foreach ($data['metaTags'] as $tag) {
                $this->view->registerMetaTag($tag);
            }
        }
    }
}
