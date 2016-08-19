<?php

use yii\db\Migration;

/**
 * Class m160818_143135_auth
 */
class m160818_143135_auth extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%core_auth}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'source' => $this->string()->notNull(),
            'source_id' => $this->string()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('fk-auth-user_id-account-id', '{{%core_auth}}', 'user_id', '{{%core_account}}', 'id', 'CASCADE', 'CASCADE');
    }


    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-auth-user_id-account-id', '{{%core_auth}}');
        $this->dropTable('{{%core_auth}}');
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
