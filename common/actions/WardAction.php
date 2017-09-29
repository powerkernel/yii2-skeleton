<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2016 Power Kernel
 */

namespace common\actions;

use common\Core;
use Yii;
use yii\base\Action;

/**
 * Class WardAction
 * @package common\actions
 */
class WardAction extends Action
{

    /**
     * run action
     * @param $country
     */
    public function run($country)
    {
        $parents = Yii::$app->request->post('depdrop_parents');
        if (!empty($parents)) {
            $parent_id = $parents[0];
            $data=Core::getWardList($country, $parent_id);
            echo json_encode(['output' => $data, 'selected' => '']);
            return;
        }
        echo json_encode(['output' => '', 'selected' => '']);

//        $parents = Yii::$app->request->post('depdrop_parents');
//        if (!empty($parents)) {
//            $parent_id = $parents[0];
//            $out = (new Query())->select('id, name')
//                ->from('{{%core_ward}}')
//                ->where(['id_state' => $parent_id])
//                ->orderBy('name')
//                ->all();
//            echo json_encode(['output' => $out, 'selected' => '']);
//            return;
//        }
//        echo json_encode(['output' => '', 'selected' => '']);
    }


}