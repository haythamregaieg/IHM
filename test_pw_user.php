<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = 'haythamregaieg11@gmail.com';
$hash = App\Models\Entreprise::where('email', $email)->value('password');
$out = "Hash for " . $email . ": " . $hash . "\n";
file_put_contents('test_pw_user.txt', $out);
