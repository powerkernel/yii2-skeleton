<?php

use yii\db\Migration;

/**
 * Class m170211_134857_tbl_banner
 */
class m170211_134857_tbl_banner extends Migration
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
        $this->createTable('{{%core_banner}}', [
            'id' => $this->primaryKey(),
            'lang'=>$this->string(5)->null()->defaultValue(null),
            'title' => $this->string()->notNull(),

            'text_content' => $this->text()->null(),
            'text_style' => $this->string()->null(),

            'banner_url' => $this->string()->notNull(),
            'link_url' => $this->string()->null(),
            'link_option'=>$this->string()->null(),

            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%core_banner}}');
    }

}
