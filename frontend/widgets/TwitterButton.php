<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

namespace frontend\widgets;


use yii\helpers\Html;

/**
 * Class TwitterButton
 * @package frontend\widgets
 */
class TwitterButton extends SocialWidget
{

    public $href = '';
    public $size = 'default'; // default, large
    public $count = 'horizontal'; // horizontal, vertical, none
    public $options = [];

    public function init()
    {
        parent::init();

        if (!empty($this->href))
            $this->options['data-href'] = $this->href;
        if (!empty($this->size))
            $this->options['data-size'] = $this->size;
        if (!empty($this->count))
            $this->options['data-count'] = $this->count;
        if (!isset($this->options['class'])) {
            $this->options['class'] = ' twitter-share-button';
        } else {
            $this->options['class'] .= ' twitter-share-button';
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->registerTwitterPlugin()) {
            echo Html::tag('a', '', $this->options);
        }

    }
} 