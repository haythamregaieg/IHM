<?php
$lines = file(__DIR__.'/storage/logs/laravel.log');
$end = array_slice($lines, -200);
$out = "";
foreach($end as $line) {
    if (strpos($line, 'local.INFO') !== false) {
        $out .= $line;
    }
}
file_put_contents('last_info.txt', $out);
