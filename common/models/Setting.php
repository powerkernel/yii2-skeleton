<?php

namespace common\models;

use common\Core;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%core_setting}}".
 *
 * @property string $key
 * @property string $value
 * @property string $description
 * @property string $group
 * @property string $type
 * @property string $data
 * @property string $default
 * @property string $rules
 */
class Setting extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%core_setting}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'description', 'group', 'type', 'data', 'rules'], 'required'],
            [['value', 'default'], 'string'],
            [['key', 'description', 'group'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'key' => Yii::t('app', 'Key'),
            'value' => Yii::t('app', 'Value'),
            'description' => Yii::t('app', 'Description'),
            'group' => Yii::t('app', 'Group'),
        ];
    }

    /**
     * @inheritdoc
     * @return SettingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SettingQuery(get_called_class());
    }

    /**
     * get setting value
     * @param $key
     * @return string
     */
    public static function getValue($key){
        return self::findOne($key)->value;
    }


    /**
     * get data for dropDownList in update setting page
     * @param $type
     * @return array
     */
    public static function getListData($type){
        if($type=='{TIMEZONE}'){
            return Core::getTimezoneList();
        }
        if($type=='{LANGUAGE}'){
            return ['en-US'=>'en-US', 'vi-VN'=>'vi-VN'];
        }
        return [];
    }
}
