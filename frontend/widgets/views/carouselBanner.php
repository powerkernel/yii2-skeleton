<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */
use yii\bootstrap\Carousel;

/* @var $items[] */

echo Carousel::widget([
    'items' => $items,
    'options'=>[
        'class'=>'carousel slide',
    ],
    'controls'=>[
        \modernkernel\fontawesome\Icon::widget(['icon'=>'chevron-left']),
        \modernkernel\fontawesome\Icon::widget(['icon'=>'chevron-right']),
    ]
]);
