<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => ['setting'],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'setting'=>[
            'class'=>'common\bootstrap\Setting'
        ]
    ],
];
