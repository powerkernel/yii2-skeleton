<?php
/**
 * @var \omnilight\scheduling\Schedule $schedule
 */


use common\Core;

$local = Core::isLocalhost();
$time = $local ? '* * * * *' : '0 0 1 * *';

$schedule->call(function (\yii\console\Application $app) {

    Yii::$app->cache->flush();
    Yii::$app->db->schema->refresh();

    $output = Yii::t('app','All values from cache deleted.');

    $log = new \common\models\TaskLog();
    $log->task = basename(__FILE__, '.php');
    $log->result = $output;
    $log->save();

    /* delete old logs never bad */
    $period = 30 * 24 * 60 * 60; // 7 days
    $point = time() - $period;
    if(Yii::$app->params['mongodb']['taskLog']){
        \common\models\TaskLog::deleteAll([
            'task'=>basename(__FILE__, '.php'),
            'created_at'=>['$lte', new \MongoDB\BSON\UTCDateTime($point*1000)]
        ]);
    }
    else {
        \common\models\TaskLog::deleteAll('task=:task AND created_at<=:point', [
            ':task' => basename(__FILE__, '.php'),
            ':point' => $point
        ]);
    }

    unset($app);
})->cron($time);