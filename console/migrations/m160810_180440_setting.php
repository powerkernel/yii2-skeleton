<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m160810_180440_setting
 */
class m160810_180440_setting extends Migration
{

    /**
     * @inheritdoc
     */
    public function up()
    {
        /* database */
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%core_setting}}', [
            'key' => Schema::TYPE_STRING . ' NOT NULL',
            'value' => Schema::TYPE_TEXT . ' NULL DEFAULT NULL',
            'description' => Schema::TYPE_STRING . ' NOT NULL',
            'group' => Schema::TYPE_STRING . ' NOT NULL',
        ], $tableOptions);
        $this->addPrimaryKey('pk', '{{%core_setting}}', 'key');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%core_setting}}');
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
