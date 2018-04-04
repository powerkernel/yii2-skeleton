<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);


return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'api\controllers',
    'components' => [
        'session' => [
            'class' => 'yii\mongodb\Session',
            'sessionCollection'=>'core_session_api'
        ],
        'user' => [
            'identityClass' => 'common\models\Account',
            'enableAutoLogin' => false,
            'enableSession'=>false,
            'loginUrl' => null
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'except' => [
                        //'yii\web\HttpException:404',
                        'yii\debug\Module::checkAccess'
                    ],
                ],
            ],
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'format' =>  \yii\web\Response::FORMAT_JSON,
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                if ($response->data !== null) {
                    $response->data = [
                        'success' => $response->isSuccessful,
                        'data' => $response->data,
                    ];
                    $response->statusCode = 200;
                }
            },
        ]
    ],
    'params' => $params,
    'modules' => [
        'v1'=>[
            'class' => 'api\modules\v1\Module',
        ]
    ]
];
