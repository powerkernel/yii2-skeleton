<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2017 Modern Kernel
 */

namespace common\models;


use Yii;


if (Yii::$app->params['mongodb']['blog']) {
    /**
     * Class BlogBase
     * @package common\models
     */
    class BlogBase extends \yii\mongodb\ActiveRecord
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
    }
} else {
    /**
     * Class BlogBase
     * @package common\models
     */
    class BlogBase extends \yii\db\ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return '{{%core_blog}}';
        }
    }
}
