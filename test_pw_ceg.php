<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$hash = '$2y$12$4ZGvxsuR2Mk.0VmzQD4ZBOGEnVXg0.DewpVjNuZrM5qMhyFT4HEZe';
$pw = 'Admin@12345';

echo "Match check: " . (password_verify($pw, $hash) ? 'TRUE' : 'FALSE');
