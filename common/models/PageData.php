<?php

namespace common\models;

use DOMDocument;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\caching\DbDependency;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\HtmlPurifier;

/**
 * This is the model class for table "{{%core_page_data}}".
 *
 * @property integer $id_page
 * @property string $language
 * @property string $title
 * @property string $description
 * @property string $content
 * @property string $keywords
 * @property string $thumbnail
 * @property integer $status
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Page $page
 */
class PageData extends ActiveRecord
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
    * @param null $status
    * @return string
    */
    public function getStatusText($status = null)
    {
        if ($status === null)
        $status = $this->status;
        switch ($status) {
            case self::STATUS_ACTIVE:
                return Yii::t('app', 'Active');
                break;
            case self::STATUS_INACTIVE:
                return Yii::t('app', 'Inactive');
                break;
            default:
                return Yii::t('app', 'Unknown');
                break;
        }
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%core_page_data}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_page', 'language', 'title', 'description', 'content', 'keywords'], 'required', 'on'=>['update', 'create']],
            [['status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['content', 'thumbnail'], 'string'],
            [['thumbnail'], 'url'],
            [['language'], 'string', 'max' => 5],
            [['title', 'description', 'keywords'], 'string', 'max' => 110],
            [['id_page'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_page' => Yii::t('app', 'ID'),
            'language' => Yii::t('app', 'Language'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'content' => Yii::t('app', 'Content'),
            'keywords' => Yii::t('app', 'Keywords'),
            'thumbnail' => Yii::t('app', 'Thumbnail'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPage()
    {
        return $this->hasOne(Page::className(), ['id' => 'id_page']);
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

    /**
     * @inheritdoc
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        /* text */
        $this->title=ucwords($this->title);
        $this->description=ucfirst($this->description);
        $this->content=HtmlPurifier::process($this->content);

        if(!empty(Yii::$app->user)){
            if(empty($this->created_by)){
                $this->created_by=Yii::$app->user->id;
            }
            $this->updated_by=Yii::$app->user->id;
        }

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        parent::afterDelete(); // TODO: Change the autogenerated stub
        if(!$this->page->data){
            $this->page->delete();
        }
    }

    /**
     * @inheritdoc
     * @return bool
     */
    public function beforeDelete()
    {
        $corePages=[
            'privacy',
            'terms'
        ];

        if(in_array($this->id_page, $corePages)){
            Yii::$app->session->setFlash('error', Yii::t('app', 'Sorry! This page can not be deleted.'));
            return false;
        }
        return parent::beforeDelete(); // TODO: Change the autogenerated stub
    }

    /**
     * view url
     * @param bool $absolute
     * @return string
     */
    public function getViewUrl($absolute=false){
        $act='createUrl';
        if($absolute){
            $act='createAbsoluteUrl';
        }
        return Yii::$app->urlManager->$act(['/site/page', 'id'=>$this->id_page, 'lang'=>$this->language]);
    }

    /**
     * @return array|bool|mixed
     */
    public function getImageObject()
    {
        $key = 'cache_page_image_object_' . $this->id_page.'-'.$this->language;
        $img = Yii::$app->cache->get($key);

        if ($img === false) {
            $doc = new DOMDocument();
            $doc->loadHTML($this->content);
            $tags = $doc->getElementsByTagName('img');
            $img = [];
            foreach ($tags as $i => $tag) {
                $img['url'] = $tag->getAttribute('src');
                $img['width'] = $tag->getAttribute('width');
                $img['height'] = $tag->getAttribute('height');
                break;
            }
            /* cache */
            $sql=(new Query())->select('updated_at')->from(PageData::tableName())->where(['id_page'=>$this->id_page, 'language'=>$this->language])->createCommand()->rawSql;
            $dependency = new DbDependency();
            $dependency->sql=$sql;
            Yii::$app->cache->set($key, $img, 0, $dependency);
        }

        return $img;
    }

}
