<?php

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

        $this->migrate();
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

    /**
     * migrate data
     */
    protected function migrate(){
        /* rule */
        $rules=(new yii\db\Query())->select('*')->from('{{%core_auth_rule}}')->all();
        /* @var $authRule \yii\mongodb\Collection */
        $authRule=Yii::$app->mongodb->getCollection('auth_rule');
        foreach($rules as $rule){
            $authRule->insert([
                'name'=>$rule['name'],
                'data'=>$rule['data'],
                'created_at'=>(integer)$rule['created_at'],
                'updated_at'=>(integer)$rule['updated_at'],
            ]);
        }
        /* item */
        $items=(new yii\db\Query())->select('*')->from('{{%core_auth_item}}')->all();
        /* @var $authRule \yii\mongodb\Collection */
        $authItem=Yii::$app->mongodb->getCollection('auth_item');
        foreach($items as $item){
            $authItem->insert([
                'name'=>$item['name'],
                'type'=>(integer)$item['type'],
                'description'=>$item['description'],
                'rule_name'=>$item['rule_name'],
                'data'=>$item['data'],
                'created_at'=>(integer)$rule['created_at'],
                'updated_at'=>(integer)$rule['updated_at'],
            ]);
        }

        /* child item */
        $childItems=(new yii\db\Query())->select('*')->from('{{%core_auth_item_child}}')->all();
        $auth = Yii::$app->authManager;
        foreach ($childItems as $row){
            $parentType=(new yii\db\Query())->select('type')->from('{{%core_auth_item}}')->where(['name'=>$row['parent']])->scalar();
            if($parentType==yii\rbac\Item::TYPE_ROLE){
                $parent=$auth->getRole($row['parent']);
            }
            else {
                $parent=$auth->getPermission($row['parent']);
            }

            $childType=(new yii\db\Query())->select('type')->from('{{%core_auth_item}}')->where(['name'=>$row['child']])->scalar();
            if($childType==yii\rbac\Item::TYPE_ROLE){
                $child=$auth->getRole($row['child']);
            }
            else {
                $child=$auth->getPermission($row['child']);
            }

            $auth->addChild($parent, $child);
        }

        /* auth_assignment */
        $assignment=(new yii\db\Query())->select('*')->from('{{%core_auth_assignment}}')->all();
        foreach($assignment as $row){
            $itemType=(new yii\db\Query())->select('type')->from('{{%core_auth_item}}')->where(['name'=>$row['item_name']])->scalar();
            if($itemType==yii\rbac\Item::TYPE_ROLE){
                $item=$auth->getRole($row['item_name']);
            }
            else {
                $item=$auth->getPermission($row['item_name']);
            }
            $auth->assign($item, $row['user_id']);
        }
    }
}
