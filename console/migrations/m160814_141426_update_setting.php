<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m160814_141426_update_setting
 */
class m160814_141426_update_setting extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        // type, data, default, rules
        $this->addColumn('{{core_setting}}', 'type', Schema::TYPE_STRING.' NOT NULL DEFAULT "textInput"');
        $this->addColumn('{{core_setting}}', 'data', Schema::TYPE_TEXT);
        $this->addColumn('{{core_setting}}', 'default', Schema::TYPE_STRING);
        $this->addColumn('{{core_setting}}', 'rules', Schema::TYPE_TEXT);

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{core_setting}}', 'rules');
        $this->dropColumn('{{core_setting}}', 'default');
        $this->dropColumn('{{core_setting}}', 'data');
        $this->dropColumn('{{core_setting}}', 'type');
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
