<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

namespace backend\controllers;


use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Class BackendController
 * @package backend\controllers
 */
class BackendController extends Controller
{
    public $layout = 'admin';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['error', 'login'],
                        'allow' => true,
                    ],
                    [
                        //'actions' => ['*'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],

        ];
    }
}