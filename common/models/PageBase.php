<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */


namespace common\models;


use Yii;


if (Yii::$app->params['mongodb']['page']) {
    /**
     * Class PageActiveRecord
     * @package common\models
     */
    class PageActiveRecord extends \yii\mongodb\ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function collectionName()
        {
            return 'page_id';
        }

        /**
         * @return array
         */
        public function attributes()
        {
            return [
                '_id',
                'slug',
                'show_description',
                'show_update_date'
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
     * Class PageActiveRecord
     * @package common\models
     */
    class PageActiveRecord extends \yii\db\ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return '{{%core_page_id}}';
        }


    }
}

/**
 * Class PageBase
 * @package common\models
 */
class PageBase extends PageActiveRecord
{
}