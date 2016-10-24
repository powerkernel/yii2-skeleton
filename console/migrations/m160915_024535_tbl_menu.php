<?php

use yii\db\Migration;

/**
 * Class m160915_024535_tbl_menu
 */
class m160915_024535_tbl_menu extends Migration
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

        $this->createTable('{{%core_menu}}', [
            'id' => $this->primaryKey(),
            'label' => $this->string()->notNull(),
            'url' => $this->string()->notNull(),
            'class' => $this->string(),
            'position' => $this->string()->notNull(),
            'order' => $this->integer()->notNull()->defaultValue(0),

            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey('pk_menu_created_by-account_id', '{{%core_menu}}', 'created_by', '{{%core_account}}', 'id', 'SET NULL', 'SET NULL');
        $this->addForeignKey('pk_menu_updated_by-account_id', '{{%core_menu}}', 'updated_by', '{{%core_account}}', 'id', 'SET NULL', 'SET NULL');

        
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('pk_menu_updated_by-account_id', '{{%core_menu}}');
        $this->dropForeignKey('pk_menu_created_by-account_id', '{{%core_menu}}');
        $this->dropTable('{{%core_menu}}');
    }


    
}
