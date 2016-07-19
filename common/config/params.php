<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,

    'account'=>[

        'registrationDisabled'=>false,

        'loginDuration' => 2592000,
        'enableCaptcha' => true,
        'minPasswordLength' => 6,
        'rememberMeExpire' => 3600 * 24 * 30,
        'tokenExpire' => 3600,

        'facebookLogin' => true,
        'googleLogin' => true,
        'yahooLogin' => true,

        'extLoginClass'=>false,
        'extResetPassUrl'=>false,

        'adminMenuVisible'=>true,
        'memberMenuVisible'=>true,        
    ],
    
    'settings'=>[
        'supportEmail'=>'support@example.com',
        'admins'=>[1]
    ]
];
