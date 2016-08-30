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
        'session' => [
            'class' => 'yii\web\DbSession',
            'sessionTable' => '{{%core_session}}',
            'name' => 'PHPFRONTSESSID'
        ],
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
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'class' => 'common\components\LocaleUrl',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'view' => [
            'theme' => [
                //'class' => 'harrytang\themeinspinia\InspiniaTheme',
                'class' => 'modernkernel\themeadminlte\AdminlteTheme',
                'skin' => 'skin-blue',
                'layout' => 'layout-top-nav' //fixed
            ],
        ],
    ],
    'params' => $params,
];
