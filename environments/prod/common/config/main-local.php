<?php
return [
    'components' => [
//        'mongodb' => [
//            'class' => '\yii\mongodb\Connection',
//            'dsn'=>'mongodb://u6353857_mg:xM5mVgcRAFx4WHLe@localhost:27017/u6353857_db'
//        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => \common\Core::isLocalhost()
        ],
        'urlManagerFrontend' => [
            'class' => 'common\components\LocaleUrl',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            //'hostInfo' => \common\Core::isLocalhost()?'https://domain.dev/':'https://domain.com/',
            //'baseUrl' =>'',
        ],
    ],
    'modules' => [],
];
