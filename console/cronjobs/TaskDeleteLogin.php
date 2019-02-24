<?php
/**
 * @var \powerkernel\scheduling\Schedule $schedule
 */


use common\Core;
use common\models\Setting;

$local = Core::isLocalhost();
$time = $local ? '* * * * *' : '33 3 * * *';

$schedule->call(function (\yii\console\Application $app) {

    $now = time();
    $expire = (int)Setting::getValue('tokenExpiryTime');
    $point = $now - $expire;


    $codes = \common\models\CodeVerification::find()->where([
        'status' => \common\models\CodeVerification::STATUS_USED,
    ])->orWhere([
        'updated_at' => ['$lt' => new \MongoDB\BSON\UTCDateTime($point * 1000)]
    ])->all();

    if ($codes) {
        $obj = [];
        foreach ($codes as $code) {
            $obj[] = (string)$code->_id;
            $code->delete();
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

    \common\models\TaskLog::deleteAll([
        'task' => basename(__FILE__, '.php'),
        'created_at' => ['$lte', new \MongoDB\BSON\UTCDateTime($point * 1000)]
    ]);

    unset($app);
})->cron($time);