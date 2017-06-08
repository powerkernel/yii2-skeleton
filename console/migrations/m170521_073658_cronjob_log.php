<?php

use yii\db\Migration;

/**
 * Class m170521_073658_cronjob_log
 */
class m170521_073658_cronjob_log extends Migration
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
        $this->createTable('{{%core_task_logs}}', [
            'id' => $this->primaryKey(),
            'task'=>$this->string()->notNull(),
            'result' => $this->text()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->createIndex('idx_task', '{{%core_task_logs}}', ['task']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%core_task_logs}}');
    }


}
