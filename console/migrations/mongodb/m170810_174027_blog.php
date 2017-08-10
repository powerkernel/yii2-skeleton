<?php

/**
 * Class m170810_174027_blog
 */
class m170810_174027_blog extends \yii\mongodb\Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $col=Yii::$app->mongodb->getCollection('blog');
        $col->createIndexes([
            [
                'key'=>['slug'],
                'unique'=>true,
            ]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $col=Yii::$app->mongodb->getCollection('blog');
        $col->drop();
    }
}
