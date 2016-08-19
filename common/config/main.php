<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => ['configuration'],
    'components' => [
        'configuration'=>[
            'class'=>'common\bootstrap\Configuration'
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
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
            ],
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            //'clients' => [
//                'google' => [
//                    'class' => 'yii\authclient\clients\Google',
//                    //'clientId' => 'client_id',
//                    //'clientSecret' => 'client_secret',
//                ],
//                'facebook' => [
//                    'class' => 'yii\authclient\clients\Facebook',
//                    'clientId' => 'client_id',
//                    'clientSecret' => 'client_secret',
//                ],
            //],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => false,
        ],
        'reCaptcha' => [
            'name' => 'reCaptcha',
            'class' => 'himiklab\yii2\recaptcha\ReCaptcha',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],

    ],
];
