<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2018 Power Kernel
 */

namespace frontend\modules\api\controllers;

/**
 * Class DefaultController
 */
class DefaultController extends \yii\rest\Controller
{
    /**
     * @return array
     */
    public function actionIndex(){
        return [
            'status'=>'ok'
        ];
    }
}
