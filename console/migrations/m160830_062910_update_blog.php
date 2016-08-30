<?php

use yii\db\Migration;

/**
 * Class m160830_062910_update_blog
 */
class m160830_062910_update_blog extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{core_blog}}', 'views', $this->integer()->notNull()->defaultValue(0)->after('created_by'));
        $this->alterColumn('{{core_blog}}', 'slug', $this->string()->notNull()->unique());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{core_blog}}', 'views');
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
