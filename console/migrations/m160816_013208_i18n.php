<?php

use yii\db\Migration;

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
            'id' => $this->primaryKey(),
            'category' => $this->string(32)->notNull(),
            'message' => $this->text()->notNull(),
        ], $tableOptions);
        //$this->createIndex('idx_message', '{{%icore_source_message}}', ['message']);

        $this->createTable('{{%core_message}}', [
            'id' => $this->integer()->notNull(),
            'language' => $this->string(16)->notNull(),
            'translation' => $this->text()->notNull(),
            'is_translated' => $this->boolean()->notNull()->defaultValue(0),
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
