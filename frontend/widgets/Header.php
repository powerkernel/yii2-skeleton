<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

namespace frontend\widgets;


use common\models\Menu;
use Yii;
use yii\base\Widget;

/**
 * Class Header
 * @package frontend\widgets
 */
class Header extends Widget
{

    /**
     * @inheritdoc
     * @return string
     */
    public function run()
    {
        $items=[];
        $menus=Menu::find()->where(['status'=>Menu::STATUS_ACTIVE, 'position'=>'header'])->orderBy('order')->all();
        foreach ($menus as $menu){
            $items[]=['active'=>$menu->getActiveStatus(), 'label' => Yii::t('main', $menu->label), 'url' => preg_match('/\/\//', $menu->url)?$menu->url:[$menu->url], 'linkOptions'=>['class'=>$menu->class]];
        }
        return $this->render('header', ['items'=>$items]);
    }
}