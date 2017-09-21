<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2016 Power Kernel
 */

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Banner */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Banners'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

/* misc */
//$js=file_get_contents(__DIR__.'/index.min.js');
//$this->registerJs($js);
//$css=file_get_contents(__DIR__.'/index.css');
//$this->registerCss($css);
?>
<div class="banner-view">
    <div class="box box-info">
        <div class="box-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="table-responsive">
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                //'id',
                                'title',
                                'lang',
                                'text_content:html',
                                'link_url:url',
                                'link_option',
                                ['attribute' => 'status', 'value' => $model->statusColorText, 'format'=>'raw'],
                                'createdAt:dateTime',
                                'updatedAt:dateTime',
                            ],
                        ]) ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <?= Html::img($model->banner_url, ['alt' => $model->title, 'class'=>'img-responsive img-thumbnail']) ?>
                </div>
            </div>

            <p>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => Yii::$app->params['mongodb']['banner']?(string)$model->id:$model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => Yii::$app->params['mongodb']['banner']?(string)$model->id:$model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            </p>
        </div>
    </div>
</div>
