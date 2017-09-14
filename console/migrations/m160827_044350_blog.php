<?php

use yii\db\Migration;

/**
 * Class m160827_044350_blog
 */
class m160827_044350_blog extends Migration
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

        $this->createTable('{{%core_blog}}', [
            'id' => $this->primaryKey(),
            'slug' => $this->string()->null(),
            'title'=>$this->string()->notNull(),
            'desc'=>$this->string()->notNull(),
            'content'=>$this->text()->notNull(),
            'tags'=>$this->string()->notNull(),
            'thumbnail'=>$this->string(),
            'image_object'=>$this->text(),
            'created_by'=>$this->integer()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'published_at' => $this->integer()->null(),
        ], $tableOptions);

        $this->addForeignKey('fk-blog_created_by-account_id', '{{%core_blog}}', 'created_by', '{{%core_account}}', 'id', 'CASCADE', 'CASCADE');


    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        /* SQL */
        $this->dropForeignKey('fk-blog_created_by-account_id', '{{%core_blog}}');
        $this->dropTable('{{%core_blog}}');
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
