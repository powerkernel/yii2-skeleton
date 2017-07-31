<?php
return [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
        'urlManagerFrontend' => [
            'class' => 'common\components\LocaleUrl',
            'baseUrl' => file_exists(__DIR__.'/../../common/config/localhost.php')?'/':'/', // replace with your domain
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
//        'session' => [
//            'class' => 'yii\mongodb\Session',
//            'sessionCollection'=>'backend_session'
//        ],
        'session' => [
            'class' => 'yii\web\DbSession',
            'sessionTable' => '{{%core_session}}',
            'name' => 'PHPBACKSESSID'
        ],
    ],
];

