<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2017 Modern Kernel
 */


namespace common\models;


use Yii;


if (Yii::$app->params['mongodb']['auth']) {
    /**
     * Class AuthActiveRecord
     * @package common\models
     */
    class AuthActiveRecord extends \yii\mongodb\ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function collectionName()
        {
            return 'auth';
        }

        /**
         * @return array
         */
        public function attributes()
        {
            return [
                '_id',
                'user_id',
                'source',
                'source_id',
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
     * Class AuthActiveRecord
     * @package common\models
     */
    class AuthActiveRecord extends \yii\db\ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return '{{%core_auth}}';
        }


    }
}

/**
 * Class AuthBase
 * @package common\models
 */
class AuthBase extends AuthActiveRecord
{
}