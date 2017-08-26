<?php

namespace common\models;

use common\Core;
use Yii;

/**
 * This is the model class for table "{{%core_setting}}".
 *
 * @property string $key
 * @property string $value
 * @property string $title
 * @property string $description
 * @property string $group
 * @property string $type
 * @property string $data
 * @property string $default
 * @property string $rules
 * @property string $key_order
 */
class Setting extends SettingBase
{


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'title', 'description', 'group', 'type', 'data', 'rules'], 'required'],
            [['value', 'default'], 'string'],
            [['key', 'title', 'description', 'group'], 'string', 'max' => 255],
            [['key_order'], 'safe']
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
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'group' => Yii::t('app', 'Group'),
        ];
    }


    /**
     * get setting value
     * @param $key
     * @return string
     */
    public static function getValue($key){
        $setting=self::find()->where(['key'=>$key])->one();
        if($setting){
            return $setting->value;
        }
        return null;
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
        if($type=='{LOCALE}'){
            return Core::getLocaleList();
        }
        return [];
    }
}
