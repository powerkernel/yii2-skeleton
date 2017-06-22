<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
//        'session' => [
//            'class' => 'yii\mongodb\Session',
//            'sessionCollection'=>'frontend_session'
//        ],
        'session' => [
            'class' => 'yii\web\DbSession',
            'sessionTable' => '{{%core_session}}',
            'name' => 'PHPFRONTSESSID'
        ],
    ],
];


return $config;
