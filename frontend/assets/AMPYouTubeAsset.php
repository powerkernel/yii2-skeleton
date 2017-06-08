<?php

namespace frontend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * AMP YouTube Asset asset bundle.
 */
class AMPYouTubeAsset extends AssetBundle
{

    /**
     * @var array
     */
    public $js = [
        'https://cdn.ampproject.org/v0/amp-youtube-0.1.js'
    ];

    /**
     * @var array
     */
    public $jsOptions=[
        'async'=>true,
        'custom-element'=>'amp-youtube',
        'position'=>View::POS_HEAD,
    ];

    /**
     * @var array
     */
    public $depends=[
        'frontend\assets\AMPAsset'
    ];

}
