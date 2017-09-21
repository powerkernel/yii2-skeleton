<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2016 Power Kernel
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