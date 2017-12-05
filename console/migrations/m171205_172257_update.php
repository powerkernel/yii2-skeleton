<?php

use yii\db\Migration;

/**
 * Class m171205_172257_update
 */
class m171205_172257_update extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%core_account}}', 'phone', $this->string(15)->null()->defaultValue(null)->after('change_email_token'));
        $this->addColumn('{{%core_account}}', 'phone_verified', $this->integer(1)->notNull()->defaultValue(0)->after('phone'));
        $this->addColumn('{{%core_account}}', 'new_phone', $this->string(15)->null()->defaultValue(null)->after('phone_verified'));
        $this->addColumn('{{%core_account}}', 'new_phone_code', $this->string(15)->null()->defaultValue(null)->after('new_phone'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m171205_172257_update cannot be reverted.\n";
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171205_172257_update cannot be reverted.\n";

        return false;
    }
    */
}
