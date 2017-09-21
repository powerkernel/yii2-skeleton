<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */

use yii\bootstrap\Nav;

/* @var $this \yii\web\View */
/* @var $items [] */
?>
<?=
Nav::widget([
    'options' => ['class' => 'navbar-nav'],
    'items' => $items
]);
?>
