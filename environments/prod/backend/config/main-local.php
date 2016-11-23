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
    ],
];
