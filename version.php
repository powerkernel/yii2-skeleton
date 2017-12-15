<?php
$build=exec('git rev-list HEAD --count');
$a = [
    'version' => '1.0.13',
    'build'=>$build++,
    'date'=>time()
];
file_put_contents(__DIR__.'/version.json', json_encode($a));