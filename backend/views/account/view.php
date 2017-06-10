<?php

use common\models\Account;
use common\models\Setting;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Account */


$this->title = $model->fullname;
$keywords = '';
$description = '';

$this->registerMetaTag(['name' => 'keywords', 'content' => $keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $description]);
//$this->registerMetaTag(['name' => 'robots', 'content' => 'noindex, nofollow, nosnippet, noodp, noarchive, noimageindex']);

/* Facebook */
//$this->registerMetaTag(['property' => 'og:title', 'content' => $this->title]);
//$this->registerMetaTag(['property' => 'og:description', 'content' => $description]);
//$this->registerMetaTag(['property' => 'og:type', 'content' => '']);
//$this->registerMetaTag(['property' => 'og:image', 'content' => '']);
//$this->registerMetaTag(['property' => 'og:url', 'content' => '']);
//$this->registerMetaTag(['property' => 'fb:app_id', 'content' => '']);
//$this->registerMetaTag(['property' => 'fb:admins', 'content' => '']);

/* Twitter */
//$this->registerMetaTag(['name'=>'twitter:title', 'content'=>$this->title]);
//$this->registerMetaTag(['name'=>'twitter:description', 'content'=>$description]);
//$this->registerMetaTag(['name'=>'twitter:card', 'content'=>'summary']);
//$this->registerMetaTag(['name'=>'twitter:site', 'content'=>'']);
//$this->registerMetaTag(['name'=>'twitter:image', 'content'=>'']);
//$this->registerMetaTag(['name'=>'twitter:data1', 'content'=>'']);
//$this->registerMetaTag(['name'=>'twitter:label1', 'content'=>'']);
//$this->registerMetaTag(['name'=>'twitter:data2', 'content'=>'']);
//$this->registerMetaTag(['name'=>'twitter:label2', 'content'=>'']);

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

/* misc */
//$js=file_get_contents(__DIR__.'/index.min.js');
//$this->registerJs($js);
//$css=file_get_contents(__DIR__.'/index.css');
//$this->registerCss($css);

?>
<div class="account-view">
    <div class="box box-info">
        <div class="box-body">
            <div class="table-responsive">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        'seo_name',
                        'fullname',
                        //'fullname_changed:boolean',
                        //'auth_key',
                        //'password_hash',
                        //'password_reset_token',
                        'email:email',
                        'email_verified:boolean',
                        //'new_email:email',
                        //'change_email_token:email',
                        [
                            'label' => 'Role(s)',
                            'value' => $this->render('_rbac', ['model'=>$model]),
                            'format'=>'raw'
                        ],
                        'language',
                        'timezone',
                        [
                            'attribute'=>'status',
                            'value' => $model->statusText,
                        ],
                        'created_at:dateTime',
                        'updated_at:dateTime',
                    ],
                ]) ?>
            </div>

            <p class="pull-left">
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?php if(Yii::$app->hasModule('billing')):?>
                    <?= Html::a(Yii::t('app', 'Billing Info'), Yii::$app->urlManager->createUrl(['/billing/info/check', 'id'=>$model->id]), ['class' => 'btn btn-primary']) ?>
                <?php endif;?>

                <?php if($model->status!=Account::STATUS_SUSPENDED && !Setting::getValue('passwordLessLogin')):?>
                <?= Html::a(Yii::t('app', 'Send New Password'), ['new-password', 'id' => $model->id], [
                    'class' => 'btn btn-info',
                    'data' => [
                        'confirm' => Yii::t('app', 'Send new password to this user?'),
                        //'method' => 'post',
                    ],
                ]) ?>
                <?php endif;?>

                <?php if($model->canSuspend()):?>
                <?= Html::a(Yii::t('app', 'Suspend'), ['suspend', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to suspend this user?'),
                        //'method' => 'post',
                    ],
                ]) ?>
                <?php endif;?>
                <?php if($model->status== Account::STATUS_SUSPENDED):?>
                    <?= Html::a(Yii::t('app', 'Unsuspend'), ['unsuspend', 'id' => $model->id], [
                        'class' => 'btn btn-success',
                        'data' => [
                            'confirm' => Yii::t('app', 'Are you sure you want to reactivate this user?'),
                            //'method' => 'post',
                        ],
                    ]) ?>
                <?php endif;?>
            </p>

            <?php if($model->status!=Account::STATUS_SUSPENDED):?>
            <p class="pull-right">
                <?= Html::a(Yii::t('app', 'Login'), ['login-as', 'id' => $model->id], ['class' => 'btn btn-warning', 'target'=>'_blank']) ?>
            </p>
            <?php endif;?>
        </div>
    </div>
</div>
