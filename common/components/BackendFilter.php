<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */


namespace common\components;


use Yii;
use yii\base\ActionEvent;
use yii\base\Behavior;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


/**
 * ```php
 * public function behaviors()
 * {
 *     return [
 *         'backend' => [
 *             'class' => common\components\BackendFilter::className(),
 *             'actions' => [
 *                 'index',
 *                 'view',
 *                 'create',
 *                 'update',
 *                 'delete'
 *             ],
 *         ],
 *     ];
 * }
 * ```
 */

/**
 * Class BackendFilter
 * @package common\components
 */
class BackendFilter extends Behavior
{
    public $actions = [];

    /**
     * Declares event handlers for the [[owner]]'s events.
     * @return array events (array keys) and the corresponding event handler methods (array values).
     */
    public function events()
    {
        return [Controller::EVENT_BEFORE_ACTION => 'beforeAction'];
    }

    /**
     * @param ActionEvent $event
     * @return bool
     * @throws NotFoundHttpException when the request method is not allowed.
     */
    public function beforeAction($event)
    {

        $action = $event->action->id;
        if (in_array($action, $this->actions) or in_array('*', $this->actions)) {
            if (Yii::$app->id != 'app-backend') {
                $event->isValid = false;
                throw new NotFoundHttpException('The requested page does not exist.');
            }
            $event->action->controller->layout = Yii::$app->view->theme->basePath . '/admin.php';
        }
        return $event->isValid;
    }
}