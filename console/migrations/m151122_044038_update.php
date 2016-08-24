<?php

use yii\db\Migration;

/**
 * Class m151122_044038_update
 */
class m151122_044038_update extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{core_account}}', 'language', $this->string(5)->notNull()->defaultValue('en-US')->after('role'));
        $this->addColumn('{{core_account}}', 'timezone', $this->string(100)->notNull()->defaultValue('Asia/Ho_Chi_Minh')->after('language'));
        $this->addColumn('{{core_account}}', 'username_changed', $this->smallInteger()->notNull()->defaultValue(0)->after('username'));
    }

    /**
     * @inheritdoc
     * @return bool
     */
    public function down()
    {
        $this->dropColumn('{{core_account}}', 'username_changed');
        $this->dropColumn('{{core_account}}', 'timezone');
        $this->dropColumn('{{core_account}}', 'language');
        return true;
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
