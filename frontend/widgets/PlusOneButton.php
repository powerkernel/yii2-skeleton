<?php
/** 
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com 
 * @copyright Copyright (c) 2016 Modern Kernel
 */

namespace frontend\widgets;


use yii\helpers\Html;

/**
 * Class PlusOneButton
 * @package frontend\widgets
 */
class PlusOneButton extends SocialWidget {

    public $href = '';
    public $size='standard'; // small, medium, standard, tall
    public $annotation='bubble';
    public $width='';
    public $align='left';
    public $expandTo='';
    public $callback='';
    public $onstartinteraction='';
    public $onendinteraction='';
    public $recommendations='true';

    public $options=[];

    public function init(){
        parent::init();

        if(!empty($this->href))                 $this->options['data-href']                  = $this->href;
        if(!empty($this->size))                 $this->options['data-size']                  = $this->size;
        if(!empty($this->annotation))           $this->options['data-annotation']            = $this->annotation;
        if(!empty($this->width))                $this->options['data-width']                 = $this->width;
        if(!empty($this->align))                $this->options['data-align']                 = $this->align;
        if(!empty($this->expandTo))             $this->options['data-expandTo']              = $this->expandTo;
        if(!empty($this->callback))             $this->options['data-callback']              = $this->callback;
        if(!empty($this->onstartinteraction))   $this->options['data-onstartinteraction']    = $this->onstartinteraction;
        if(!empty($this->onendinteraction))     $this->options['data-onendinteraction']      = $this->onendinteraction;
        if(!empty($this->recommendations))      $this->options['data-recommendations']       = $this->recommendations;

        if (!isset($this->options['class'])) {
            $this->options['class']='g-plusone';
        } else {
            $this->options['class'].=' g-plusone';
        }
    }

    /**
     * @inheritdoc
     */
    public function run(){
        if($this->registerGooglePlusPlugin()){
            echo Html::tag('div', '', $this->options);
        }

    }
} 