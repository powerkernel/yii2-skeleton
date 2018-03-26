<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);


return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [

        'user' => [
            'identityClass' => 'common\models\Account',
            'enableAutoLogin' => true,
            'loginUrl' => ['/account/login']
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
//                [
//                    'class' => 'yii\log\EmailTarget',
//                    'message' => [
//                        'from' => ['webmaster@domain.com'],
//                        'to' => ['admin@domain.com'],
//                        'subject' => 'Error at domain.com',
//                    ],
//                    'levels' => ['error', 'warning'],
//                    'except' => [
//                        'yii\web\HttpException:404',
//                        'yii\debug\Module::checkAccess'
//                    ],
//                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'view' => [
            'theme' => [
                'class' => 'powerkernel\themeadminlte\AdminlteTheme',
                'skin' => 'skin-blue',
                'layout' => 'layout-top-nav' //fixed
            ],
        ],
    ],
    'params' => $params,
    'modules' => [
        'api'=>[
            'class' => 'frontend\modules\api\Module',
        ]
    ]
];
