<?php

use yii\db\Migration;

/**
 * Class m160818_132736_update_setting
 */
class m160818_132736_update_setting extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%core_setting}}', 'title', $this->string()->after('value'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('{{%core_setting}}', 'title');
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
