<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2016 Power Kernel
 */
use frontend\widgets\Adsense;

?>
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title"><?= Yii::t('app', 'Advertisement') ?></h3>
    </div>
    <div class="box-body no-no-padding">
        <?php //Adsense::widget() ?>
        <?= \frontend\widgets\AmzAds::widget() ?>
    </div>
</div>
