<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

$map=[];
if(class_exists('yii\mongodb\console\controllers\MigrateController')){
    $map=[
        'controllerMap' => [
            'mongodb-migrate' => 'yii\mongodb\console\controllers\MigrateController'
        ],
    ];
}
if(class_exists('modernkernel\contact\console\MigrateController')){
    $map=[
        'controllerMap' => [
            'contact-migrate' => 'modernkernel\contact\console\MigrateController'
        ],
    ];
}

return array_merge($map, [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'params' => $params,
]);