<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m160816_013208_i18n
 */
class m160816_013208_i18n extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions='';
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%core_source_message}}', [
            'id' => Schema::TYPE_PK,
            'category' => Schema::TYPE_STRING . '(32) NOT NULL',
            'message' => Schema::TYPE_TEXT . ' NOT NULL',
        ], $tableOptions);
        //$this->createIndex('idx_message', '{{%icore_source_message}}', ['message']);

        $this->createTable('{{%core_message}}', [
            'id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'language' => Schema::TYPE_STRING . '(16) NOT NULL',
            'translation' => Schema::TYPE_TEXT . ' NOT NULL',
            'is_translated' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 0'
        ], $tableOptions);
        $this->addPrimaryKey('pk', '{{%core_message}}', ['id', 'language']);
        $this->addForeignKey('fk_message_source_message', '{{%core_message}}', 'id', '{{%core_source_message}}', 'id', 'CASCADE', 'RESTRICT');


        /* add role */
//        $auth = Yii::$app->authManager;
//        $translator = $auth->createRole('translator');
//        $translator->description=Yii::t('app', 'Only frontend access');
//        $auth->add($translator);
//        $staff = $auth->getRole('staff');
//        $auth->addChild($staff, $translator);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
//        $auth = Yii::$app->authManager;
//        $staff = $auth->getRole('staff');
//        $translator = $auth->getRole('translator');
//        $auth->removeChild($staff, $translator);
//        $auth->remove($translator);

        $this->dropForeignKey('fk_message_source_message', '{{%core_message}}');
        $this->dropTable('{{%core_message}}');
        $this->dropTable('{{%core_source_message}}');
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
