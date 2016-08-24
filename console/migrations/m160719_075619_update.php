<?php

use yii\db\Migration;

/**
 * Class m160719_075619_update
 */
class m160719_075619_update extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->renameColumn('{{%core_account}}', 'username', 'fullname');
        $this->renameColumn('{{%core_account}}', 'username_changed', 'fullname_changed');
        $this->addColumn('{{%core_account}}', 'email_verified', $this->boolean()->null()->defaultValue(0)->after('email')); //

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%core_account}}', 'email_verified');
        $this->renameColumn('{{%core_account}}', 'fullname', 'username');
        $this->renameColumn('{{%core_account}}', 'fullname_changed', 'username_changed');
    }

}
