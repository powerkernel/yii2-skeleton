<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2016 Power Kernel
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