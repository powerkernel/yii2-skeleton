<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Class m151122_044038_update
 */
class m151122_044038_update extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{core_account}}', 'language', Schema::TYPE_STRING . '(5) NOT NULL DEFAULT "en-US" AFTER `role`');
        $this->addColumn('{{core_account}}', 'timezone', Schema::TYPE_STRING . '(100) NOT NULL DEFAULT "GMT" AFTER `language`');
        $this->addColumn('{{core_account}}', 'username_changed', Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0 AFTER `username`');
    }

    /**
     * @inheritdoc
     * @return bool
     */
    public function down()
    {
        $this->dropColumn('{{core_account}}', 'username_changed');
        $this->dropColumn('{{core_account}}', 'timezone');
        $this->dropColumn('{{core_account}}', 'language');
        return true;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
