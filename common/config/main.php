<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => ['configuration'],
    'components' => [
        'cache' => ['class' => 'yii\caching\FileCache'],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'ruleTable'=>'{{%core_auth_rule}}',
            'assignmentTable'=>'{{%core_auth_assignment}}',
            'itemChildTable'=>'{{%core_auth_item_child}}',
            'itemTable'=>'{{%core_auth_item}}',
            'defaultRoles'=>['member']
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'common\components\DbMessageSource',
                    //'basePath'=>$this->basePath.DIRECTORY_SEPARATOR.'messages',
                    'on missingTranslation' => function ($event) {
                        $event->sender->insertMissingTranslation($event);
                    },
                ],
                'main' => [
                    'class' => 'common\components\DbMessageSource',
                    //'basePath'=>$this->basePath.DIRECTORY_SEPARATOR.'messages',
                    'on missingTranslation' => function ($event) {
                        $event->sender->insertMissingTranslation($event);
                    },
                ],
            ],
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            //'useFileTransport' => true,
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
        'configuration'=>['class'=>'common\bootstrap\Configuration'],
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
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
    ],
    'modules' => [],
];
