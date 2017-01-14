<?php

use yii\db\Migration;

/**
 * Class m160814_141426_update_setting
 */
class m160814_141426_update_setting extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        // type, data, default, rules
        $this->addColumn('{{%core_setting}}', 'type', $this->string()->notNull()->defaultValue('textInput'));
        $this->addColumn('{{%core_setting}}', 'data', $this->text());
        $this->addColumn('{{%core_setting}}', 'default', $this->string());
        $this->addColumn('{{%core_setting}}', 'rules', $this->text());

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%core_setting}}', 'rules');
        $this->dropColumn('{{%core_setting}}', 'default');
        $this->dropColumn('{{%core_setting}}', 'data');
        $this->dropColumn('{{%core_setting}}', 'type');
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
