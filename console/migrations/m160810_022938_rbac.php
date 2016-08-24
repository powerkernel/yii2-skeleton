<?php

use yii\db\Migration;

/**
 * Class m160810_022938_rbac
 */
class m160810_022938_rbac extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {

        /* database */
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%core_auth_rule}}', [
            'name' => 'VARCHAR(64)',
            'data' => $this->text()->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->addPrimaryKey('pk', '{{%core_auth_rule}}', 'name');

        $this->createTable('{{%core_auth_item}}', [
            'name' => 'VARCHAR(64)',
            'type' => $this->integer()->notNull(),
            'description' => $this->text(),
            'rule_name' => 'VARCHAR(64) NULL DEFAULT NULL',
            'data' => $this->text(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->addPrimaryKey('pk', '{{%core_auth_item}}', 'name');
        $this->addForeignKey('fk_item_rule', '{{%core_auth_item}}', 'rule_name', '{{%core_auth_rule}}', 'name', 'SET NULL', 'CASCADE');


        $this->createTable('{{%core_auth_item_child}}', [
            'parent' => 'VARCHAR(64) NOT NULL',
            'child' => 'VARCHAR(64) NOT NULL',
        ], $tableOptions);
        $this->addPrimaryKey('pk', '{{%core_auth_item_child}}', ['parent', 'child']);
        $this->addForeignKey('fk_parent_item', '{{%core_auth_item_child}}', 'parent', '{{%core_auth_item}}', 'name', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_child_item', '{{%core_auth_item_child}}', 'child', '{{%core_auth_item}}', 'name', 'CASCADE', 'CASCADE');

        $this->createTable('{{%core_auth_assignment}}', [
            'item_name' => 'VARCHAR(64) NOT NULL',
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->addPrimaryKey('pk', '{{%core_auth_assignment}}', ['item_name', 'user_id']);
        $this->addForeignKey('fk_item_name', '{{%core_auth_assignment}}', 'item_name', '{{%core_auth_item}}', 'name', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_user_id', '{{%core_auth_assignment}}', 'user_id', '{{%core_account}}', 'id', 'CASCADE', 'CASCADE');

        /* authManager */
        $auth = Yii::$app->authManager;
        $staff = $auth->createRole('staff');
        $staff->description=Yii::t('app', 'Only frontend access');

        $auth->add($staff);
        $admin = $auth->createRole('admin');
        $admin->description=Yii::t('app', 'Full access frontend and backend');
        $auth->add($admin);
        $auth->addChild($admin, $staff);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        /* authManager */
        $auth = Yii::$app->authManager;
        $admin = $auth->getRole('admin');
        $staff = $auth->getRole('staff');
        $auth->removeChild($admin, $staff);
        $auth->remove($staff);
        $auth->remove($admin);

        /* database */
        $this->dropForeignKey('fk_user_id', '{{%core_auth_assignment}}');
        $this->dropForeignKey('fk_item_name', '{{%core_auth_assignment}}');
        $this->dropTable('{{%core_auth_assignment}}');

        $this->dropForeignKey('fk_child_item', '{{%core_auth_item_child}}');
        $this->dropForeignKey('fk_parent_item', '{{%core_auth_item_child}}');
        $this->dropTable('{{%core_auth_item_child}}');

        $this->dropForeignKey('fk_item_rule', '{{%core_auth_item}}');
        $this->dropTable('{{%core_auth_item}}');
        $this->dropTable('{{%core_auth_rule}}');


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
