<?php

namespace common\models;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Setting]].
 *
 * @see Setting
 */
class SettingQuery extends ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Setting[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Setting|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
