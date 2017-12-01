<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request'=>[
            'csrfParam'=>'_csrf_b'
        ],
        'user' => [
            'identityClass' => 'common\models\Account',
            'enableAutoLogin' => true,
            'identityCookie'=>['name' => '_identity_b', 'httpOnly' => true],
            'loginUrl'=>['/account/login']
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'view' => [
            'theme' => [
                'class' => 'powerkernel\themeadminlte\AdminlteTheme',
                'layout'=>'sidebar-mini' //fixed
            ],
        ],
    ],
    'params' => $params,
];
