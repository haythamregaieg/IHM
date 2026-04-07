<?php
$content = file_get_contents('views.txt');
$utf8 = mb_convert_encoding($content, 'UTF-8', 'UTF-16LE');
file_put_contents('views_utf8.txt', $utf8);
