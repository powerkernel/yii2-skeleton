<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

namespace common\web;


use yii\web\AssetBundle;

/**
 * Class MomentjsAsset
 * @package modernkernel\momentjs
 */
class MomentjsAsset extends AssetBundle
{
    CONST VERSION = '2.14.1';
    public $js = [
        //'https://cdnjs.cloudflare.com/ajax/libs/moment.js/' . self::VERSION . '/moment-with-locales.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/moment.js/' . self::VERSION . '/moment.min.js'
    ];


}