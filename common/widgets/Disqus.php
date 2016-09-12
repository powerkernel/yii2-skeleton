<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

namespace common\widgets;


use common\models\Setting;
use Yii;
use yii\base\View;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * Class Disqus
 * @package common\widgets
 */
class Disqus extends Widget
{
    public $pageUrl = '';
    public $pageIdentifier = '';

    /**
     * @inheritdoc
     */
    public function run()
    {
        $disqus = Setting::getValue('disqus');
        if (!empty($disqus)) {
            $js=$this->registerVariables().$disqus;
            Yii::$app->view->registerJs($js, \yii\web\View::POS_END);
            echo Html::tag('div', '', ['id' => 'disqus_thread']);
        }
    }

    /**
     * register variables
     */
    protected function registerVariables()
    {
        $pageUrl = '';
        $pageIdentifier = '';
        $active = false;
        if (!empty($this->pageUrl)) {
            $pageUrl = 'this.page.url = "' . $this->pageUrl . '";';
            $active = true;
        }
        if (!empty($this->pageIdentifier)) {
            $pageIdentifier = 'this.page.identifier = "' . $this->pageIdentifier . '";';
            $active = true;
        }

        if ($active) {
            $js = <<<EOB
 var disqus_config = function () {
    {$pageUrl}
    {$pageIdentifier}     
 };
EOB;
            return $js;
        }

    }
}