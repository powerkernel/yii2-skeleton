<?php


use common\models\Setting;
use conquer\select2\Select2Widget;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model \yii\base\DynamicModel */
/* @var $attributes [] */
/* @var $tabs [] */
/* @var $settings [] */

$this->title = Yii::t('app', 'Settings');
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

/* breadcrumbs */
$this->params['breadcrumbs'][] = $this->title;

/* misc */
$this->registerJs('$(document).on("pjax:send", function(){ $(".grid-view-overlay").removeClass("hidden");});$(document).on("pjax:complete", function(){ $(".grid-view-overlay").addClass("hidden");})');
//$js=file_get_contents(__DIR__.'/index.min.js');
//$this->registerJs($js);
//$css=file_get_contents(__DIR__.'/index.css');
//$this->registerCss($css);
//echo json_encode(\common\Core::getTimezoneList());
//var_dump(json_encode(['required'=>[]]));

?>
<div class="setting-index">
    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'fieldConfig'=>['horizontalCssClasses' => [
            'offset' => '',
            'label' => 'col-sm-2',
            'wrapper' => 'col-sm-6',
            'error' => '',
            'hint' => 'col-sm-4',
        ]],
    ]); ?>
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <?php foreach ($tabs as $i => $tab): ?>
                <li class="<?= $i == 0 ? 'active' : '' ?>">
                    <a href="#<?= $tab ?>" data-toggle="tab" aria-expanded="<?= $i == 0 ? 'true' : 'false' ?>"><?= $tab ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="tab-content">
            <?php foreach ($tabs as $i => $tab): ?>
                <div class="tab-pane <?= $i == 0 ? 'active' : '' ?>" id="<?= $tab ?>">
                    <?php foreach ($settings[$tab] as $key => $setting): ?>

                        <?php if ($setting['type'] == 'textInput'): ?>
                            <?= $form->field($model, $key)->textInput()->label($setting['title'])->hint($setting['description']) ?>
                        <?php endif; ?>

                        <?php if ($setting['type'] == 'passwordInput'): ?>
                            <?= $form->field($model, $key)->passwordInput()->label($setting['title'])->hint($setting['description']) ?>
                        <?php endif; ?>

                        <?php if ($setting['type'] == 'dropDownList'): ?>
                            <?=
                            $form->field($model, $key)->dropDownList(in_array($setting['data'], ['{TIMEZONE}', '{LOCALE}'])? Setting::getListData($setting['data']):json_decode($setting['data'], true))->label($setting['title'])->hint($setting['description'])
                            ?>
                        <?php endif; ?>

                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
            <div><hr /></div>
            <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <?= Html::submitButton(Yii::t('app', 'Save Settings'), ['class' => 'btn btn-primary']) ?>
            </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
