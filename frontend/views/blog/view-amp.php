<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2016 Power Kernel
 */
use frontend\assets\AMPAsset;
use frontend\assets\AMPYouTubeAsset;


/* @var $this yii\web\View */
/* @var $model common\models\Blog */


//$this->registerMetaTag(['name' => 'robots', 'content' => 'noindex, nofollow, nosnippet, noodp, noarchive, noimageindex']);

/* Facebook */
//$this->registerMetaTag(['property' => 'og:title', 'content' => $this->title]);
//$this->registerMetaTag(['property' => 'og:description', 'content' => $description]);
//$this->registerMetaTag(['property' => 'og:type', 'content' => '']);
//$this->registerMetaTag(['property' => 'og:image', 'content' => '']); // best 1200 x 630
//$this->registerMetaTag(['property' => 'og:url', 'content' => '']);
//$this->registerMetaTag(['property' => 'fb:app_id', 'content' => '']);
//$this->registerMetaTag(['property' => 'fb:admins', 'content' => '']);

/* Twitter */
//$this->registerMetaTag(['name'=>'twitter:title', 'content'=>$this->title]);
//$this->registerMetaTag(['name'=>'twitter:description', 'content'=>$description]);
//$this->registerMetaTag(['name'=>'twitter:card', 'content'=>'summary']);
//$this->registerMetaTag(['name'=>'twitter:site', 'content'=>'']);
//$this->registerMetaTag(['name'=>'twitter:image', 'content'=>'']);
//$this->registerMetaTag(['name'=>'twitter:data1', 'content'=>'']);
//$this->registerMetaTag(['name'=>'twitter:label1', 'content'=>'']);
//$this->registerMetaTag(['name'=>'twitter:data2', 'content'=>'']);
//$this->registerMetaTag(['name'=>'twitter:label2', 'content'=>'']);

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Blog'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;

$this->params['subtitle'] = Yii::$app->formatter->asDate($model->publishedAt);

/* misc */
//$js = file_get_contents(__DIR__ . '/view.min.js');
//$this->registerJs($js);
//$css = file_get_contents(__DIR__ . '/view.css');
//$this->registerCss($css);

if(!empty($model->getEmbedYoutubeID())){
    AMPYouTubeAsset::register($this);
}
AMPAsset::register($this);

?>
<div class="blog-view">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body blog-body">
                    <div class="blog-content"><?= $model->getAmpContent() ?></div>
                </div>
                <div class="box-footer">
                    <div class="pull-left">


                    </div>
                    <div class="pull-right text-right">
                        <small class="text-muted">
                            <?= Yii::t(
                                'app',
                                'By {AUTHOR}, last updated {DATE}',
                                [
                                    'AUTHOR' => $model->author->fullname,
                                    'DATE' => Yii::$app->formatter->asDate($model->updatedAt)
                                ])
                            ?>
                        </small>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


