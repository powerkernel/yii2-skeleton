<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2016 Power Kernel
 */
use yii\helpers\Html;


/* @var $this yii\web\View */
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-search">
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-7">
                    <gcse:searchresults-only linkTarget="_self"></gcse:searchresults-only>
                </div>
                <div class="col-sm-5">
                    <?= \frontend\widgets\AmzSearchAds::widget() ?>
                </div>
            </div>
        </div>
    </div>
</div>