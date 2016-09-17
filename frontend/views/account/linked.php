<?php

/* @var $this yii\web\View */
/* @var $model \common\models\Account */
/* @var $auths[] */

use modernkernel\bootstrapsocial\Button;
use modernkernel\fontawesome\Icon;
use yii\bootstrap\Html;


$this->title = Yii::t('app', 'Linked Accounts');

?>
<div class="account-index">
    <div class="box box-default">
        <div class="box-header with-border">
            <h1 class="box-title"><?= $this->title ?></h1>

        </div>
        <div class="box-body">
            <h4 class="no-margin"><?= Yii::t('app', 'Available Services') ?></h4>
            <p><?= Yii::t('app', 'Add your accounts from other websites here and use them for quick login.') ?></p>

            <div class="table-responsive">
                <table class="table">
                    <thead class="hidden">
                    <tr>
                        <th><?= Yii::t('app', 'Website') ?></th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(Yii::$app->authClientCollection->hasClient('facebook')):?>
                    <tr>
                        <td style="max-width: 50px"><?= Button::widget(['button' => 'facebook btn-xs', 'iconOnly'=>false, 'label'=>'Facebook']) ?></td>
                        <td>
                            <?php if(in_array('facebook', array_keys($auths))):?>
                                <?= Yii::t('app', 'Account linked') ?>
                            <?php else:?>
                                <?= Yii::t('app', 'Account not linked') ?>
                            <?php endif;?>
                        </td>
                        <td class="text-right">
                            <?php if(in_array('facebook', array_keys($auths))):?>
                                <?= Html::a(Yii::t('app', Icon::widget(['icon'=>'remove']).' '.'Remove account'), Yii::$app->urlManager->createUrl(['/account/linked', 'remove'=>$auths['facebook']]), ['class'=>'btn btn-warning btn-xs'])  ?>
                            <?php else:?>
                                <?= Html::a(Yii::t('app', Icon::widget(['icon'=>'plus']).' '.'Add account'), Yii::$app->urlManager->createUrl(['/account/auth', 'authclient'=>'facebook']), ['class'=>'btn btn-success btn-xs'])  ?>
                            <?php endif;?>
                        </td>
                    </tr>
                    <?php endif;?>
                    <?php if(Yii::$app->authClientCollection->hasClient('google')):?>
                    <tr>
                        <td style="max-width: 50px"><?= Button::widget(['button' => 'google btn-xs', 'iconOnly'=>false, 'label'=>'Google']) ?></td>
                        <td>
                            <?php if(in_array('google', array_keys($auths))):?>
                                <?= Yii::t('app', 'Account linked') ?>
                            <?php else:?>
                                <?= Yii::t('app', 'Account not linked') ?>
                            <?php endif;?>
                        </td>
                        <td class="text-right">
                            <?php if(in_array('google', array_keys($auths))):?>
                                <?= Html::a(Yii::t('app', Icon::widget(['icon'=>'remove']).' '.'Remove account'), Yii::$app->urlManager->createUrl(['/account/linked', 'remove'=>$auths['google']]), ['class'=>'btn btn-warning btn-xs'])  ?>
                            <?php else:?>
                                <?= Html::a(Yii::t('app', Icon::widget(['icon'=>'plus']).' '.'Add account'), Yii::$app->urlManager->createUrl(['/account/auth', 'authclient'=>'google']), ['class'=>'btn btn-success btn-xs'])  ?>
                            <?php endif;?>
                        </td>
                    </tr>
                    <?php endif;?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

