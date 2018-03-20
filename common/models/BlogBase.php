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


if (Yii::$app->params['mongodb']['blog']) {
    /**
     * Class BlogActiveRecord
     * @package common\models
     */
    class BlogActiveRecord extends \yii\mongodb\ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function collectionName()
        {
            return 'blog';
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
                'desc',
                'content',
                'tags',
                'thumbnail',
                'thumbnail_square',
                'image_object',
                'created_by',
                'views',
                'status',
                'created_at',
                'updated_at',
                'published_at',
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

        /**
         * @return int timestamp
         */
        public function getPublishedAt()
        {
            return $this->published_at->toDateTime()->format('U');
        }
    }
} else {
    /**
     * Class BlogActiveRecord
     * @package common\models
     */
    class BlogActiveRecord extends \yii\db\ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return '{{%core_blog}}';
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

        /**
         * @return int timestamp
         */
        public function getPublishedAt()
        {
            return $this->published_at;
        }
    }
}

/**
 * Class BlogBase
 * @package common\models
 */
class BlogBase extends BlogActiveRecord
{

}
