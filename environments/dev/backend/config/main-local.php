<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
//        'session' => [
//            'class' => 'yii\mongodb\Session',
//            'sessionCollection'=>'backend_session'
//        ],
        'session' => [
            'class' => 'yii\web\DbSession',
            'sessionTable' => '{{%core_session}}',
            'name' => 'PHPBACKSESSID'
        ],
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => []
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'generators' => [
            'crud' => [
                'class' => 'yii\gii\generators\crud\Generator',
                'templates' => ['skeleton' => '@common/gii/generators/crud/skeleton']
            ],
            'model' => [
                'class' => 'yii\gii\generators\model\Generator',
                'templates' => ['skeleton' => '@common/gii/generators/model/skeleton']
            ],
            'module' => [
                'class' => 'yii\gii\generators\module\Generator',
                'templates' => ['skeleton' => '@common/gii/generators/module/skeleton']
            ],
        ],
    ];
}

return $config;
