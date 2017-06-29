<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2017 Modern Kernel
 */

namespace common\models;


use Yii;



if(Yii::$app->params['mongodb']['taskLog']) {
    /**
     * Class TaskLogBase
     * @package common\models
     */
    class TaskLogBase extends \yii\mongodb\ActiveRecord {
        /**
         * @inheritdoc
         */
        public static function collectionName()
        {
            return 'tasklogs';
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
        public function getId(){
            return $this->_id;
        }
    }
}
else {
    /**
     * Class TaskLogBase
     * @package common\models
     */
    class TaskLogBase extends \yii\db\ActiveRecord {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return '{{%core_task_logs}}';
        }
    }
}
