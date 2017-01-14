<?php

use yii\db\Migration;

/**
 * Class m161207_043755_update_rbac
 */
class m161207_043755_update_rbac extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $auth = Yii::$app->authManager;
        $rule=$auth->getRule('isOwner');
        /* view own item */
        $viewOwnItem = $auth->createPermission('viewOwnItem');
        $viewOwnItem->description = 'View own item';
        $viewOwnItem->ruleName = $rule->name;
        $auth->add($viewOwnItem);
        /* update own item */
        $updateOwnItem=$auth->getPermission('updateOwnItem');
        /* member */
        $member = $auth->createRole('member');
        $member->description='Default user';
        /* author */
        $author = $auth->getRole('author');
        $auth->add($member);
        $auth->addChild($member, $viewOwnItem);
        $auth->addChild($member, $updateOwnItem);
        $auth->addChild($author, $member);
        $auth->removeChild($author, $updateOwnItem);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
//        $auth = Yii::$app->authManager;
//        $viewOwnItem=$auth->getPermission('viewOwnItem');
//        $auth->remove($viewOwnItem);
        return false;
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
