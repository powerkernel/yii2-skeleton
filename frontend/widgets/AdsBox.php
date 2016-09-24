<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

namespace frontend\widgets;


use yii\base\Widget;

/**
 * Class AdsBox
 * @package frontend\widgets
 */
class AdsBox extends Widget
{
    /**
     * @inheritdoc
     * @return string
     */
    public function run(){
        return $this->render('adsBox');
    }
}