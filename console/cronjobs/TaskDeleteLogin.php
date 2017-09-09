<?php
/**
 * @var \omnilight\scheduling\Schedule $schedule
 */


use common\Core;
use common\models\Setting;

$local = Core::isLocalhost();
$time = $local ? '* * * * *' : '33 3 * * *';

$schedule->call(function (\yii\console\Application $app) {

    $now = time();
    $expire = (int)Setting::getValue('tokenExpiryTime');
    $point = $now - $expire;

    if (Yii::$app->params['mongodb']['login']) {
        $links = \common\models\Login::find()->where([
            'status'=>\common\models\Login::STATUS_USED,
        ])->orWhere([
            'updated_at'=>['$lt'=>new \MongoDB\BSON\UTCDateTime($point*1000)]
        ])->all();
    } else {
        $links = \common\models\Login::find()->where('status=:status OR updated_at<=:point', [
            ':status' => \common\models\Login::STATUS_USED,
            ':point' => $point
        ])->all();
    }
    if ($links) {
        $obj = [];
        foreach ($links as $link) {
            $obj[] = $link->token;
            $link->delete();
        }
        $output = implode(', ', $obj);
    }

    if (!empty($output)) {
        $log = new \common\models\TaskLog();
        $log->task = basename(__FILE__, '.php');
        $log->result = $output;
        $log->save();
    }

    /* delete old logs never bad */
    $period = 30 * 24 * 60 * 60; // 30 days
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