<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%core_services}}".
 *
 * @property string $name
 * @property string $title
 * @property string $token
 * @property string $data
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Service extends ActiveRecord
{


    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 20;


    /**
     * get status list
     * @param null $e
     * @return array
     */
    public static function getStatusOption($e = null)
    {
        $option = [
            self::STATUS_ACTIVE => Yii::t('app', 'Active'),
            self::STATUS_INACTIVE => Yii::t('app', 'Inactive'),
        ];
        if (is_array($e))
            foreach ($e as $i)
                unset($option[$i]);
        return $option;
    }

    /**
     * get status text
     * @return string
     */
    public function getStatusText()
    {
        $status = $this->status;
        $list = self::getStatusOption();
        if (!empty($status) && in_array($status, array_keys($list))) {
            return $list[$status];
        }
        return Yii::t('app', 'Unknown');
    }


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
    public function rules()
    {
        return [
            [['name', 'title'], 'required'],
            [['token', 'data'], 'string'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Name'),
            'title' => Yii::t('app', 'Title'),
            'token' => Yii::t('app', 'Token'),
            'data' => Yii::t('app', 'Data'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
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
}
