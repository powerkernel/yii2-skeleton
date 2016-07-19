<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Class m150119_083106_update
 */
class m150119_083106_update extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{core_account}}', 'seo_name', Schema::TYPE_STRING . ' NULL AFTER `id`');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{core_account}}', 'seo_name');
    }
}
