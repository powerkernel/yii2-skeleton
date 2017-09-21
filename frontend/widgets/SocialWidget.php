<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2016 Power Kernel
 */

namespace frontend\widgets;

use common\models\Setting;
use Yii;
use yii\base\Widget;
use yii\web\View;


/**
 * Class SocialWidget
 * @package frontend\widgets
 */
class SocialWidget extends Widget
{
    public $fb = []; // facebook
    public $gp = []; // google plus
    public $twttr = []; // twitter

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        /* facebook */
        if (empty($this->fb['appId'])) {
            $this->fb['appId'] = Setting::getValue('fbAppId');
        }
        if (empty($this->fb['language'])) {
            $this->fb['language'] = str_ireplace('-', '_', Yii::$app->language);
        }
    }

    /**
     * Registers facebook js SDK
     */
    protected function registerFacebookPlugin()
    {
        if (isset($this->fb['appId'], $this->fb['language'])) {
            $js = <<<EOD
window.fbAsyncInit = function() {
    FB.init({
        appId      : {$this->fb['appId']},
        xfbml      : true,
        version    : 'v2.1'
    });
};

(function(d, s, id){
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/{$this->fb['language']}/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
EOD;
            $css = <<<EOD
.fb-like span {vertical-align: inherit !important;}
EOD;
            $view = $this->getView();
            $view->registerJs($js, View::POS_BEGIN);
            $view->registerCss($css);
            return true;
        }
        return false;
    }

    /**
     * Registers google+ js SDK
     */
    protected function registerGooglePlusPlugin()
    {
        $lang = 'en-US';
        if (preg_match('/^[a-z]{2}/', Yii::$app->language, $match)) {
            $lang = $match[0];
        }
        $js = <<<EOD
window.___gcfg = {
    lang: '{$lang}'
};
(function() {
    var po = document.createElement("script"); po.type = "text/javascript"; po.async = true;
    po.src = "https://apis.google.com/js/platform.js";
    var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(po, s);
})();
EOD;
        $view = $this->getView();
        $view->registerJs($js, View::POS_BEGIN);
        return true;
    }

    /**
     * Twitter
     */
    protected function registerTwitterPlugin()
    {
        $js = <<<EOD
window.twttr = (function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0],
    t = window.twttr || {};
  if (d.getElementById(id)) return t;
  js = d.createElement(s);
  js.id = id;
  js.src = "https://platform.twitter.com/widgets.js";
  fjs.parentNode.insertBefore(js, fjs);

  t._e = [];
  t.ready = function(f) {
    t._e.push(f);
  };

  return t;
}(document, "script", "twitter-wjs"));
EOD;
        $view = $this->getView();
        $view->registerJs($js, View::POS_BEGIN);
        return true;
    }
} 