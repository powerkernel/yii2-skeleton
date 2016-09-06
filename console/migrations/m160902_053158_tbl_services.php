<?php

use yii\db\Migration;

/**
 * Class m160902_053158_tbl_services
 */
class m160902_053158_tbl_services extends Migration
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

        $this->createTable('{{%core_services}}', [
            'name' => $this->string()->notNull(),
            'title' => $this->string()->notNull(),

            'token' => $this->text(),
            'data' => $this->text(),

            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('pk', '{{%core_services}}', ['name']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%core_services}}');
    }


}
