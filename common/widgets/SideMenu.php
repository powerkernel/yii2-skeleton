<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2016 Power Kernel
 */

namespace common\widgets;

use common\Core;
use common\models\Setting;
use Yii;
use \yii\bootstrap\Widget;

/**
 * Class SideMenu
 * @package common\widgets
 */
class SideMenu extends Widget
{

    public $items = [];
    public $homeTitle = '';
    public $homeUrl = '';


    /**
     * @inheritdoc
     * @return string
     */
    public function run()
    {

        if (Yii::$app->id == 'app-backend') {
            $this->items = $this->adminItems();
        }
        if (Yii::$app->id == 'app-frontend') {
            $this->items = $this->accountItems();
        }
        return $this->render('sideMenu', ['items' => $this->items, 'homeTitle' => $this->homeTitle, 'homeUrl' => $this->homeUrl]);
    }

    /**
     * admin default items
     * @return array
     */
    protected function adminItems()
    {
        $general = [
            'title' => 'General',
            'icon' => 'tachometer-alt',
            'items' => [
                ['icon' => 'users', 'label' => Yii::t('app', 'Users'), 'url' => ['/account/index'], 'active' => Core::checkMCA(null, 'account', '*')],
                ['icon' => 'key', 'label' => Yii::t('app', 'RBAC'), 'url' => ['/rbac/index'], 'active' => Core::checkMCA(null, 'rbac', '*')],
                ['icon' => 'edit', 'label' => Yii::t('app', 'Blog/News'), 'url' => ['/blog/index'], 'active' => Core::checkMCA(null, 'blog', '*'), 'enabled' => Yii::$app->params['enableBlog']],
                ['icon' => 'newspaper', 'label' => Yii::t('app', 'Pages'), 'url' => ['/page/index'], 'active' => Core::checkMCA(null, 'page', '*')],
                ['icon' => 'list', 'label' => Yii::t('app', 'Menu'), 'url' => ['/menu/index'], 'active' => Core::checkMCA(null, 'menu', '*')],
                ['icon' => 'image', 'label' => Yii::t('app', 'Banners'), 'url' => ['/banner/index'], 'active' => Core::checkMCA(null, 'banner', '*')],
                ['icon' => 'cog', 'label' => Yii::t('app', 'Settings'), 'url' => ['/setting/index'], 'active' => Core::checkMCA(null, 'setting', '*')],
                ['icon' => 'language', 'label' => Yii::t('app', 'Languages'), 'url' => ['/i18n/index'], 'active' => Core::checkMCA(null, 'i18n', '*')],
                ['icon' => 'cogs', 'label' => Yii::t('app', 'Services'), 'url' => ['/service/index'], 'active' => Core::checkMCA(null, 'service', '*')],
                ['icon' => 'tasks', 'label' => Yii::t('app', 'Task Logs'), 'url' => ['/task/index'], 'active' => Core::checkMCA(null, 'task', '*')],
            ],
        ];
        $general['active'] = SideMenu::isActive($general['items']);

        return array_merge([$general], $this->loadModuleItem('admin'));
    }

    /**
     * check is active tree
     * @param $items
     * @return bool
     */
    public static function isActive($items)
    {
        $active = false;
        foreach ($items as $item) {
            if ($item['active']) {
                $active = true;
                break;
            }
        }
        return $active;
    }

    /**
     * load module menu
     * @param $type
     * @return array
     */
    protected function loadModuleItem($type)
    {
        $menuFilePath = 'memberMenu.php';
        if ($type == 'admin') {
            $menuFilePath = 'adminMenu.php';
        }
        $vendors = [
            'harrytang',
            'powerkernel',
        ];
        $items = [];

        foreach ($vendors as $vendor) {
            if (file_exists(Yii::$app->vendorPath . DIRECTORY_SEPARATOR . $vendor)) {
                $modules = scandir(Yii::$app->vendorPath . DIRECTORY_SEPARATOR . $vendor);
                foreach ($modules as $module) {
                    if (!preg_match('/[\.]+/', $module)) {
                        $moduleMenuFile = \Yii::$app->vendorPath . DIRECTORY_SEPARATOR . $vendor . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . $menuFilePath;
                        if (is_file($moduleMenuFile)) {
                            $menu = require($moduleMenuFile);
                            if (is_array($menu)) {
                                $items = array_merge($items, $menu);
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
    protected function accountItems()
    {
        $menu = [
            'title' => Yii::t('app', 'My Account'),
            'icon' => 'user',
            'items' => [
                ['icon' => 'id-card', 'label' => Yii::t('app', 'Profile'), 'url' => ['/account/index'], 'active' => Core::checkMCA(null, 'account', 'index')],
                ['icon' => 'phone', 'label' => Yii::t('app', 'Update Phone'), 'url' => ['/account/phone'], 'active' => Core::checkMCA(null, 'account', 'phone')],
            ]
        ];
        $menu['active'] = SideMenu::isActive($menu['items']);
        /* blog */
        $blog = [
            'title' => Yii::t('app', 'Blog/News'),
            'enabled'=> Yii::$app->params['enableBlog'] && Yii::$app->user->can('author'),
            'icon' => 'rss-square',
            'items' => [
                ['icon' => 'rss', 'label' => Yii::t('app', 'My Articles'), 'url' => ['/blog/manage'], 'active' => Core::checkMCA(null, 'blog', 'manage'), 'enabled' => Yii::$app->params['enableBlog'] && Yii::$app->user->can('author')],
                ['icon' => 'pen-square', 'label' => Yii::t('app', 'Write'), 'url' => ['/blog/create'], 'active' => Core::checkMCA(null, 'blog', 'create'), 'enabled' => Yii::$app->params['enableBlog'] && Yii::$app->user->can('author')],
            ]
        ];
        $blog['active'] = SideMenu::isActive($blog['items']);


        //return array_merge([$menu], [$blog]);
        return array_merge([$menu], [$blog], $this->loadModuleItem('account'));

    }


}
