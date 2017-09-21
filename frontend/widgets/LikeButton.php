<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2016 Power Kernel
 */

namespace frontend\widgets;


use yii\helpers\Html;

/**
 * Class LikeButton
 * @package frontend\widgets
 */
class LikeButton extends SocialWidget
{

    public $href = '';
    public $send = 'true';
    public $layout = "standard"; // standard, button_count, box_count
    public $width = '450';
    public $show_faces = 'true';
    public $action = 'like';
    public $font = 'arial';
    public $colorscheme = 'light';
    public $ref = '';
    public $kid_directed_site = 'false';
    public $share = 'false';
    public $options = [];

    public function init()
    {
        parent::init();

        if (!empty($this->href))
            $this->options['data-href'] = $this->href;
        if (!empty($this->send))
            $this->options['data-send'] = $this->send;
        if (!empty($this->layout))
            $this->options['data-layout'] = $this->layout;
        if (!empty($this->width))
            $this->options['data-width'] = $this->width;
        if (!empty($this->show_faces))
            $this->options['data-show_faces'] = $this->show_faces;
        if (!empty($this->action))
            $this->options['data-action'] = $this->action;
        if (!empty($this->font))
            $this->options['data-font'] = $this->font;
        if (!empty($this->colorscheme))
            $this->options['data-colorscheme'] = $this->colorscheme;
        if (!empty($this->ref))
            $this->options['data-ref'] = $this->ref;
        if (!empty($this->share))
            $this->options['data-share'] = $this->share;
        if (!empty($this->kid_directed_site))
            $this->options['data-kid_directed_site'] = $this->kid_directed_site;
        if (!isset($this->options['class'])) {
            $this->options['class'] = ' fb-like';
        } else {
            $this->options['class'] .= ' fb-like';
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->registerFacebookPlugin()) {
            echo Html::tag('div', '', $this->options);
        }

    }
} 