<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2016 Power Kernel
 */

namespace common\plugins\fastclick;

use yii\web\AssetBundle;

/**
 * Class FastclickAsset
 * @package common\plugins\fastclick
 */
class FastclickAsset extends AssetBundle
{
    public $sourcePath = '@common/plugins/fastclick/assets';

    public $js = [
        'fastclick.min.js',
    ];

}
