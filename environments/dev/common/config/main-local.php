<?php
$db = require(__DIR__ . '/db.php');
$config =  [
    'components' => [
        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
            'dsn' => $db['dsn'],
            'options' => $db['options']
        ],
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
