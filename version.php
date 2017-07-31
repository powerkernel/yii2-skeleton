<?php
$a = [
    'version' => '0.3.1dev',
    'build'=>time()
];
file_put_contents(__DIR__.'/version.json', json_encode($a));