<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2017 Modern Kernel
 */


namespace common\models;


use Yii;
use yii\behaviors\TimestampBehavior;


if (Yii::$app->params['mongodb']['menu']) {
    /**
     * Class ActiveRecord
     * @package common\models
     */
    class ActiveRecord extends \yii\mongodb\ActiveRecord
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
         * @param bool $insert
         * @return bool
         */
        public function beforeSave($insert)
        {
            $this->updateDate($insert);
            return parent::beforeSave($insert);
        }

        /**
         * Update date
         * @param $insert boolean
         */
        protected function updateDate($insert)
        {
            $time = new \MongoDB\BSON\UTCDateTime();
            if ($insert) {
                $this->created_at = $time;
            }
            $this->updated_at = $time;
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
     * Class ActiveRecord
     * @package common\models
     */
    class ActiveRecord extends \yii\db\ActiveRecord
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
                TimestampBehavior::className(),
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
class MenuBase extends ActiveRecord
{

}
