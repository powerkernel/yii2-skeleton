<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

$map = [];
$controllerMap = [];
if (class_exists('yii\mongodb\console\controllers\MigrateController')) {
    $controllerMap['mongodb-migrate'] = 'yii\mongodb\console\controllers\MigrateController';
}
if (class_exists('modernkernel\contact\console\MigrateController')) {
    $controllerMap['contact-migrate'] = 'modernkernel\contact\console\MigrateController';
}
if (class_exists('modernkernel\sms\console\MigrateController')) {
    $controllerMap['sms-migrate'] = 'modernkernel\sms\console\MigrateController';
}
if (class_exists('modernkernel\support\console\MigrateController')) {
    $controllerMap['support-migrate'] = 'modernkernel\support\console\MigrateController';
}
if (class_exists('modernkernel\billing\console\MigrateController')) {
    $controllerMap['billing-migrate'] = 'modernkernel\billing\console\MigrateController';
}
if (class_exists('harrytang\hosting\console\MigrateController')) {
    $controllerMap['hosting-migrate'] = 'harrytang\hosting\console\MigrateController';
}

$map = [
    'controllerMap' => $controllerMap
];

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