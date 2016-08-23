<?php
/**
 * @var \omnilight\scheduling\Schedule $schedule
 */

$vendors=['harrytang','modernkernel'];

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