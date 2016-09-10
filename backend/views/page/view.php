<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PageData */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

//$js=file_get_contents(__DIR__.'/view.min.js');
//$this->registerJs($js);
//$css=file_get_contents(__DIR__.'/view.css');
//$this->registerJs($css);
?>
<div class="page-data-view">
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">
            <?php if ($model->page->show_description): ?>
                <div><p><?= Html::encode($model->description) ?></p></div>
            <?php endif; ?>
            <div><?= $model->content ?></div>
        </div>
        <?php if ($model->page->show_update_date): ?>
            <div class="box-footer">
                <div class="pull-right font-light text-sm">
                    <?= Yii::t('app', 'Last updated: {DATE}', ['DATE' => Yii::$app->formatter->asDate($model->updated_at)]) ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id_page' => $model->id_page, 'language' => $model->language], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id_page' => $model->id_page, 'language' => $model->language], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this page?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
</div>