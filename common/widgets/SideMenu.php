<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

namespace common\widgets;

use common\Core;
use Yii;
use \yii\bootstrap\Widget;

/**
 * Class SideMenu
 * @package common\widgets
 */
class SideMenu extends Widget
{

    public $items = [];
    public $homeTitle='';
    public $homeUrl='';


    /**
     * @inheritdoc
     * @return string|void
     */
    public function run()
    {

        if(Yii::$app->id == 'app-backend'){
            $this->items=$this->adminItems();
        }
        if(Yii::$app->id == 'app-frontend'){
            $this->items=$this->accountItems();
        }
        return $this->render('sideMenu', ['items' => $this->items, 'homeTitle'=>$this->homeTitle, 'homeUrl'=>$this->homeUrl]);
    }

    /**
     * admin default items
     * @return array
     */
    protected function adminItems(){
        $default= [
            ['icon' => 'users', 'label' => Yii::t('app','Users'), 'url' => ['/account/index'], 'active' => Core::checkMCA(null, 'account', '*')],
            ['icon' => 'key', 'label' => Yii::t('app','RBAC'), 'url' => ['/rbac/index'], 'active' => Core::checkMCA(null, 'rbac', '*')],
            ['icon' => 'edit', 'label' => Yii::t('app','Blog'), 'url' => ['/blog/index'], 'active' => Core::checkMCA(null, 'blog', '*'), 'enabled'=>Yii::$app->params['enableBlog']],
            ['icon' => 'files-o', 'label' => Yii::t('app','Pages'), 'url' => ['/page/index'], 'active' => Core::checkMCA(null, 'page', '*')],
            ['icon' => 'list', 'label' => Yii::t('app','Menu'), 'url' => ['/menu/index'], 'active' => Core::checkMCA(null, 'menu', '*')],
            ['icon' => 'cog', 'label' => Yii::t('app','Settings'), 'url' => ['/setting/index'], 'active' => Core::checkMCA(null, 'setting', '*')],
            ['icon' => 'language', 'label' => Yii::t('app','Languages'), 'url' => ['/i18n/index'], 'active' => Core::checkMCA(null, 'i18n', '*')],
            ['icon' => 'gears', 'label' => Yii::t('app','Services'), 'url' => ['/service/index'], 'active' => Core::checkMCA(null, 'service', '*')],
        ];
        return array_merge($default, $this->loadModuleItem('admin'));
    }

    /**
     * load module menu
     * @param $type
     * @return array
     */
    protected function loadModuleItem($type){
        $menuFilePath='memberMenu.php';
        if($type=='admin'){
            $menuFilePath = 'adminMenu.php';
        }
        $vendors=[
            'harrytang',
            'modernkernel'
        ];
        $items=[];

        foreach($vendors as $vendor){
            if(file_exists(Yii::$app->vendorPath . DIRECTORY_SEPARATOR . $vendor)){
                $modules = scandir(Yii::$app->vendorPath . DIRECTORY_SEPARATOR . $vendor);
                foreach ($modules as $module) {
                    if (!preg_match('/[\.]+/', $module)) {
                        $moduleMenuFile = \Yii::$app->vendorPath . DIRECTORY_SEPARATOR . $vendor . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . $menuFilePath;
                        if (is_file($moduleMenuFile)) {
                            $menu = require($moduleMenuFile);
                            if(is_array($menu)){
                                $items=array_merge($items,$menu);
                            }
                        }
                    }
                }
            }
        }

        return $items;
    }

    /**
     * account default items
     * @return array
     */
    protected function accountItems(){
        $default= [
            ['label'=>Yii::t('app', 'Personal settings')],
            ['icon' => 'user', 'label' => Yii::t('app', 'Profile'), 'url' => ['/account/index'], 'active' => Core::checkMCA(null, 'account', 'index')],
            ['icon' => 'envelope', 'label' => Yii::t('app', 'Email'), 'url' => ['/account/email'], 'active' => Core::checkMCA(null, 'account', 'email')],
            ['icon' => 'lock', 'label' => Yii::t('app', 'Password'), 'url' => ['/account/password'], 'active' => Core::checkMCA(null, 'account', 'password')],
            ['icon' => 'puzzle-piece', 'label' => Yii::t('app', 'Linked Accounts'), 'url' => ['/account/linked'], 'active' => Core::checkMCA(null, 'account', 'linked')],

            ['label'=>Yii::t('app', 'Blog'), 'enabled'=>Yii::$app->params['enableBlog']&&Yii::$app->user->can('author')],
            ['icon' => 'rss-square', 'label' => Yii::t('app', 'My Blog'), 'url' => ['/blog/manage'], 'active' => Core::checkMCA(null, 'blog', 'manage'), 'enabled'=>Yii::$app->params['enableBlog']&&Yii::$app->user->can('author')],
            ['icon' => 'pencil-square', 'label' => Yii::t('app', 'Write'), 'url' => ['/blog/create'], 'active' => Core::checkMCA(null, 'blog', 'create'), 'enabled'=>Yii::$app->params['enableBlog']&&Yii::$app->user->can('author')],
        ];
        return array_merge($default, $this->loadModuleItem('account'));

    }


} 