<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

namespace common\plugins\moment;


use yii\web\AssetBundle;

/**
 * Class MomentAsset
 * @package common\plugins\moment
 */
class MomentAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/assets';

    public $js = [
        'moment.min.js'
    ];

}