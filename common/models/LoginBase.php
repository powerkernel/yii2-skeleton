<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */

namespace common\models;

use Yii;
use common\behaviors\UTCDateTimeBehavior;
use yii\behaviors\TimestampBehavior;


if (Yii::$app->params['mongodb']['login']) {
    /**
     * Class LoginActiveRecord
     * @package common\models
     */
    class LoginActiveRecord extends \yii\mongodb\ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function collectionName()
        {
            return 'login';
        }

        /**
         * @return array
         */
        public function attributes()
        {
            return [
                '_id',
                'email',
                'token',
                'status',
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
     * Class LoginActiveRecord
     * @package common\models
     */
    class LoginActiveRecord extends \yii\db\ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return '{{%core_login}}';
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
 * Class LoginBase
 * @package common\models
 */
class LoginBase extends LoginActiveRecord
{

}
