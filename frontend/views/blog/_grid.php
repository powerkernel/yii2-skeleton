<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */
use modernkernel\fontawesome\Icon;
use yii\bootstrap\Html;

/* @var $model common\models\Blog */
/* @var integer $index */

?>
<div class="col-sm-6">
    <div class="box box-primary">
        <div class="box-header">
            <h2 class="box-title">
                <a href="<?= $model->viewUrl ?>"><?= $model->title ?></a>
            </h2>
        </div>
        <div class="box-body">
            <p><a href="<?= $model->viewUrl ?>"><img src="<?= $model->thumbnail ?>" alt="<?= $model->title ?>" class="img-responsive img-thumbnail"></a></p>
            <p><?= $model->desc ?></p>
            <p><?= Html::a(Yii::t('app', 'Read') . ' ' . Icon::widget(['icon' => 'long-arrow-right']), $model->viewUrl, ['class' => 'btn btn-info btn-xs']) ?></p>
        </div>
        <div class="box-footer">
            <div class="box-tools pull-right">
                <small class="text-muted">
                    <?= Yii::t('app', 'By {AUTHOR}, last updated {DATE}', ['AUTHOR' => $model->author->fullname, 'DATE' => Yii::$app->formatter->asDate($model->updated_at)]) ?>
                </small>
            </div>
        </div>
    </div>
</div>
<?php if (($index + 1) % 2 == 0): ?>
    <div class="clearfix hidden-xs"></div>
<?php endif; ?>
