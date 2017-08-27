<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2017 Modern Kernel
 */


namespace console\controllers;

use MongoDB\BSON\UTCDateTime;
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
        echo "yii mg-migrate/rbac\n";
        echo "yii mg-migrate/tasks\n";
        echo "yii mg-migrate/blog\n";
    }

    /**
     * rbac
     */
    public function actionRbac(){
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

    public function actionPages(){
        echo "Migrating Page model...\n";
        /* PAGE ID */
        $rows=(new Query())->select('*')->from('{{%core_page_id}}')->all();
        $collection = Yii::$app->mongodb->getCollection('page_id');
        foreach($rows as $row){
            $collection->insert([
                'slug' => $row['slug'],
                'show_description' => $row['show_description'],
                'show_update_date' => $row['show_update_date'],
            ]);
        }
        /* Page DATA */
        $rows=(new Query())->select('*')->from('{{%core_page_data}}')->all();
        $collection = Yii::$app->mongodb->getCollection('page_data');
        foreach($rows as $row){
            $collection->insert([
                'slug' => $row['slug'],
                'language' => $row['language'],
                'title' => $row['title'],
                'description' => $row['description'],
                'content' => $row['content'],
                'keywords' => $row['keywords'],
                'thumbnail' => $row['thumbnail'],
                'status' => (int)$row['status'],
                'created_by' => (int)$row['created_by'],
                'updated_by' => (int)$row['updated_by'],
                'created_at' => new UTCDateTime($row['created_at']*1000),
                'updated_at' => new UTCDateTime($row['updated_at']*1000),
            ]);
        }
        echo "Page model migration completed.\n";
    }

    /**
     * migrate Setting model
     */
    public function actionSetting(){
        echo "Migrating Setting model...\n";
        $rows=(new Query())->select('*')->from('{{%core_setting}}')->all();
        $collection = Yii::$app->mongodb->getCollection('settings');
        foreach($rows as $row){
            $collection->insert([
                'key' => $row['key'],
                'value' => $row['value'],
                'title' => $row['title'],
                'description' => $row['description'],
                'group' => $row['group'],
                'type' => $row['type'],
                'data' => $row['data'],
                'default' => $row['default'],
                'rules' => $row['rules'],
                'key_order' => $row['key_order'],
            ]);
        }

        echo "Setting model migration completed.\n";
    }


    /**
     * migrate banner
     */
    public function actionBanner(){
        echo "Migrating Banner...\n";
        $rows=(new Query())->select('*')->from('{{%core_banner}}')->all();
        $collection = Yii::$app->mongodb->getCollection('banner');
        foreach($rows as $row){
            $collection->insert([
                'title' => $row['title'],
                'lang' => $row['lang'],
                'text_content' => $row['text_content'],
                'text_style' => $row['text_style'],
                'banner_url' => $row['banner_url'],
                'link_url' => $row['link_url'],
                'link_option' => $row['link_option'],
                'status' => $row['status'],
                'created_at' => new \MongoDB\BSON\UTCDateTime($row['created_at']*1000),
                'updated_at' => new \MongoDB\BSON\UTCDateTime($row['updated_at']*1000),
            ]);
        }

        echo "Banner migration completed.\n";
    }

    /**
     * task log
     */
    public function actionTasks(){
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
        //Yii::$app->db->createCommand()->truncateTable('{{%core_task_logs}}')->execute();

        echo "Task log migration completed.\n";
    }

    /**
     * blog
     */
    public function actionBlog(){
        echo "Migrating Blog...\n";

        $rows=(new Query())->select('*')->from('{{%core_blog}}')->all();
        $collection = Yii::$app->mongodb->getCollection('blog');
        foreach($rows as $row){
            $collection->insert([
                'slug' => $row['slug'],
                'language' => $row['language'],
                'title' => $row['title'],
                'desc' => $row['desc'],
                'content' => $row['content'],
                'tags' => $row['tags'],
                'thumbnail' => $row['thumbnail'],
                'thumbnail_square' => $row['thumbnail_square'],
                'image_object' => $row['image_object'],
                'created_by' => (integer)$row['created_by'],
                'views' => (integer)$row['views'],
                'status' => (integer)$row['status'],
                'created_at' => new UTCDateTime($row['created_at']*1000),
                'updated_at' => new UTCDateTime($row['updated_at']*1000),
                'published_at'=> new UTCDateTime($row['published_at']*1000),
            ]);
        }
        echo "Blog migration completed.\n";
    }
}