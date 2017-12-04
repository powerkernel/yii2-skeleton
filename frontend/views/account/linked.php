<?php

/* @var $this yii\web\View */
/* @var $model \common\models\Account */

/* @var $auths [] */

use powerkernel\bootstrapsocial\Button;
use powerkernel\fontawesome\Icon;
use yii\bootstrap\Html;


$this->title = Yii::t('app', 'Linked Accounts');
$keywords = Yii::t('app', 'linked account, facebook, google');
$description = Yii::t('app', 'Add your accounts from other websites here and use them for quick login');


?>
<div class="account-index">
    <div class="box box-info">
        <div class="box-header with-border">
            <h1 class="box-title"><?= $this->title ?></h1>
        </div>
        <div class="box-body">
            <p><?= Yii::t('app', 'Add your accounts from other websites here and use them for quick login') ?></p>
            <div class="row">
                <?php if (Yii::$app->authClientCollection->hasClient('facebook')): ?>
                    <div class="col-sm-3">
                        <div class="well well-sm">
                            <div class="text-center">
                                <?= Button::widget(['button' => 'facebook', 'iconOnly' => true, 'label' => 'Facebook']) ?>
                                <?php if (in_array('facebook', array_keys($auths))): ?>
                                    <span class="text-success"><?= Yii::t('app', 'Facebook account linked') ?></span>
                                <?php else: ?>
                                    <span class="text-danger"><?= Yii::t('app', 'Facebook account not linked') ?></span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <hr/>
                            </div>
                            <div class="text-center">
                                <?php if (in_array('facebook', array_keys($auths))): ?>
                                    <?= Html::a(Yii::t('app', Icon::widget(['icon' => 'remove']) . ' ' . 'Remove account'), Yii::$app->urlManager->createUrl(['/account/linked', 'remove' => $auths['facebook']]), ['class' => 'btn btn-warning btn-xs', 'data-confirm' => Yii::t('app', 'Are you sure want to remove this link?')]) ?>
                                <?php else: ?>
                                    <?= Html::a(Yii::t('app', Icon::widget(['icon' => 'plus']) . ' ' . 'Add account'), Yii::$app->urlManager->createUrl(['/account/auth', 'authclient' => 'facebook']), ['class' => 'btn btn-success btn-xs']) ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (Yii::$app->authClientCollection->hasClient('google')): ?>
                    <div class="col-sm-3">
                        <div class="well well-sm">

                            <div class="text-center">
                                <?= Button::widget(['button' => 'google', 'iconOnly' => true, 'label' => 'Google']) ?>
                                <?php if (in_array('google', array_keys($auths))): ?>
                                    <span class="text-success"><?= Yii::t('app', 'Google account linked') ?></span>
                                <?php else: ?>
                                    <span class="text-danger"><?= Yii::t('app', 'Google account not linked') ?></span>
                                <?php endif; ?>
                            </div>
                            <div><hr /></div>
                            <div class="text-center">
                                <?php if (in_array('google', array_keys($auths))): ?>
                                    <?= Html::a(Yii::t('app', Icon::widget(['icon' => 'remove']) . ' ' . 'Remove account'), Yii::$app->urlManager->createUrl(['/account/linked', 'remove' => $auths['google']]), ['class' => 'btn btn-warning btn-xs', 'data-confirm' => Yii::t('app', 'Are you sure want to remove this link?')]) ?>
                                <?php else: ?>
                                    <?= Html::a(Yii::t('app', Icon::widget(['icon' => 'plus']) . ' ' . 'Add account'), Yii::$app->urlManager->createUrl(['/account/auth', 'authclient' => 'google']), ['class' => 'btn btn-success btn-xs']) ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

