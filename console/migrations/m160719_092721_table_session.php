<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m160719_092721_table_session
 */
class m160719_092721_table_session extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%core_session}}', [
            'id' => 'CHAR(64) NOT NULL PRIMARY KEY',
            'expire' => Schema::TYPE_INTEGER.' NOT NULL',
            'data' => Schema::TYPE_BINARY.' NOT NULL',
        ], $tableOptions);

        $this->createIndex('expire', '{{%core_session}}', 'expire');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%core_session}}');
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
