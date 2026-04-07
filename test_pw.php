<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$hash = App\Models\Entreprise::where('email', 'test@gmail.com')->value('password');
$out = "Hash: " . $hash . "\n";
$tests = ['password', '12345678', 'password123', 'admin123', 'test@gmail.com', 'testtest'];
foreach ($tests as $t) {
    if (password_verify($t, $hash)) {
        $out .= "MATCH: " . $t . "\n";
    }
}
file_put_contents('test_pw.txt', $out);
