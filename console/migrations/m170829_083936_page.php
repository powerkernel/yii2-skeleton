<?php

/**
 * Class m170829_083936_page
 */
class m170829_083936_page extends \yii\mongodb\Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        // page_id
        $col=Yii::$app->mongodb->getCollection('page_id');
        $col->createIndexes([
            [
                'key'=>['slug'],
                'unique'=>true,
                'name'=>'slug_u'
            ]
        ]);
        // page_data
        $col=Yii::$app->mongodb->getCollection('page_data');
        $col->createIndexes([
            [
                'key'=>['slug', 'language'],
                'unique'=>true,
                'name'=>'slug_language_u'
            ]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // page_data
        $col=Yii::$app->mongodb->getCollection('page_data');
        $col->dropIndexes('slug_language_u');
        // page_id
        $col=Yii::$app->mongodb->getCollection('page_id');
        $col->dropIndexes('slug_u');
    }
}
