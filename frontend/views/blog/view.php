<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

use common\models\Blog;
use common\widgets\Disqus;
use frontend\widgets\Adsense;
use frontend\widgets\LikeButton;
use frontend\widgets\PlusOneButton;


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

$this->params['subtitle'] = Yii::$app->formatter->asDate($model->updated_at);

/* misc */
$js = file_get_contents(__DIR__ . '/view.min.js');
$this->registerJs($js);
$css = file_get_contents(__DIR__ . '/view.css');
$this->registerCss($css);

?>
<div class="blog-view">
    <div class="row">
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-body blog-body">
                    <div><?= $model->content ?></div>
                    <?php if(Yii::$app->user->can('admin')):?>
                    <div class="well well-sm">
                        <a target="_blank" class="btn btn-xs bg-purple" href="https://developers.facebook.com/tools/debug/og/object/?q=<?= $model->getViewUrl(true)?>">Open Graph Object Debugger</a>
                        <a target="_blank" class="btn btn-xs bg-purple" href="https://search.google.com/structured-data/testing-tool#url=<?= $model->getViewUrl(true)?>">Structured Data Testing</a>
                        <a target="_blank" class="btn btn-xs bg-purple" href="https://cards-dev.twitter.com/validator">Twitter Card validator</a>
                        <a class="btn btn-xs btn-primary" href="<?= Yii::$app->urlManager->createUrl(['/blog/update', 'id'=>$model->id]) ?>"><?= Yii::t('app', 'Edit') ?></a>
                    </div>
                    <?php endif;?>
                    <?= Adsense::widget() ?>
                </div>
                <div class="box-footer">
                    <div class="pull-left">
                        <?= LikeButton::widget([
                            'href'=>$model->getViewUrl(true),
                            'layout'=>'button_count',
                        ]) ?>

                        <?=
                        PlusOneButton::widget([
                            'href'=>$model->getViewUrl(true),
                            'size'=>'medium'
                        ])
                        ?>

                    </div>
                    <div class="pull-right text-right">
                        <small class="text-muted">
                            <?= Yii::t(
                                'app',
                                'By {AUTHOR}, last updated {DATE}',
                                [
                                    'AUTHOR' => $model->author->fullname,
                                    'DATE' => Yii::$app->formatter->asDate($model->updated_at)
                                ])
                            ?>
                        </small>
                    </div>
                </div>
            </div>
            <?= Disqus::widget([
                'pageUrl' => $model->getViewUrl(true),
                'pageIdentifier' => $model->slug
            ]) ?>

        </div>
        <div class="col-md-4">
            <?= \frontend\widgets\BlogPost::widget(['type' => 'mostViewed']) ?>
            <?= \frontend\widgets\AdsBox::widget() ?>
            <?= \frontend\widgets\BlogPost::widget(['type' => 'latest']) ?>
        </div>
    </div>

</div>


