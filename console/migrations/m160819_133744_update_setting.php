<?php

use yii\db\Migration;

/**
 * Class m160819_133744_update_setting
 */
class m160819_133744_update_setting extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{core_setting}}', 'key_order', $this->integer()->notNull()->defaultValue(0));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('{{core_setting}}', 'key_order');
    }
}
