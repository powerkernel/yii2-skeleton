<?php

use yii\db\Migration;

/**
 * Class m160827_044350_blog
 */
class m160827_044350_blog extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%core_blog}}', [
            'id' => $this->primaryKey(),
            'slug' => $this->string()->null(),
            'title'=>$this->string()->notNull(),
            'desc'=>$this->string()->notNull(),
            'content'=>$this->text()->notNull(),
            'tags'=>$this->string()->notNull(),
            'created_by'=>$this->integer()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('fk-blog_created_by-account_id', '{{%core_blog}}', 'created_by', '{{%core_account}}', 'id', 'CASCADE', 'CASCADE');

        /* RBAC */
        $auth = Yii::$app->authManager;

        // add "createBlog" permission
        $createBlog = $auth->createPermission('createBlog');
        $createBlog->description = 'Create a blog';
        $auth->add($createBlog);

        // add "updatePost" permission
        $updateBlog = $auth->createPermission('updateBlog');
        $updateBlog->description = 'Update blog';
        $auth->add($updateBlog);

        // add "author" role and give this role the "createBlog" permission
        $author = $auth->createRole('author');
        $author->description='Can write and update their blog';
        $auth->add($author);
        $auth->addChild($author, $createBlog);

        // give admin role the "updateBlog" permission
        // as well as the permissions of the "author" role
        $staff = $auth->getRole('staff');
        $auth->addChild($staff, $updateBlog);
        $auth->addChild($staff, $author);

        // add the rule
        $rule = new \common\components\OwnerRule();
        $auth->add($rule);

        // add the "updateOwnItem" permission and associate the rule with it.
        $updateOwnItem = $auth->createPermission('updateOwnItem');
        $updateOwnItem->description = 'Update own item';
        $updateOwnItem->ruleName = $rule->name;
        $auth->add($updateOwnItem);

        // "$updateOwnItem" will be used from "updateBlog"
        $auth->addChild($updateOwnItem, $updateBlog);

        // allow "author" to update their own blog
        $auth->addChild($author, $updateOwnItem);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        /* RBAC */
        $auth = Yii::$app->authManager;
        $updateOwnItem=$auth->getPermission('updateOwnItem');
        $author=$auth->getRole('author');
        $staff=$auth->getRole('staff');
        $updateBlog=$auth->getPermission('updateBlog');
        $createBlog=$auth->getPermission('createBlog');


        $auth->removeChild($author, $updateOwnItem);
        $auth->removeChild($updateOwnItem, $updateBlog);
        $auth->removeChild($staff, $updateBlog);
        $auth->removeChild($staff, $author);
        $auth->removeChild($author, $createBlog);

        $auth->remove($updateOwnItem);
        $auth->remove($author);
        $auth->remove($updateBlog);
        $auth->remove($createBlog);

        $rule=$auth->getRule('isOwner');
        $auth->remove($rule);
        /* SQL */
        $this->dropForeignKey('fk-blog_created_by-account_id', '{{%core_blog}}');
        $this->dropTable('{{%core_blog}}');
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
