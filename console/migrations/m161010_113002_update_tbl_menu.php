<?php

use yii\db\Migration;

/**
 * Class m161010_113002_update_tbl_menu
 */
class m161010_113002_update_tbl_menu extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%core_menu}}', 'active_route', $this->string()->null()->after('label'));
		$this->addDefaultData();
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%core_menu}}', 'active_route');
		
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
