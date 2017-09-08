<?php

use yii\db\Migration;

/**
 * Class m170907_124237_status
 */
class m170907_124237_status extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        /* account */
        $this->alterColumn('{{%core_account}}', 'status', $this->string(50));
        $this->update('{{%core_account}}', ['status'=> \common\models\Account::STATUS_ACTIVE], ['status'=>'10']);
        $this->update('{{%core_account}}', ['status'=> \common\models\Account::STATUS_SUSPENDED], ['status'=>'20']);

        /* banner */
        $this->alterColumn('{{%core_banner}}', 'status', $this->string(50));
        $this->update('{{%core_banner}}', ['status'=> \common\models\Banner::STATUS_ACTIVE], ['status'=>'10']);
        $this->update('{{%core_banner}}', ['status'=> \common\models\Banner::STATUS_INACTIVE], ['status'=>'20']);

        /* blog */
        $this->alterColumn('{{%core_blog}}', 'status', $this->string(50));
        $this->update('{{%core_blog}}', ['status'=> \common\models\Blog::STATUS_PUBLISHED], ['status'=>'10']);
        $this->update('{{%core_blog}}', ['status'=> \common\models\Blog::STATUS_DRAFT], ['status'=>'20']);

        /* login */
        $this->alterColumn('{{%core_login}}', 'status', $this->string(50));
        $this->update('{{%core_login}}', ['status'=> \common\models\Login::STATUS_NEW], ['status'=>'10']);
        $this->update('{{%core_login}}', ['status'=> \common\models\Login::STATUS_USED], ['status'=>'20']);

        /* menu */
        $this->alterColumn('{{%core_menu}}', 'status', $this->string(50));
        $this->update('{{%core_menu}}', ['status'=> \common\models\Menu::STATUS_ACTIVE], ['status'=>'10']);
        $this->update('{{%core_menu}}', ['status'=> \common\models\Menu::STATUS_INACTIVE], ['status'=>'20']);

        /* page */
        $this->alterColumn('{{%core_page_data}}', 'status', $this->string(50));
        $this->update('{{%core_page_data}}', ['status'=> \common\models\PageData::STATUS_ACTIVE], ['status'=>'10']);
        $this->update('{{%core_page_data}}', ['status'=> \common\models\PageData::STATUS_INACTIVE], ['status'=>'20']);
    }


    /**
     * @return bool
     */
    public function safeDown()
    {
        echo "m170907_124237_status cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170907_124237_status cannot be reverted.\n";

        return false;
    }
    */
}
