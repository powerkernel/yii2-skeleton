<?php

namespace common\models;

use Yii;

/**
 * This is the model class for Auth.
 *
 * @property integer|\MongoDB\BSON\ObjectID|string $id
 * @property integer|string $user_id
 * @property string $source
 * @property string $source_id
 *
 * @property Account $user
 */
class Auth extends AuthBase
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'source', 'source_id'], 'required'],
            //[['user_id'], 'integer'],
            [['source', 'source_id'], 'string', 'max' => 255],
            //[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Account::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            //'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'source' => Yii::t('app', 'Source'),
            'source_id' => Yii::t('app', 'Source ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        if (Yii::$app->params['mongodb']['account']) {
            return $this->hasOne(Account::className(), ['_id' => 'user_id']);
        } else {
            return $this->hasOne(Account::className(), ['id' => 'user_id']);
        }

    }
}
