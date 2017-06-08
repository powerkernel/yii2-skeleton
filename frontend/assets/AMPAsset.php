<?php

namespace frontend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Main frontend application asset bundle.
 */
class AMPAsset extends AssetBundle
{

    public $js = [
        'https://cdn.ampproject.org/v0.js'
    ];

    public $jsOptions=[
        'async'=>true,
        'position'=>View::POS_HEAD
    ];

}
