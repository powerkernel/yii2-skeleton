<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

namespace common\components;

use yii\rbac\Item;
use yii\rbac\Rule;

/**
 * Checks if owner matches user passed via params
 */
class OwnerRule extends Rule
{
    public $name = 'isOwner';

    /**
     * @param string|integer $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return boolean a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {

        if (isset($params['model'])) {
            $ids=[];
            if (!empty($params['model']->created_by)) {
                $ids[] = $params['model']->created_by;
            }
            if (!empty($params['model']->id_account)) {
                $ids[] = $params['model']->id_account;
            }
            return in_array($user, $ids);
        }
        return false;
    }
}