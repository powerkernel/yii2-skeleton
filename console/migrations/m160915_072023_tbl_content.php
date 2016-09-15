<?php

use yii\db\Migration;

/**
 * Class m160915_072023_tbl_content
 */
class m160915_072023_tbl_content extends Migration
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
        $this->createTable('{{%core_content}}', [
            'id' => $this->string()->notNull(),
            'content' => $this->text(),
        ], $tableOptions);
        $this->addPrimaryKey('pk', '{{%core_content}}', ['id']);

        $this->addDefaultData();
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%core_content}}');
    }

    /**
     * @inheritdoc
     */
    protected function addDefaultData(){
        $c=new \common\models\Content();
        $c->id='Blog';
        $c->content=<<<EOB
<p>Welcome to my world!</b>
<p>Please drop by and say hello or leave a comment.</p>
EOB;
        $c->save();

    }

}
