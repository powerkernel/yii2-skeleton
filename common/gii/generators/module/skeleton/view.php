<?php
/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\module\Generator */
?>

<?php
echo "<?php\n";
?>
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) <?= date('Y') ?> Power Kernel
 */
<?php
echo "?>\n";
?>

<div class="<?= $generator->moduleID . '-default-index' ?>">
    <h1><?= "<?= " ?>$this->context->action->uniqueId ?></h1>
    <p>
        This is the view content for action "<?= "<?= " ?>$this->context->action->id ?>".
        The action belongs to the controller "<?= "<?= " ?>get_class($this->context) ?>"
        in the "<?= "<?= " ?>$this->context->module->id ?>" module.
    </p>
    <p>
        You may customize this page by editing the following file:<br>
        <code><?= "<?= " ?>__FILE__ ?></code>
    </p>
</div>
