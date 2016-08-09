<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

namespace backend\widgets;

use common\Core;
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
            ['icon' => 'users', 'label' => 'Users', 'url' => ['/account/index'], 'active' => Core::checkMCA(null, 'account', '*')],

        ];
        return $this->render('sideMenu', ['items' => $this->items]);
    }


} 