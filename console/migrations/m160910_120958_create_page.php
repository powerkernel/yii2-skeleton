<?php

use yii\db\Migration;

/**
 * Class m160910_120958_create_page
 */
class m160910_120958_create_page extends Migration
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

        $this->createTable('{{%core_page_id}}', [
            'id' => $this->string(100)->notNull(),
            'show_description' => $this->boolean()->notNull()->defaultValue(1),
            'show_update_date' => $this->boolean()->notNull()->defaultValue(1),
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->addPrimaryKey('pk', '{{%core_page_id}}', 'id');

        $this->createTable('{{%core_page_data}}', [
            'id_page' => $this->string(100)->notNull(),
            'language' => $this->string(5)->notNull(),

            'title' => $this->string()->notNull(),
            'description' => $this->string()->notNull(),
            'content' => $this->text()->notNull(),
            'keywords' => $this->string()->notNull(),
            'thumbnail'=> $this->string(),

            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->addPrimaryKey('pk', '{{%core_page_data}}', ['id_page', 'language']);
        $this->addForeignKey('fk_page_data_id_page-page_id_id', '{{%core_page_data}}', 'id_page', '{{%core_page_id}}', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_page_data_id_page-page_id_id', '{{%core_page_data}}');
        $this->dropTable('{{%core_page_data}}');
        $this->dropTable('{{%core_page_id}}');
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
