<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('app', 'Login');
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


$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Account'), 'url' => ['/account']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-login-index">
    <div class="row">
        <div style="" class="col-xs-12">

            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'rememberMe')->checkbox() ?>

            <div class="text-center">
                <div class="form-group">
                    <?= Html::submitButton($this->title, ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
                <div>
                    <?= Yii::t('app', 'If you forgot your password you can {RESET}.', ['RESET' => Html::a(Yii::t('app', 'reset it'), ['/account/reset'])]); ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
            <div>
                <hr/>
            </div>
            <?php if (!Yii::$app->params['account']['registrationDisabled']): ?>
                <div class="text-center">
                    <?= Yii::t('app', 'Do not have an account?') ?> <a
                        href="<?= Yii::$app->urlManager->createUrl(['/account/signup']) ?>"
                        class="btn btn-xs btn-danger"><?= Yii::t('app', 'Signup') ?></a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
