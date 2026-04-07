<?php
$lines = file(__DIR__.'/storage/logs/laravel.log');
$end = array_slice($lines, -150);
file_put_contents(__DIR__.'/last_log.txt', implode("", $end));
