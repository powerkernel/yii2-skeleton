<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

namespace common\models;

use Yii;

/**
 * Service model class.
 *
 * @property integer|\MongoDB\BSON\ObjectID|string $id
 * @property string $name
 * @property string $title
 * @property string $token
 * @property string $data
 * @property string $status
 * @property integer|\MongoDB\BSON\UTCDateTime $created_at
 * @property integer|\MongoDB\BSON\UTCDateTime $updated_at
 */
class Service extends ServiceBase
{


    const STATUS_ACTIVE = 'STATUS_ACTIVE';//10;
    const STATUS_INACTIVE = 'STATUS_INACTIVE';//20;


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
    public function rules()
    {
        if (is_a($this, '\yii\mongodb\ActiveRecord')) {
            $date = [
                [['created_at', 'updated_at'], 'yii\mongodb\validators\MongoDateValidator']
            ];
        } else {
            $date = [
                [['created_at', 'updated_at'], 'integer']
            ];
        }
        $default = [
            [['name', 'title'], 'required'],
            [['token', 'data'], 'string'],
            [['name', 'title', 'status'], 'string', 'max' => 255],
        ];
        return array_merge($default, $date);
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


}
