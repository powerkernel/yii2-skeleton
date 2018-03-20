<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */


namespace common\models;


use Yii;
use yii\behaviors\TimestampBehavior;
use common\behaviors\UTCDateTimeBehavior;


if (Yii::$app->params['mongodb']['page']) {
    /**
     * Class PageDataActiveRecord
     * @package common\models
     */
    class PageDataActiveRecord extends \yii\mongodb\ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function collectionName()
        {
            return 'page_data';
        }

        /**
         * @return array
         */
        public function attributes()
        {
            return [
                '_id',
                'slug',
                'language',
                'title',
                'description',
                'content',
                'keywords',
                'thumbnail',
                'status',
                'created_by',
                'updated_by',
                'created_at',
                'updated_at',
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
     * Class PageDataActiveRecord
     * @package common\models
     */
    class PageDataActiveRecord extends \yii\db\ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return '{{%core_page_data}}';
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
 * Class PageDataBase
 * @package common\models
 */
class PageDataBase extends PageDataActiveRecord
{
}
