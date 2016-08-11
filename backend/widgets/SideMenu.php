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
            ['icon' => 'cog', 'label' => Yii::t('app','Settings'), 'url' => ['/setting/index'], 'active' => Core::checkMCA(null, 'setting', '*')],
        ];
        return $this->render('sideMenu', ['items' => $this->items]);
    }


} 