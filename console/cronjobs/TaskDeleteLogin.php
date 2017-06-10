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
    $links = \common\models\Login::find()->where('status=:status OR updated_at<=:point', [
        ':status'=>\common\models\Login::STATUS_USED,
        ':point' => $point
    ])->all();
    if($links){
        $obj = [];
        foreach($links as $link){
            $obj[] = $link->token;
            $link->delete();
        }
        $output = implode(', ', $obj);
    }

    if(empty($output)){
        $output = Yii::t('app','No login link deleted.');
    }


    $log = new \common\models\TaskLog();
    $log->task = basename(__FILE__, '.php');
    $log->result = $output;
    $log->save();
    /* delete old logs never bad */
    $period = 7 * 24 * 60 * 60; // 7 days
    $point = time() - $period;
    \common\models\TaskLog::deleteAll('task=:task AND created_at<=:point', [
        ':task' => basename(__FILE__, '.php'),
        ':point' => $point
    ]);


})->cron($time);