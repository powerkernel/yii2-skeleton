<?php
//use u1234567_db
//db.createUser(
//     {
//          user: "u1234567_mg",
//          pwd: "abcd1234xxx",
//          roles: [ { role: "readWrite", db: "u1234567_db" } ]
//    }
//)
// add file hosts 127.0.0.1 dbserver
$config =  [
    'components' => [
//        'mongodb' => [
//            'class' => '\yii\mongodb\Connection',
//            'dsn'=>'mongodb://user_mg:password@localhost:27017/dbname',
//            'options'=>['ssl'=>true]
//        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => \common\Core::isLocalhost()
        ],
        'urlManagerFrontend' => [
            'class' => 'common\components\LocaleUrl',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            //'baseUrl' => \common\Core::isLocalhost()?'https://dev.domain.com/':'https://domain.com/',
        ],
        'urlManagerBackend' => [
            'class' => 'common\components\LocaleUrl',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            //'baseUrl' => \common\Core::isLocalhost()?'https://dev.backend.domain.com/':'https://backend.domain.com/',
        ],
    ],
    'modules' => [],
];

return $config;
