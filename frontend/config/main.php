<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);


$modules = scandir(__DIR__ . '/../../vendor/modernkernel');
$urlManager = [
    'ignoreLanguageUrlPatterns' => [
        '#^account/login/google|facebook|yahoo#' => '#^account/login/google|facebook|yahoo#',
        '#^admin/default/sitemap#' => '#^admin/default/sitemap#',
    ],
    'rules' => [
        '' => 'site/index',
        'sitemap.xml' => 'site/sitemap',

        'blog/<action:(manage|create|update|delete)>' => 'blog/<action>',
        'blog' => 'blog/index',
        'blog/<name:.+?>' => 'blog/view',
        'blog/sitemap<page:\d+>.xml' => 'blog/sitemap',


    ],
];
foreach ($modules as $module) {
    if (!preg_match('/[\.]+/', $module)) {
        $urlManagerFile = __DIR__ . '/../../vendor/modernkernel/' . $module . '/urlManager.php';
        if (is_file($urlManagerFile)) {
            $urlManagerConfig = require($urlManagerFile);
            $urlManager['ignoreLanguageUrlPatterns'] = array_merge(
                $urlManager['ignoreLanguageUrlPatterns'],
                $urlManagerConfig['ignoreLanguageUrlPatterns']
            );
            $urlManager['rules'] = array_merge(
                $urlManager['rules'],
                $urlManagerConfig['rules']
            );
        }
    }
}


return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'session' => [
            'class' => 'yii\web\DbSession',
            'sessionTable' => '{{%core_session}}',
            'name' => 'PHPFRONTSESSID'
        ],
        'user' => [
            'identityClass' => 'common\models\Account',
            'enableAutoLogin' => true,
            'loginUrl' => ['/account/login']
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'class' => 'common\components\LocaleUrl',
            /* config */
            //'languages' => ['vi-VN', 'en-US'],
            'languageParam' => 'lang',
            'enableLanguagePersistence' => false,
            'enableDefaultLanguageUrlCode' => false,
            'enableLanguageDetection' => false,
            'ignoreLanguageUrlPatterns' => $urlManager['ignoreLanguageUrlPatterns'],

            /* yii */
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            //'enableStrictParsing'=>true,
            'rules' => $urlManager['rules']
        ],
        'view' => [
            'theme' => [
                //'class' => 'harrytang\themeinspinia\InspiniaTheme',
                'class' => 'modernkernel\themeadminlte\AdminlteTheme',
                'skin' => 'skin-blue',
                'layout' => 'layout-top-nav' //fixed
            ],
        ],
    ],
    'params' => $params,
];
