<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2016 Power Kernel
 */

namespace common\models;

use common\behaviors\UTCDateTimeBehavior;
use common\Core;
use DOMDocument;
use MongoDB\BSON\UTCDateTime;
use Yii;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

/**
 * @property \MongoDB\BSON\ObjectID|string $id
 * @property string $slug
 * @property string $language
 * @property string $title
 * @property string $desc
 * @property string $content
 * @property string $tags
 * @property string $thumbnail
 * @property string $thumbnail_square
 * @property string $image_object
 * @property integer|string $created_by
 * @property integer $views
 * @property string $status
 * @property \MongoDB\BSON\UTCDateTime $created_at
 * @property \MongoDB\BSON\UTCDateTime $updated_at
 * @property \MongoDB\BSON\UTCDateTime $published_at
 *
 * @property Account $author
 * @property string $viewUrl
 * @property string $updateUrl
 */
class Blog extends \yii\mongodb\ActiveRecord
{
    const STATUS_PUBLISHED = 'STATUS_PUBLISHED';//10;
    const STATUS_DRAFT = 'STATUS_DRAFT';//20;

    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'core_blog';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'slug',
            'language',
            'title',
            'desc',
            'content',
            'tags',
            'thumbnail',
            'thumbnail_square',
            'image_object',
            'created_by',
            'views',
            'status',
            'created_at',
            'updated_at',
            'published_at',
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

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            UTCDateTimeBehavior::class,
        ];
    }

    /**
     * @return int timestamp
     */
    public function getUpdatedAt()
    {
        return $this->updated_at->toDateTime()->format('U');
    }

    /**
     * @return int timestamp
     */
    public function getCreatedAt()
    {
        return $this->created_at->toDateTime()->format('U');
    }

    /**
     * @return int timestamp
     */
    public function getPublishedAt()
    {
        return $this->published_at->toDateTime()->format('U');
    }

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
     * get the status text
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
        if ($status == self::STATUS_PUBLISHED) {
            return '<span class="label label-success">' . $this->statusText . '</span>';
        }
        if ($status == self::STATUS_DRAFT) {
            return '<span class="label label-default">' . $this->statusText . '</span>';
        }
        return $this->statusText;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['slug', 'language', 'title', 'desc', 'content', 'tags', 'thumbnail', 'thumbnail_square'], 'required'],
            [['slug'], 'match', 'pattern' => '/^[a-z0-9-]+$/'],
            [['slug'], 'unique'],
            [['content'], 'string'],
            [['views'], 'integer'],

            [['language'], 'string', 'max' => 5],
            [['title', 'slug', 'desc'], 'string', 'max' => 110],
            [['tags', 'thumbnail', 'thumbnail_square', 'status'], 'string', 'max' => 255],

            [['thumbnail', 'thumbnail_square'], 'url'],
            [['image_object'], 'string'],

            [['created_at', 'updated_at', 'published_at'], 'yii\mongodb\validators\MongoDateValidator'],
            [['created_by'], 'string'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Account::class, 'targetAttribute' => ['created_by' => '_id']],


        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'slug' => Yii::t('app', 'Slug'),
            'language' => Yii::t('app', 'Language'),
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
     * @return \yii\db\ActiveQueryInterface
     */
    public function getAuthor()
    {
        return $this->hasOne(Account::class, ['_id' => 'created_by']);
    }


    /**
     * get most viewed blog
     * @param int $limit
     * @return Blog[]
     */
    public static function mostViewed($limit = 10)
    {
        return Blog::find()->where(['status' => Blog::STATUS_PUBLISHED])->orderBy(['views' => SORT_DESC])->limit($limit)->all();
    }

    /**
     * get latest blog
     * @param int $limit
     * @return Blog[]
     */
    public static function latest($limit = 10)
    {
        return Blog::find()->where(['status' => Blog::STATUS_PUBLISHED])->orderBy(['published_at' => SORT_DESC])->limit($limit)->all();
    }

    /**
     * get random blog
     * @param int $limit
     * @return Blog[]
     */
    public static function random($limit = 10)
    {
        return Blog::find()->where(['status' => Blog::STATUS_PUBLISHED])->orderBy('rand()')->limit($limit)->all();
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
            $this->published_at = new UTCDateTime();
        }

        /* clean html */
        $config = [
            'HTML.MaxImgLength' => null,
            'CSS.MaxImgLength' => null,
            'HTML.Trusted' => true,
            'Filter.YouTube' => true,
        ];
        $this->content = HtmlPurifier::process($this->content, $config);

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
     * @param bool $absolute
     * @return string
     */
    public function getViewUrl($absolute = false)
    {
        $act = 'createUrl';
        if ($absolute) {
            $act = 'createAbsoluteUrl';
        }
        return Yii::$app->urlManager->$act(['/blog/view', 'name' => $this->slug]);
    }


    /**
     * get image object info for SEO
     * @return array
     */
    public function getImageObject()
    {
        //$key = 'cache_blog_image_object_' . $this->id;
        //$img = Yii::$app->cache->get($key);

        //if ($img === false) {
        $flag = false;
        $doc = new DOMDocument();
        $doc->loadHTML($this->content);
        $tags = $doc->getElementsByTagName('img');
        $img = ['url' => '', 'width' => '', 'height' => ''];
        foreach ($tags as $i => $tag) {
            $img['url'] = $tag->getAttribute('src');
            $img['width'] = $tag->getAttribute('width');
            $img['height'] = $tag->getAttribute('height');
            $flag = true;
            break;
        }
        if ($flag) {
            /* cache */
            //$sql = (new Query())->select('updated_at')->from(Blog::tableName())->where(['id' => $this->id])->createCommand()->rawSql;
            //$dependency = new DbDependency();
            //$dependency->sql = $sql;
            //Yii::$app->cache->set($key, $img, 0, $dependency);
        } else {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Missing image in blog post.'));
        }

        //}

        return $img;
    }

    /**
     * get all images in blog's content
     * @return array
     */
    public function getImages()
    {
        $doc = new DOMDocument();
        $doc->loadHTML($this->content);
        $tags = $doc->getElementsByTagName('img');
        $imgs = [];
        foreach ($tags as $i => $tag) {
            $imgs[] = [
                'src' => $tag->getAttribute('src'),
                'width' => $tag->getAttribute('width'),
                'height' => $tag->getAttribute('height'),
                'alt' => $tag->getAttribute('alt')
            ];
        }
        return $imgs;
    }


    /**
     * update views
     */
    public function updateViews()
    {
        $key = 'blog_viewed' . $this->id;
        $viewed = Yii::$app->session->get($key);
        if (empty($viewed)) {
            Yii::$app->session->set($key, true);
            $this->updateCounters(['views' => 1]);
        }

    }

    /**
     * AMP Content
     * @return mixed|string
     */
    public function getAmpContent()
    {
        /* youtube ids */
        $ids = $this->getEmbedYoutubeID();
        $html = str_ireplace(
            ['<img', '<video', '/video>', '<audio', '/audio>'],
            ['<amp-img layout="responsive"', '<amp-video', '/amp-video>', '<amp-audio', '/amp-audio>'],
            $this->content
        );
        # Add closing tags to amp-img custom element
        $html = preg_replace('/<amp-img(.*?)>/', '<amp-img$1></amp-img>', $html);
        # Whitelist of HTML tags allowed by AMP
        $html = strip_tags($html, '<h1><h2><h3><h4><h5><h6><a><p><ul><ol><li><blockquote><q><cite><ins><del><strong><em><code><pre><svg><table><thead><tbody><tfoot><th><tr><td><dl><dt><dd><article><section><header><footer><aside><figure><time><abbr><div><span><hr><small><br><amp-img><amp-audio><amp-video><amp-ad><amp-anim><amp-carousel><amp-fit-rext><amp-image-lightbox><amp-instagram><amp-lightbox><amp-twitter><amp-youtube>');
        # re-add youtube
        $youtube = '';
        foreach ($ids as $id) {
            $youtube .= Html::tag('amp-youtube', '', ['data-videoid' => $id, 'width' => 640, 'height' => 360, 'layout' => 'responsive']);
        }
        return $youtube . $html;
    }

    /**
     * get youtube ids
     * @return array
     */
    public function getEmbedYoutubeID()
    {
        $doc = new DOMDocument();
        $doc->loadHTML($this->content);
        $tags = $doc->getElementsByTagName('iframe');
        $ids = [];
        foreach ($tags as $i => $tag) {
            $src = $tag->getAttribute('src');
            if (preg_match('/embed\/(\w+)/i', $src, $matches)) {
                $ids[] = $matches[1];
            }
        }
        return $ids;
    }


}
