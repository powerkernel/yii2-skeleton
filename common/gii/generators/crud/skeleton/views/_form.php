<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) <?= date('Y') ?> Power Kernel
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */

$js=file_get_contents(__DIR__.'/form.min.js');
$this->registerJs($js);
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form">

    <?= "<?php " ?>$form = ActiveForm::begin(['options'=>['class'=>'pds']]); ?>

<?php foreach ($generator->getColumnNames() as $attribute) {
    if (in_array($attribute, $safeAttributes)) {
        echo "    <?= " . $generator->generateActiveField($attribute) . " ?>\n\n";
    }
} ?>
    <div class="form-group">
        <?= "<?= " ?>Html::submitButton(\modernkernel\fontawesome\Icon::widget(['icon'=>'refresh fa-spin hidden']).'<span>'.$model->isNewRecord ? <?= $generator->generateString('Create') ?> : <?= $generator->generateString('Update').'</span>' ?>, ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?= "<?php " ?>ActiveForm::end(); ?>

</div>
