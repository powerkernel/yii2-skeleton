<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
    ],
];

if (file_exists(__DIR__.'/../../common/config/localhost.php')) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'panels' => [
            'mongodb' => [
                'class' => 'yii\mongodb\debug\MongoDbPanel',
                'db' => 'mongodb',
            ],
        ],
        'allowedIPs' => [],
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
//            'mongoDbModel' => [
//                'class' => 'yii\mongodb\gii\model\Generator',
//                'templates' => ['skeleton' => '@common/gii/generators/mongoDbModel/skeleton']
//            ]
        ],
    ];
}

return $config;
