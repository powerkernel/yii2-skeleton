<?php
/**
 * @var \powerkernel\scheduling\Schedule $schedule
 */

/* main task */
$mainTasks = scandir(__DIR__);
foreach ($mainTasks as $mainTask) {
    if (preg_match('/^(Task\w+).php$/', $mainTask, $match)) {
        require(__DIR__ .'/'. $mainTask);
    }
}

/* vendors tasks */
$vendors=['harrytang','powerkernel'];
foreach($vendors as $vendor){
    $dir = \Yii::$app->vendorPath . '/'.$vendor;
    if (file_exists($dir)) {
        $modules = scandir($dir);
        foreach ($modules as $module) {
            if (!preg_match('/[\.]+/', $module)) {
                $cronjobs = $dir.'/' . $module . '/cronjobs';
                if (is_dir($cronjobs)) {
                    $tasks = scandir($cronjobs);
                    foreach ($tasks as $task) {
                        if (preg_match('/^(Task\w+).php$/', $task, $match)) {
                            require($dir.'/' . $module . '/cronjobs/' . $task);
                        }
                    }
                }
            }
        }
    }
}