<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2016 Power Kernel
 */

namespace backend\controllers;

use common\Core;
use Yii;
use common\models\Service;
use yii\web\NotFoundHttpException;

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
            $user = $client->getUserAttributes();

            $service = Service::find()->where(['name' => $client->getName()])->one();
            if (!$service) {
                $service = new Service();
            }

            /* photoset */
            $data = !empty($service->data) ? json_decode($service->data, true) : [];
            $photoset = null;
            if (empty($data['photoset'])) {
                /* download photo */
                $url = Core::getStorageUrl();
                $photoUrl = $url . '/images/banner.png';
                if (Core::isUrlExist($photoUrl)) {
                    /* download photo */
                    $photoFile = tempnam(sys_get_temp_dir(), 'cma');
                    file_put_contents($photoFile, file_get_contents($photoUrl));
                    /* upload logo */
                    $response = $client->apiUpload(['title'=>Yii::$app->name], $photoFile);
                    unlink($photoFile);

                    /* create photoset */
                    if (!empty($response['photoid'])) {
                        $params = [
                            'method' => 'flickr.photosets.create',
                            'title' => Yii::$app->name,
                            'primary_photo_id' => $response['photoid']
                        ];
                        $resp = $client->api('', 'POST', $params);
                        if (!empty($resp['photoset']['id'])) {
                            $photoset = $resp['photoset']['id'];
                        }
                    }


                }


            } else {
                $photoset = $data['photoset'];
            }
            /* end: photoset */

            if (!empty($photoset)) {
                $service->name = $client->getName();
                $service->title = 'Flickr Photo';
                $service->token = json_encode($client->accessToken);
                $service->data = json_encode([
                    'token' => $client->accessToken->token,
                    'tokenSecret' => $client->accessToken->tokenSecret,
                    'userid' => $user['user']['id'],
                    'username' => json_encode($user['user']['username']),
                    'photoset' => $photoset
                ]);
                $service->save(false);
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Cannot create Flickr photoset.'));
            }

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


}
