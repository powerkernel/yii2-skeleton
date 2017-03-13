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
    }


    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_menu_parent-menu-id', '{{%core_menu}}');
        $this->dropColumn('{{%core_menu}}', 'id_parent');
    }

}
