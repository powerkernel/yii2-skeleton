<?php
$config =  [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=yii2advanced',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
//        'mongodb' => [
//            'class' => '\yii\mongodb\Connection',
//            'dsn'=>'mongodb://user_mg:password@localhost:27017/dbname'
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
            //'hostInfo' => \common\Core::isLocalhost()?'https://domain.dev/':'https://domain.com/',
            //'baseUrl' =>'',
        ],
        'authManager' => [
            //'class' => 'yii\mongodb\rbac\MongoDbManager',
            'class' => 'yii\rbac\DbManager',
            'ruleTable'=>'{{%core_auth_rule}}',
            'assignmentTable'=>'{{%core_auth_assignment}}',
            'itemChildTable'=>'{{%core_auth_item_child}}',
            'itemTable'=>'{{%core_auth_item}}',
            'defaultRoles'=>['member']
        ],

    ],
    'modules' => [],
];

if (!YII_ENV_TEST) {
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
//                'class' => 'yii\mongodb\gii\model\Generator'
//            ]
        ],
    ];
}

return $config;