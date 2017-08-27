<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2017 Modern Kernel
 */

namespace common\models;

use Yii;

/**
 * Banner model class.
 *
 * @property integer|string $id
 * @property string $lang
 * @property string $title
 * @property string $text_content
 * @property string $text_style
 * @property string $banner_url
 * @property string $link_url
 * @property string $link_option
 * @property integer $status
 * @property integer|\MongoDB\BSON\UTCDateTime $created_at
 * @property integer|\MongoDB\BSON\UTCDateTime $updated_at
 */
class Banner extends BannerBase
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
     * color status text
     * @return mixed|string
     */
    public function getStatusColorText()
    {
        $status = $this->status;
        if ($status == self::STATUS_ACTIVE) {
            return '<span class="label label-success">' . $this->statusText . '</span>';
        }
        if ($status == self::STATUS_INACTIVE) {
            return '<span class="label label-default">' . $this->statusText . '</span>';
        }
        return $this->statusText;
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
            [['lang', 'text_content', 'text_style', 'link_url'], 'default', 'value' => null],
            [['title', 'banner_url', 'status'], 'required'],
            [['text_content', 'text_style', 'lang'], 'string'],
            [['status'], 'integer'],
            [['title', 'link_url', 'link_option'], 'string', 'max' => 255],
            [['banner_url', 'link_url'], 'url'],
        ];
        return array_merge($default, $date);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'lang' => Yii::t('app', 'Language'),
            'title' => Yii::t('app', 'Title'),
            'text_content' => Yii::t('app', 'Text'),
            'text_style' => Yii::t('app', 'Text Style'),
            'banner_url' => Yii::t('app', 'Banner URL'),
            'link_url' => Yii::t('app', 'Click URL'),
            'link_option' => Yii::t('app', 'Link Option'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

}
