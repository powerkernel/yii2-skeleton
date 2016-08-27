<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */
use modernkernel\fontawesome\Icon;
use yii\bootstrap\Html;

/* @var $model common\models\Blog */

?>
<div class="col-sm-4">
    <div class="box box-primary">
        <div class="box-header">
            <h2 class="box-title"><?= $model->title ?></h2>
            <div class="box-tools pull-right">
                <small>29/10/1984</small>
            </div>
        </div>
        <div class="box-body">
            <p><?= $model->desc ?></p>
            <p><?= Html::a(Yii::t('app', 'Read') . ' ' . Icon::widget(['icon' => 'long-arrow-right']), $model->viewUrl, ['class' => 'btn btn-info btn-xs']) ?></p>
        </div>
    </div>
</div>
