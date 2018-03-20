<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */


namespace common\models;


use common\behaviors\UTCDateTimeBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;


if (Yii::$app->params['mongodb']['menu']) {
    /**
     * Class MenuActiveRecord
     * @package common\models
     */
    class MenuActiveRecord extends \yii\mongodb\ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function collectionName()
        {
            return 'menu';
        }

        /**
         * @return array
         */
        public function attributes()
        {
            return [
                '_id',
                'id_parent',
                'label',
                'active_route',
                'url',
                'class',
                'position',
                'order',
                'status',
                'created_at',
                'updated_at',
                'created_by',
                'updated_by'
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

        /**
         * @inheritdoc
         */
        public function behaviors()
        {
            return [
                UTCDateTimeBehavior::class,
            ];
        }

        /**
         * @return int timestamp
         */
        public function getUpdatedAt()
        {
            return $this->updated_at->toDateTime()->format('U');
        }

        /**
         * @return int timestamp
         */
        public function getCreatedAt()
        {
            return $this->created_at->toDateTime()->format('U');
        }
    }
} else {
    /**
     * Class MenuActiveRecord
     * @package common\models
     */
    class MenuActiveRecord extends \yii\db\ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return '{{%core_menu}}';
        }

        /**
         * @inheritdoc
         */
        public function behaviors()
        {
            return [
                TimestampBehavior::class,
            ];
        }

        /**
         * @return int timestamp
         */
        public function getUpdatedAt()
        {
            return $this->updated_at;
        }

        /**
         * @return int timestamp
         */
        public function getCreatedAt()
        {
            return $this->created_at;
        }
    }
}


/**
 * Class MenuBase
 * @package common\models
 */
class MenuBase extends MenuActiveRecord
{

}
