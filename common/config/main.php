<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => ['configuration'],
    'components' => [
        'cache' => ['class' => 'yii\caching\FileCache'],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => \common\Core::isLocalhost()
        ],
        'reCaptcha' => [
            'name' => 'reCaptcha',
            'class' => 'himiklab\yii2\recaptcha\ReCaptcha',
        ],
        'urlManager' => [
            'class' => 'common\components\LocaleUrl',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'urlManagerFrontend' => [
            'class' => 'common\components\LocaleUrl',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'urlManagerBackend' => [
            'class' => 'common\components\LocaleUrl',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'configuration'=>['class'=>'common\bootstrap\Configuration'],
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'hashCallback' => function ($path) {
                return hash('md4', $path);
            },
            'appendTimestamp' => true,
            'linkAssets' => true,
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js' => [
                        YII_ENV_DEV ? 'jquery.js' : 'jquery.min.js'
                    ]
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [
                        YII_ENV_DEV ? 'css/bootstrap.css' :         'css/bootstrap.min.css',
                    ]
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js' => [
                        YII_ENV_DEV ? 'js/bootstrap.js' : 'js/bootstrap.min.js',
                    ]
                ]
            ],
        ],
        'authManager' => [
            'class' => 'yii\mongodb\rbac\MongoDbManager',
            'itemCollection'=>'core_auth_item',
            'ruleCollection'=>'core_auth_rule',
            'assignmentCollection'=>'core_auth_assignment',
            'defaultRoles'=>['member']
        ],
    ],
    'modules' => [
        'sms' => [
            'class' => 'powerkernel\sms\Module',
        ],
    ],
];
