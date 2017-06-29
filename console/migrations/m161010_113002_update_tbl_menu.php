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

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%core_menu}}', 'active_route');
		
    }


}
