<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

namespace backend\widgets;

use common\Core;
use Yii;
use \yii\bootstrap\Widget;

/**
 * Class SideMenu
 * @package backend\widgets
 */
class SideMenu extends Widget
{

    public $items = [];


    /**
     * @inheritdoc
     * @return string|void
     */
    public function run()
    {
        $this->items = [
            ['icon' => 'users', 'label' => Yii::t('app','Users'), 'url' => ['/account/index'], 'active' => Core::checkMCA(null, 'account', '*')],
            ['icon' => 'key', 'label' => Yii::t('app','RBAC'), 'url' => ['/rbac/index'], 'active' => Core::checkMCA(null, 'rbac', '*')],
            ['icon' => 'edit', 'label' => Yii::t('app','Blog'), 'url' => ['/blog/index'], 'active' => Core::checkMCA(null, 'blog', '*')],
            ['icon' => 'files-o', 'label' => Yii::t('app','Pages'), 'url' => ['/page/index'], 'active' => Core::checkMCA(null, 'page', '*')],
            ['icon' => 'cog', 'label' => Yii::t('app','Settings'), 'url' => ['/setting/index'], 'active' => Core::checkMCA(null, 'setting', '*')],
            ['icon' => 'language', 'label' => Yii::t('app','Languages'), 'url' => ['/i18n/index'], 'active' => Core::checkMCA(null, 'i18n', '*')],
            ['icon' => 'gears', 'label' => Yii::t('app','Services'), 'url' => ['/service/index'], 'active' => Core::checkMCA(null, 'service', '*')],
        ];
        return $this->render('sideMenu', ['items' => $this->items]);
    }


} 