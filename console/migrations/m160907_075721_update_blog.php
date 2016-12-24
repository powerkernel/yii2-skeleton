<?php

use yii\db\Migration;

/**
 * Class m160907_075721_update_blog
 */
class m160907_075721_update_blog extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%core_blog}}', 'thumbnail_square', $this->string()->after('thumbnail'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%core_blog}}', 'thumbnail_square');
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
