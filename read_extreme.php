<?php
$lines = file(__DIR__.'/storage/logs/laravel.log');
$out = "";
foreach($lines as $line) {
    if (strpos($line, 'EXTREME') !== false || 
        strpos($line, 'Email provided:') !== false || 
        strpos($line, 'MotDePasse') !== false || 
        strpos($line, 'DB Hash') !== false || 
        strpos($line, 'Password check') !== false) {
        $out .= $line;
    }
}
file_put_contents('extreme.txt', $out);
