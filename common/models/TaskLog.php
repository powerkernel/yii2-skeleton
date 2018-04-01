<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */

namespace common\models;


use common\behaviors\UTCDateTimeBehavior;
use Yii;


/**
 * @property integer|\MongoDB\BSON\ObjectID|string $id
 * @property string $task
 * @property string $result
 * @property integer|\MongoDB\BSON\UTCDateTime $created_at
 * @property integer|\MongoDB\BSON\UTCDateTime $updated_at
 */
class TaskLog extends \yii\mongodb\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'core_task_logs';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'task',
            'result',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * get id
     * @return \MongoDB\BSON\ObjectID|string
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            UTCDateTimeBehavior::class,
        ];
    }

    /**
     * @return int timestamp
     */
    public function getUpdatedAt()
    {
        return $this->updated_at->toDateTime()->format('U');
    }

    /**
     * @return int timestamp
     */
    public function getCreatedAt()
    {
        return $this->created_at->toDateTime()->format('U');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task', 'result'], 'required'],
            [['result'], 'string'],

            [['task'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'yii\mongodb\validators\MongoDateValidator'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => Yii::t('app', 'ID'),
            'task' => Yii::t('app', 'Task'),
            'result' => Yii::t('app', 'Result'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }


    /**
     * get task list
     * @return array
     */
    public static function getTaskList()
    {
        $vendors = ['harrytang', 'powerkernel'];
        $options = [];

        /* main */
        $mainTasks = scandir(Yii::$app->basePath . '/../console/cronjobs');
        foreach ($mainTasks as $mainTask) {
            if (preg_match('/^(Task\w+).php$/', $mainTask, $match)) {
                $name = str_ireplace('.php', '', $mainTask);
                $options[$name] = $name;
            }
        }

        /* vendors */
        foreach ($vendors as $vendor) {
            $dir = \Yii::$app->vendorPath . '/' . $vendor;
            if (file_exists($dir)) {
                $modules = scandir($dir);
                foreach ($modules as $module) {
                    if (!preg_match('/[\.]+/', $module)) {
                        $cronjobs = $dir . '/' . $module . '/cronjobs';
                        if (is_dir($cronjobs)) {
                            $tasks = scandir($cronjobs);
                            foreach ($tasks as $task) {
                                if (preg_match('/^(Task\w+).php$/', $task, $match)) {
                                    //require($dir.'/' . $module . '/cronjobs/' . $task);
                                    $name = str_ireplace('.php', '', $task);
                                    $options[$name] = $name;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $options;
    }
}
