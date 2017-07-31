<?php

use yii\db\Migration;

/**
 * Class m170312_135338_update_menu
 */
class m170312_135338_update_menu extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%core_menu}}', 'id_parent', $this->integer()->null()->after('id'));
        $this->addForeignKey('fk_menu_parent-menu-id', '{{%core_menu}}', 'id_parent', '{{%core_menu}}', 'id', 'CASCADE', 'CASCADE');
        $this->addDefaultData();
    }


    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_menu_parent-menu-id', '{{%core_menu}}');
        $this->dropColumn('{{%core_menu}}', 'id_parent');
    }


    /**
     * add default menus
     */
    protected function addDefaultData()
    {
        $items = [
            [
                'label' => 'Home',
                'url' => '/site/index',
                'position' => 'header',
            ],
            [
                'label' => 'Blog',
                'url' => '/blog/index',
                'position' => 'header',
            ],

        ];


        foreach ($items as $i=>$item) {
            $menu = new \common\models\Menu();
            $menu->label = $item['label'];
            $menu->url = $item['url'];
            $menu->position = $item['position'];
            $menu->order=$i;
            $menu->status = \common\models\Menu::STATUS_ACTIVE;
            $menu->save();
        }
    }
}
