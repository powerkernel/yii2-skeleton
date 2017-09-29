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
 * Class DistrictAction
 * @package common\actions
 */
class DistrictAction extends Action
{

    /**
     * run action
     * @param string $country
     */
    public function run($country)
    {
        $parents = Yii::$app->request->post('depdrop_parents');
        if (!empty($parents)) {
            $parent_id = $parents[0];
            $data=Core::getDistrictList($country, $parent_id);
            echo json_encode(['output' => $data, 'selected' => '']);
            return;
        }
        echo json_encode(['output' => '', 'selected' => '']);

    }


}