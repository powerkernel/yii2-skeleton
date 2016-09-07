<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

namespace common\models;

use common\Core;
use DOMDocument;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\caching\DbDependency;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\HtmlPurifier;

/**
 * This is the model class for table "{{%core_blog}}".
 *
 * @property integer $id
 * @property string $slug
 * @property string $title
 * @property string $desc
 * @property string $content
 * @property string $tags
 * @property string $thumbnail
 * @property string $thumbnail_square
 * @property string $image_object
 * @property integer $created_by
 * @property integer $views
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $published_at
 *
 * @property Account $author
 * @property string $viewUrl
 * @property string $viewAbsoluteUrl
 * @property string $updateUrl
 */
class Blog extends ActiveRecord
{


    const STATUS_PUBLISHED = 10;
    const STATUS_DRAFT = 20;


    /**
     * get status list
     * @param null $e
     * @return array
     */
    public static function getStatusOption($e = null)
    {
        $option = [
            self::STATUS_PUBLISHED => Yii::t('app', 'Published'),
            self::STATUS_DRAFT => Yii::t('app', 'Draft'),
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
        return '{{%core_blog}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['slug', 'title', 'desc', 'content', 'tags', 'thumbnail', 'thumbnail_square'], 'required'],
            [['slug'], 'match', 'pattern' => '/^[a-z0-9-]+$/'],
            [['slug'], 'unique'],
            [['content'], 'string'],
            [['created_by', 'views', 'status', 'created_at', 'updated_at', 'published_at'], 'integer'],

            [['title', 'slug'], 'string', 'max' => 110],
            [['desc', 'tags', 'thumbnail', 'thumbnail_square'], 'string', 'max' => 255],

            [['thumbnail', 'thumbnail_square'], 'url'],
            [['image_object'], 'string'],

            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Account::className(), 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'slug' => Yii::t('app', 'Slug'),
            'title' => Yii::t('app', 'Title'),
            'desc' => Yii::t('app', 'Desc'),
            'content' => Yii::t('app', 'Content'),
            'tags' => Yii::t('app', 'Tags'),
            'thumbnail' => Yii::t('app', 'Thumbnail'),
            'thumbnail_square' => Yii::t('app', 'Square Thumbnail'),
            'image_object' => Yii::t('app', 'Image Object'),
            'created_by' => Yii::t('app', 'Author'),
            'views' => Yii::t('app', 'Views'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'published_at' => Yii::t('app', 'Published At'),

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Account::className(), ['id' => 'created_by']);
    }

    /**
     * @inheritdoc
     * @return BlogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BlogQuery(get_called_class());
    }

    /**
     * get most viewed blog
     * @param int $limit
     * @return Blog[]
     */
    public static function mostViewed($limit=10){
        return Blog::find()->orderBy(['views'=>SORT_DESC])->limit($limit)->all();
    }

    /**
     * get latest blog
     * @param int $limit
     * @return Blog[]
     */
    public static function latest($limit=10){
        return Blog::find()->orderBy(['published_at'=>SORT_DESC])->limit($limit)->all();
    }

    /**
     * get random blog
     * @param int $limit
     * @return Blog[]
     */
    public static function random($limit=10){
        return Blog::find()->orderBy('rand()')->limit($limit)->all();
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
     */
    public function beforeSave($insert)
    {
        /* author */
        if ($insert) {
            $this->created_by = Yii::$app->user->id;
        }

        /* slug */
        if (empty($this->slug)) {
            $this->slug = Core::generateSeoName($this->title);
        }

        /* published_at */
        if ($this->status == Blog::STATUS_PUBLISHED && empty($this->published_at)) {
            $this->published_at = time();
        }

        /* clean html */
        $this->content = HtmlPurifier::process($this->content);

        /* done */
        return parent::beforeSave($insert);

    }

    /**
     * get update url
     * @return string
     */
    public function getUpdateUrl()
    {
        return Yii::$app->urlManager->createUrl(['/blog/update', 'id' => $this->id]);
    }

    /**
     * get view url
     * @return string
     */
    public function getViewUrl()
    {
        return Yii::$app->urlManager->createUrl(['/blog/view', 'name' => $this->slug]);
    }

    /**
     * get absolute view url
     * @return string
     */
    public function getViewAbsoluteUrl()
    {
        return Yii::$app->urlManager->createAbsoluteUrl(['/blog/view', 'name' => $this->slug]);
    }

    /**
     * get image object info for SEO
     * @return array
     */
    public function getImageObject()
    {
        $key = 'cache_blog_image_object_' . $this->id;
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
            $sql=(new Query())->select('updated_at')->from(Blog::tableName())->where(['id'=>$this->id])->createCommand()->rawSql;
            $dependency = new DbDependency();
            $dependency->sql=$sql;
            Yii::$app->cache->set($key, $img, 0, $dependency);
        }

        return $img;
    }

    /**
     * update views
     */
    public function updateViews(){
        $key='blog_viewed'.$this->id;
        $viewed=Yii::$app->session->get($key);
        if(empty($viewed)){
            Yii::$app->session->set($key, true);
            $this->updateCounters(['views' => 1]);
        }

    }
}
