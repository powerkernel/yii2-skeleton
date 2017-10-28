<?php
use himiklab\yii2\recaptcha\ReCaptcha;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

$this->title = Yii::t('app', 'Signup');
$keywords = Yii::t('app', 'login, signup, create account');
$description = Yii::t('app', 'Create an account or log into {APP}. Start using our website immediately', ['APP'=>Yii::$app->name]);

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
<div class="account-signup">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <div class="col-xs-12">
            <?php $form = ActiveForm::begin(['id' => 'signup-form', 'enableAjaxValidation' => false]); ?>
            <?= $form->field($model, 'name') ?>
            <?= $form->field($model, 'email') ?>
            <?php if(\common\Core::isReCaptchaEnabled()):?>
            <?= $form->field($model, 'captcha')->widget(ReCaptcha::className())->label(false) ?>
            <?php endif;?>


            <p>
                <?= Yii::t(
                    'app',
                    'By clicking on the "Signup" button below, you certify that you have read and agree to our {TERMS} and {PRIVACY}.',
                    [
                        'TERMS'=>Html::a('terms of use', Yii::$app->urlManager->createUrl(['/site/page', 'id'=>'terms']), ['target'=>'_blank']),
                        'PRIVACY'=>Html::a('privacy policy', Yii::$app->urlManager->createUrl(['/site/page', 'id'=>'privacy']), ['target'=>'_blank']),
                    ]
                ) ?>
            </p>

            <div class="form-group">
                <?= \common\components\SubmitButton::widget(['text'=>Yii::t('app', 'Signup'), 'options'=>['class' => 'btn btn-primary', 'name' => 'signup-button']]) ?>
            </div>
            <?php ActiveForm::end(); ?>
            <div><hr /></div>
            <p class="text-center">
                <?= Yii::t('app', 'Already have an account?') ?>
                <?= Html::a(Yii::t('app', 'Sign in'), Yii::$app->urlManager->createUrl(['/account/login'])) ?>
            </p>

        </div>
    </div>
</div>
