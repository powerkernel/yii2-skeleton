<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

namespace common\plugins\fastclick;

use yii\web\AssetBundle;

/**
 * Class FastclickAsset
 * @package common\plugins\fastclick
 */
class FastclickAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/assets';

    public $js = [
        'fastclick.min.js',
    ];

}
