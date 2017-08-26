<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2017 Modern Kernel
 */


namespace common\models;


use Yii;
use yii\behaviors\TimestampBehavior;


if (Yii::$app->params['mongodb']['service']) {
    /**
     * Class ServiceBase
     * @package common\models
     */
    class ServiceBase extends \yii\mongodb\ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function collectionName()
        {
            return 'services';
        }

        /**
         * @return array
         */
        public function attributes()
        {
            return [
                '_id',
                'name',
                'title',
                'token',
                'data',
                'status',
                'created_at',
                'updated_at'
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
     * Class ServiceBase
     * @package common\models
     */
    class ServiceBase extends \yii\db\ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return '{{%core_services}}';
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
