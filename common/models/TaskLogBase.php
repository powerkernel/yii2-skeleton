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


if (Yii::$app->params['mongodb']['taskLog']) {
    /**
     * Class TaskLogActiveRecord
     * @package common\models
     */
    class TaskLogActiveRecord extends \yii\mongodb\ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function collectionName()
        {
            return 'task_logs';
        }

        /**
         * @return array
         */
        public function attributes()
        {
            return [
                '_id',
                'task',
                'result',
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
     * Class TaskLogActiveRecord
     * @package common\models
     */
    class TaskLogActiveRecord extends \yii\db\ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return '{{%core_task_logs}}';
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
 * Class TaskLogBase
 * @package common\models
 */
class TaskLogBase extends TaskLogActiveRecord
{

}
