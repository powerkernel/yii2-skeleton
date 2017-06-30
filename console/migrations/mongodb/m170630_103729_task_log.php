<?php

use yii\db\Query;

class m170630_103729_task_log extends \yii\mongodb\Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $logs=(new Query())->select('*')->from('{{%core_task_logs}}')->all();
        $collection = Yii::$app->mongodb->getCollection('task_logs');
        foreach($logs as $log){
            $collection->insert([
                'task' => $log['task'],
                'result' => $log['result'],
                'created_at' => (integer)$log['created_at'],
                'updated_at' => (integer)$log['updated_at'],
            ]);
        }
        Yii::$app->db->createCommand()->truncateTable('{{%core_task_logs}}')->execute();
    }

    /**
     * @inheritdoc
     * @return bool
     */
    public function down()
    {
        echo "m170630_103729_task_log cannot be reverted.\n";
        return false;
    }
}
