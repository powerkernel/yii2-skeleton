<?php

use yii\db\Migration;

/**
 * Class m170827_092027_update_page_id
 */
class m170827_092027_update_page_id extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->renameColumn('{{%core_page_id}}', 'id', 'slug');
        $this->renameColumn('{{%core_page_data}}', 'id_page', 'slug');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->renameColumn('{{%core_page_data}}', 'slug', 'id_page');
        $this->renameColumn('{{%core_page_id}}', 'slug', 'id');

    }

}
