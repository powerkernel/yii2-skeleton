<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2017 Modern Kernel
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
