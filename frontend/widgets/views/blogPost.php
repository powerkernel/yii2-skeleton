<?php
/* @var \common\models\Blog[] $models */
/* @var string $title */
?>
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $title ?></h3>
    </div>

    <div class="box-body no-no-padding">
        <ul class="products-list product-list-in-box">
            <?php foreach ($models as $model):?>
                <li class="item">
                    <div class="product-img">
                        <img src="<?= $model->thumbnail ?>" class="" style="width: 50px; height: auto" alt="<?= $model->title ?>" />
                    </div>
                    <div class="product-info">
                        <a href="<?= $model->viewUrl ?>" class="product-title"><?= $model->title ?></a>
                        <span class="product-description">
                          <?= $model->desc ?>
                        </span>
                    </div>
                </li>
            <?php endforeach;?>
        </ul>
    </div>
</div>