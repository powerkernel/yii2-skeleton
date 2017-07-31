<?php

/**
 * Class m170630_035059_rbac
 */
class m170630_035059_rbac extends \yii\mongodb\Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $authItem=Yii::$app->mongodb->getCollection('auth_item');
        $authItem->createIndexes([
            [
                'key'=>['name'],
                'unique'=>true,
            ]
        ]);

        $authRule=Yii::$app->mongodb->getCollection('auth_rule');
        $authRule->createIndexes([
            [
                'key'=>['name'],
                'unique'=>true,
            ]
        ]);

        $authAssignment=Yii::$app->mongodb->getCollection('auth_assignment');
        $authAssignment->createIndexes([
            [
                'key'=>['user_id', 'item_name'],
                'unique'=>true,
            ]
        ]);

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        /* @var $authItem \yii\mongodb\Collection */
        $authItem=Yii::$app->mongodb->getCollection('auth_item');
        $authItem->drop();
        $authRule=Yii::$app->mongodb->getCollection('auth_rule');
        $authRule->drop();
        $authAssignment=Yii::$app->mongodb->getCollection('auth_assignment');
        $authAssignment->drop();
    }

}
