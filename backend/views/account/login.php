<?php
use common\models\Setting;
use modernkernel\bootstrapsocial\Button;
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


//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Account'), 'url' => ['/account']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-login-index">
    <div class="row">
        <div style="" class="col-xs-12">
            <h1 class="box-title text-center"><?= Yii::t('app', 'Login / Register') ?></h1>
            <div><hr /></div>

            <?php if(Yii::$app->authClientCollection->hasClient('facebook')):?>
                <?= Button::widget([
                    'button' => 'facebook',
                    'link' => Yii::$app->urlManager->createUrl(['/account/auth', 'authclient'=>'facebook']),
                    'label'=> Yii::t('app', 'Login with Facebook')
                ]) ?>
            <?php endif;?>
            <?php if(Yii::$app->authClientCollection->hasClient('google')):?>
                <?= Button::widget([
                    'button' => 'google',
                    'link' => Yii::$app->urlManager->createUrl(['/account/auth', 'authclient'=>'google']),
                    'label'=> Yii::t('app', 'Login with Google')
                ]) ?>
            <?php endif;?>


            <div>
                <hr/>
            </div>

            <?php if(!Yii::$app->session->hasFlash('info')):?>
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
            <?= $form->field($model, 'email') ?>
            <?php if($model->scenario=='default'):?>
                <?= $form->field($model, 'password')->passwordInput() ?>
            <?php endif;?>
            <?= $form->field($model, 'rememberMe')->checkbox() ?>
            <div class="form-group text-center">
                <?= Html::submitButton(Yii::t('app', 'Login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
            <?php endif;?>

            <?php if(!Setting::getValue('passwordLessLogin')):?>
            <div>
                <hr/>
            </div>
            <div class="text-center">
                <?= Html::a(Yii::t('app', 'Sign up'), Yii::$app->urlManagerFrontend->createUrl(['/account/signup']), ['class' => 'btn btn-danger']); ?>
                <?= Html::a(Yii::t('app', 'Forgot password?'), Yii::$app->urlManagerFrontend->createUrl(['/account/reset']), ['class' => 'btn btn-default']); ?>
            </div>
            <?php endif;?>

        </div>
    </div>
</div>
