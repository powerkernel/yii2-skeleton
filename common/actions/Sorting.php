<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */


namespace common\actions;


use yii\base\Action;
use yii\web\BadRequestHttpException;

/**
 * Class Sorting
 * @package common\actions
 */
class Sorting extends Action
{
    /** @var \yii\mongodb\ActiveQuery|\yii\db\ActiveQuery */
    public $query;

    /** @var string */
    public $orderAttribute = 'order';

    /**
     * @throws BadRequestHttpException
     */
    public function run()
    {
        foreach (\Yii::$app->request->post('sorting') as $order => $id) {
            $query = clone $this->query;
            $model = $query->andWhere(['_id' => $id])->one();

            if ($model === null) {
                throw new BadRequestHttpException();
            }
            $model->{$this->orderAttribute} = $order;
            $model->update(false, [$this->orderAttribute]);
        }


    }
}
