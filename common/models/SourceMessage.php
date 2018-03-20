<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%core_source_message}}".
 *
 * @property integer $id
 * @property string $category
 * @property string $message
 *
 * @property Message[] $messages
 */
class SourceMessage extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%core_source_message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category', 'message'], 'required'],
            [['message'], 'string'],
            [['category'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'category' => Yii::t('app', 'Category'),
            'message' => Yii::t('app', 'Message'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getI18nMessages()
    {
        return $this->hasMany(Message::class, ['id' => 'id']);
    }

    /**
     * get category list
     * @return array
     */
    public static function getCategoryList(){
        $items=SourceMessage::find()->select(['category'])->distinct()->all();
        return ArrayHelper::map($items, 'category', 'category');
    }

}
