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
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'ruleTable'=>'{{%core_auth_rule}}',
            'assignmentTable'=>'{{%core_auth_assignment}}',
            'itemChildTable'=>'{{%core_auth_item_child}}',
            'itemTable'=>'{{%core_auth_item}}',
            'defaultRoles'=>['member']
        ],
    ],
];
