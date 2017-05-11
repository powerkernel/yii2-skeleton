<?php

use yii\db\Migration;

/**
 * Class m170506_155518_update_blog
 */
class m170506_155518_update_blog extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%core_blog}}', 'language', $this->string(5)->notNull()->defaultValue(Yii::$app->language)->after('slug'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%core_blog}}', 'language');
    }

}
