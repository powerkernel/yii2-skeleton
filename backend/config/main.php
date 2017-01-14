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
        'session' => [
            'class' => 'yii\web\DbSession',
            'sessionTable' => '{{%core_session}}',
            'name' => 'PHPBACKSESSID'
        ],
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
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
        'view' => [
            'theme' => [
                //'class' => 'harrytang\themeinspinia\InspiniaTheme',
                'class' => 'modernkernel\themeadminlte\AdminlteTheme',
                //'skin'=>'skin-red-light',
                'layout'=>'sidebar-mini' //fixed
            ],
        ],
    ],
    'params' => $params,
];
