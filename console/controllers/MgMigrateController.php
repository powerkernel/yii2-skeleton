<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2017 Modern Kernel
 */


namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\db\Query;
use yii\rbac\Item;

/**
 * Class MgMigrateController
 * @package console\controllers
 */
class MgMigrateController extends Controller
{
    /**
     * action index
     * run this first: yii mongodb-migrate --migrationPath=console/migrations/mongodb
     * then run: php yii mg-migrate
     */
    public function actionIndex(){
        $this->migrateRbac();
        $this->migrateTasks();
    }

    /**
     * rbac
     */
    protected function migrateRbac(){
        echo "Migrating RBAC...\n";

        /* rule */
        $rules=(new Query())->select('*')->from('{{%core_auth_rule}}')->all();
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
        $items=(new Query())->select('*')->from('{{%core_auth_item}}')->all();
        /* @var $authRule \yii\mongodb\Collection */
        $authItem=Yii::$app->mongodb->getCollection('auth_item');
        foreach($items as $item){
            $authItem->insert([
                'name'=>$item['name'],
                'type'=>(integer)$item['type'],
                'description'=>$item['description'],
                'rule_name'=>$item['rule_name'],
                'data'=>$item['data'],
                'created_at'=>(integer)$item['created_at'],
                'updated_at'=>(integer)$item['updated_at'],
            ]);
        }

        /* child item */
        $childItems=(new Query())->select('*')->from('{{%core_auth_item_child}}')->all();
        $auth = Yii::$app->authManager;
        foreach ($childItems as $row){
            $parentType=(new Query())->select('type')->from('{{%core_auth_item}}')->where(['name'=>$row['parent']])->scalar();
            if($parentType== Item::TYPE_ROLE){
                $parent=$auth->getRole($row['parent']);
            }
            else {
                $parent=$auth->getPermission($row['parent']);
            }

            $childType=(new Query())->select('type')->from('{{%core_auth_item}}')->where(['name'=>$row['child']])->scalar();
            if($childType== Item::TYPE_ROLE){
                $child=$auth->getRole($row['child']);
            }
            else {
                $child=$auth->getPermission($row['child']);
            }

            $auth->addChild($parent, $child);
        }

        /* auth_assignment */
        $assignment=(new Query())->select('*')->from('{{%core_auth_assignment}}')->all();
        foreach($assignment as $row){
            $itemType=(new Query())->select('type')->from('{{%core_auth_item}}')->where(['name'=>$row['item_name']])->scalar();
            if($itemType== Item::TYPE_ROLE){
                $item=$auth->getRole($row['item_name']);
            }
            else {
                $item=$auth->getPermission($row['item_name']);
            }
            $auth->assign($item, $row['user_id']);
        }

        echo "RBAC migration completed.\n";
    }

    /**
     * task log
     */
    protected function migrateTasks(){
        echo "Migrating Task log...\n";

        $logs=(new Query())->select('*')->from('{{%core_task_logs}}')->all();
        $collection = Yii::$app->mongodb->getCollection('task_logs');
        foreach($logs as $log){
            $collection->insert([
                'task' => $log['task'],
                'result' => $log['result'],
                'created_at' => (integer)$log['created_at'],
                'updated_at' => (integer)$log['updated_at'],
            ]);
        }
        Yii::$app->db->createCommand()->truncateTable('{{%core_task_logs}}')->execute();

        echo "Task log migration completed.\n";
    }
}