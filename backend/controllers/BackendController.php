<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2016 Power Kernel
 */

namespace backend\controllers;


use common\components\MainController;
use Yii;
use yii\filters\AccessControl;

/**
 * Class BackendController
 * @package backend\controllers
 */
class BackendController extends MainController
{
    //public $layout = 'admin';

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->layout = Yii::$app->view->theme->basePath.'/admin.php';
        parent::init();
    }

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
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['error', 'login', 'manifest', 'browser-config'],
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }
}