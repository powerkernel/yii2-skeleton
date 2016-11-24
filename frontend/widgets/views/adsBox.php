<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */
use frontend\widgets\Adsense;

?>
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title"><?= Yii::t('app', 'Advertisement') ?></h3>
    </div>

    <div class="box-body no-no-padding">
        <?= Adsense::widget() ?>
        <?= \frontend\widgets\AmzAds::widget() ?>
    </div>
</div>
