<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2016 Power Kernel
 */
use powerkernel\fontawesome\Icon;
use yii\bootstrap\Html;

/* @var $model common\models\Blog */
/* @var integer $index */

?>
<div class="col-sm-6">
    <div class="box box-primary">
        <div class="box-header">
            <h2 class="box-title">
                <a href="<?= $model->viewUrl ?>" data-pjax="0"><?= $model->title ?></a>
            </h2>
        </div>
        <div class="box-body">
            <p><a href="<?= $model->viewUrl ?>" data-pjax="0"><img src="<?= $model->thumbnail ?>" alt="<?= $model->title ?>" class="img-responsive img-thumbnail"></a></p>
            <p><?= $model->desc ?></p>
            <p><?= Html::a(Yii::t('app', 'Read') . ' ' . Icon::widget(['icon' => 'long-arrow-right']), $model->viewUrl, ['class' => 'btn btn-info btn-xs', 'data-pjax'=>0]) ?></p>
        </div>
        <div class="box-footer">
            <div class="box-tools pull-right">
                <small class="text-muted">
                    <?= Yii::t('app', 'By {AUTHOR}, last updated {DATE}', ['AUTHOR' => $model->author->fullname, 'DATE' => Yii::$app->formatter->asDate($model->updatedAt)]) ?>
                </small>
            </div>
        </div>
    </div>
</div>
<?php if (($index + 1) % 2 == 0): ?>
    <div class="clearfix hidden-xs"></div>
<?php endif; ?>
