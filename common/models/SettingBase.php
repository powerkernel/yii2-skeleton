<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */


namespace common\models;


use Yii;


if (Yii::$app->params['mongodb']['setting']) {
    /**
     * Class SettingActiveRecord
     * @package common\models
     */
    class SettingActiveRecord extends \yii\mongodb\ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function collectionName()
        {
            return 'settings';
        }

        /**
         * @return array
         */
        public function attributes()
        {
            return [
                '_id',
                'key',
                'value',
                'title',
                'description',
                'group',
                'type',
                'data',
                'default',
                'rules',
                'key_order'
            ];
        }

        /**
         * get id
         * @return \MongoDB\BSON\ObjectID|string
         */
        public function getId()
        {
            return $this->_id;
        }


    }
} else {
    /**
     * Class ActiveRecord
     * @package common\models
     */
    class SettingActiveRecord extends \yii\db\ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return '{{%core_setting}}';
        }


    }
}

/**
 * Class SettingBase
 * @package common\models
 */
class SettingBase extends SettingActiveRecord
{

}