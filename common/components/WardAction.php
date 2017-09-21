<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2016 Power Kernel
 */

namespace common\components;

use Yii;
use yii\base\Action;
use yii\db\Query;

/**
 * Class WardAction
 * @package common\components
 */
class WardAction extends Action
{

    /**
     * run action
     */
    public function run()
    {
        $parents = Yii::$app->request->post('depdrop_parents');
        if (!empty($parents)) {
            $parent_id = $parents[0];
            $out = (new Query())->select('id, name')
                ->from('{{%core_ward}}')
                ->where(['id_state' => $parent_id])
                ->orderBy('name')
                ->all();
            echo json_encode(['output' => $out, 'selected' => '']);
            return;
        }
        echo json_encode(['output' => '', 'selected' => '']);
    }


}