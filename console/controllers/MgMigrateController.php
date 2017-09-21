<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */


namespace console\controllers;

use common\models\Account;
use common\models\Auth;
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
    public function actionIndex()
    {
    }

    public function actionMongoDateTask(){
        $logs=\common\models\TaskLog::find()->all();
        foreach ($logs as $log){
            $log->created_at=new UTCDateTime($log->created_at*1000);
            $log->updated_at=new UTCDateTime($log->updated_at*1000);
            $log->save();
        }
    }

    /**
     * rbac
     */
    public function actionRbac()
    {
        echo "Migrating RBAC...\n";

        /* rule */
        $rules = (new Query())->select('*')->from('{{%core_auth_rule}}')->all();
        /* @var $authRule \yii\mongodb\Collection */
        $authRule = Yii::$app->mongodb->getCollection('auth_rule');
        foreach ($rules as $rule) {
            $authRule->insert([
                'name' => $rule['name'],
                'data' => $rule['data'],
                'created_at' => new UTCDateTime($rule['created_at'] * 1000),
                'updated_at' => new UTCDateTime($rule['updated_at'] * 1000),
            ]);
        }
        /* item */
        $items = (new Query())->select('*')->from('{{%core_auth_item}}')->all();
        /* @var $authRule \yii\mongodb\Collection */
        $authItem = Yii::$app->mongodb->getCollection('auth_item');
        foreach ($items as $item) {
            $authItem->insert([
                'name' => $item['name'],
                'type' => (integer)$item['type'],
                'description' => $item['description'],
                'rule_name' => $item['rule_name'],
                'data' => $item['data'],
                'created_at' => new UTCDateTime($item['created_at'] * 1000),
                'updated_at' => new UTCDateTime($item['updated_at'] * 1000),
            ]);
        }

        /* child item */
        $childItems = (new Query())->select('*')->from('{{%core_auth_item_child}}')->all();
        $auth = Yii::$app->authManager;
        foreach ($childItems as $row) {
            $parentType = (new Query())->select('type')->from('{{%core_auth_item}}')->where(['name' => $row['parent']])->scalar();
            if ($parentType == Item::TYPE_ROLE) {
                $parent = $auth->getRole($row['parent']);
            } else {
                $parent = $auth->getPermission($row['parent']);
            }

            $childType = (new Query())->select('type')->from('{{%core_auth_item}}')->where(['name' => $row['child']])->scalar();
            if ($childType == Item::TYPE_ROLE) {
                $child = $auth->getRole($row['child']);
            } else {
                $child = $auth->getPermission($row['child']);
            }

            $auth->addChild($parent, $child);
        }

        /* auth_assignment */
        $assignment = (new Query())->select('*')->from('{{%core_auth_assignment}}')->all();
        foreach ($assignment as $row) {
            $itemType = (new Query())->select('type')->from('{{%core_auth_item}}')->where(['name' => $row['item_name']])->scalar();
            if ($itemType == Item::TYPE_ROLE) {
                $item = $auth->getRole($row['item_name']);
            } else {
                $item = $auth->getPermission($row['item_name']);
            }
            $auth->assign($item, $row['user_id']);
        }

        echo "RBAC migration completed.\n";
    }

    /**
     * migrate account
     */
    public function actionAccount()
    {
        echo "Migrating Account model...\n";
        $rows = (new Query())->select('*')->from('{{%core_account}}')->all();
        $collection = Yii::$app->mongodb->getCollection('accounts');
        foreach ($rows as $row) {
            $collection->insert([
                'user_id' => (int)$row['id'],
                'seo_name' => $row['seo_name'],
                'fullname' => $row['fullname'],
                'fullname_changed' => (int)$row['fullname_changed'],
                'auth_key' => $row['auth_key'],
                'access_token' => $row['access_token'],
                'password_hash' => $row['password_hash'],
                'password_reset_token' => $row['password_reset_token'],
                'email' => $row['email'],
                'email_verified' => (int)$row['email_verified'],
                'new_email' => $row['new_email'],
                'change_email_token' => $row['change_email_token'],
                'role' => (int)$row['role'],
                'language' => $row['language'],
                'timezone' => $row['timezone'],
                'status' => $row['status'],
                'created_at' => new UTCDateTime($row['created_at'] * 1000),
                'updated_at' => new UTCDateTime($row['updated_at'] * 1000),
            ]);
        }
        echo "Account model migration completed.\n";
    }

    /**
     * Migrate auth
     */
    public function actionAuth()
    {
        echo "Migrating Auth model...\n";
        $rows = (new Query())->select('*')->from('{{%core_auth}}')->all();
        $collection = Yii::$app->mongodb->getCollection('auth');
        foreach ($rows as $row) {
            $collection->insert([
                'user_id' => (int)$row['user_id'],
                'source' => $row['source'],
                'source_id' => $row['source_id'],
            ]);
        }
        echo "Auth model migration completed.\n";
    }

    /**
     * migrate page
     */
    public function actionPages()
    {
        echo "Migrating Page model...\n";
        /* PAGE ID */
        $rows = (new Query())->select('*')->from('{{%core_page_id}}')->all();
        $collection = Yii::$app->mongodb->getCollection('page_id');
        foreach ($rows as $row) {
            $collection->insert([
                'slug' => $row['slug'],
                'show_description' => (int)$row['show_description'],
                'show_update_date' => (int)$row['show_update_date'],
            ]);
        }
        /* Page DATA */
        $rows = (new Query())->select('*')->from('{{%core_page_data}}')->all();
        $collection = Yii::$app->mongodb->getCollection('page_data');
        foreach ($rows as $row) {
            $collection->insert([
                'slug' => $row['slug'],
                'language' => $row['language'],
                'title' => $row['title'],
                'description' => $row['description'],
                'content' => $row['content'],
                'keywords' => $row['keywords'],
                'thumbnail' => $row['thumbnail'],
                'status' => $row['status'],
                'created_by' => (int)$row['created_by'],
                'updated_by' => (int)$row['updated_by'],
                'created_at' => new UTCDateTime($row['created_at'] * 1000),
                'updated_at' => new UTCDateTime($row['updated_at'] * 1000),
            ]);
        }
        echo "Page model migration completed.\n";
    }

    /**
     * migrate Setting model
     */
    public function actionSetting()
    {
        echo "Migrating Setting model...\n";
        $rows = (new Query())->select('*')->from('{{%core_setting}}')->all();
        $collection = Yii::$app->mongodb->getCollection('settings');
        $collection->remove();
        foreach ($rows as $row) {
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
                'key_order' => (int)$row['key_order'],
            ]);
        }

        echo "Setting model migration completed.\n";
    }


    /**
     * migrate banner
     */
    public function actionBanner()
    {
        echo "Migrating Banner...\n";
        $rows = (new Query())->select('*')->from('{{%core_banner}}')->all();
        $collection = Yii::$app->mongodb->getCollection('banner');
        foreach ($rows as $row) {
            $collection->insert([
                'title' => $row['title'],
                'lang' => $row['lang'],
                'text_content' => $row['text_content'],
                'text_style' => $row['text_style'],
                'banner_url' => $row['banner_url'],
                'link_url' => $row['link_url'],
                'link_option' => $row['link_option'],
                'status' => $row['status'],
                'created_at' => new \MongoDB\BSON\UTCDateTime($row['created_at'] * 1000),
                'updated_at' => new \MongoDB\BSON\UTCDateTime($row['updated_at'] * 1000),
            ]);
        }

        echo "Banner migration completed.\n";
    }

    /**
     * task log
     */
    public function actionTasks()
    {
        echo "Migrating Task log...\n";

        $logs = (new Query())->select('*')->from('{{%core_task_logs}}')->all();
        $collection = Yii::$app->mongodb->getCollection('task_logs');
        foreach ($logs as $log) {
            $collection->insert([
                'task' => $log['task'],
                'result' => $log['result'],
                'created_at' => new UTCDateTime($log['created_at']*1000),
                'updated_at' => new UTCDateTime($log['updated_at']*1000),
            ]);
        }
        //Yii::$app->db->createCommand()->truncateTable('{{%core_task_logs}}')->execute();

        echo "Task log migration completed.\n";
    }

    /**
     * blog
     */
    public function actionBlog()
    {
        echo "Migrating Blog...\n";

        $rows = (new Query())->select('*')->from('{{%core_blog}}')->all();
        $collection = Yii::$app->mongodb->getCollection('blog');
        foreach ($rows as $row) {
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
                'status' => $row['status'],
                'created_at' => new UTCDateTime($row['created_at'] * 1000),
                'updated_at' => new UTCDateTime($row['updated_at'] * 1000),
                'published_at' => new UTCDateTime($row['published_at'] * 1000),
            ]);
        }
        echo "Blog migration completed.\n";
    }

    /**
     * migrate menu
     */
    public function actionMenu()
    {
        echo "Migrating Menu...\n";
        $rows = (new Query())->select('*')->from('{{%core_menu}}')->all();
        $collection = Yii::$app->mongodb->getCollection('menu');
        foreach ($rows as $row) {
            $collection->insert([
                'id_parent' => null,
                'label' => $row['label'],
                'active_route' => $row['active_route'],
                'url' => $row['url'],
                'class' => $row['class'],
                'position' => $row['position'],
                'order' => (int)$row['order'],
                'status' => $row['status'],
                'created_at' => new UTCDateTime($row['created_at'] * 1000),
                'updated_at' => new UTCDateTime($row['updated_at'] * 1000),
                'created_by' => (int)$row['created_by'],
                'updated_by' => (int)$row['updated_by'],
            ]);
        }
        echo "Menu migration completed.\n";
    }

    /**
     * update auth user id
     */
    public function actionAuthId()
    {
        echo "Updating Auth user id...\n";
        if (Yii::$app->params['mongodb']['auth']) {
            $auths = Auth::find()->all();
            foreach ($auths as $auth) {
                $id = $this->getUserId($auth->user_id);
                if (!empty($id)) {
                    $auth->user_id = $id;
                    if (!$auth->save()) {
                        echo "Error\n";
                    }
                } else {
                    echo "No user ID.\n";
                }
            }
        }
        echo "Auth user id updated.\n";
    }

    /**
     * update Assignment user id
     */
    public function actionAssignmentId()
    {
        echo "Updating Assignment user ID...\n";
        $rows = (new \yii\mongodb\Query())->select(['user_id'])->from('auth_assignment')->distinct('user_id');
        foreach ($rows as $row) {
            $id = $this->getUserId((int)$row);
            if ($id) {
                Yii::$app->mongodb->createCommand()
                    ->update('auth_assignment', ['user_id' => $row], ['user_id' => $id]);
            }
        }
        echo "Assignment user updated.\n";
    }

    /**
     * update page data user id
     */
    public function actionPageId()
    {
        echo "Updating Page Data user id....\n";
        // created by
        $rows = (new \yii\mongodb\Query())->select(['created_by'])->from('page_data')->distinct('created_by');
        foreach ($rows as $row) {
            $id = $this->getUserId((int)$row);
            if ($id) {
                Yii::$app->mongodb->createCommand()
                    ->update('page_data', ['created_by' => $row], ['created_by' => $id]);
            }
        }
        // updated by
        $rows = (new \yii\mongodb\Query())->select(['updated_by'])->from('page_data')->distinct('updated_by');
        foreach ($rows as $row) {
            $id = $this->getUserId((int)$row);
            if ($id) {
                Yii::$app->mongodb->createCommand()
                    ->update('page_data', ['updated_by' => $row], ['updated_by' => $id]);
            }
        }
        echo "Page Data user id updated.\n";
    }

    /**
     * update blog user id
     */
    public function actionBlogId()
    {
        echo "Updating Blog user id...\n";
        $rows = (new \yii\mongodb\Query())->select(['created_by'])->from('blog')->distinct('created_by');
        foreach ($rows as $row) {
            $id = $this->getUserId((int)$row);
            if ($id) {
                Yii::$app->mongodb->createCommand()
                    ->update('blog', ['created_by' => $row], ['created_by' => $id]);
            }
        }
        echo "Blog user id updated.\n";
    }

    /**
     * get mongo user id
     * @param integer $id
     * @return string|null
     */
    protected function getUserId($id)
    {
        $a = Account::find()->where(['user_id' => $id])->one();
        if ($a) {
            return (string)$a->_id;
        }
        return null;
    }
}