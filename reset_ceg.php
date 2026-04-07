<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$e = App\Models\Entreprise::where('email', 'ceg@gmail.com')->first();
if ($e) {
    $e->password = Illuminate\Support\Facades\Hash::make('Admin@12345');
    $e->save();
    echo "Password for ceg@gmail.com reset to 'Admin@12345'";
} else {
    echo "Not found";
}
