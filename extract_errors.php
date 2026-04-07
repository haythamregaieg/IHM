<?php
$lines = file(__DIR__.'/storage/logs/laravel.log');
$end = array_slice($lines, -200);
file_put_contents('last_logs.txt', implode('', $end));
