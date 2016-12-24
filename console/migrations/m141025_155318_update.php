<?php

use yii\db\Migration;

/**
 * Class m141025_155318_update
 */
class m141025_155318_update extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createIndex('user_email', '{{%core_account}}', 'email', true);
        $this->createIndex('user_password_reset_token', '{{%core_account}}', 'password_reset_token', true);
        $this->addColumn('{{%core_account}}', 'change_email_token', $this->string()->after('email'));
        $this->createIndex('user_change_email_token', '{{%core_account}}', 'change_email_token', true);
        $this->addColumn('{{%core_account}}', 'new_email', $this->string()->after('email'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%core_account}}', 'new_email');
        $this->dropIndex('user_change_email_token', '{{%core_account}}');
        $this->dropColumn('{{%core_account}}', 'change_email_token');
        $this->dropIndex('user_password_reset_token', '{{%core_account}}');
        $this->dropIndex('user_email', '{{%core_account}}');
    }
}
