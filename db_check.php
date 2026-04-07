<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$entreprises = App\Models\Entreprise::all(['idEntreprise', 'email', 'password', 'statut']);
$out = "";
foreach ($entreprises as $e) {
    $out .= "ID: " . $e->idEntreprise . " EMAIL: " . $e->email . " STATUT: " . $e->statut . " PASS: " . $e->password . "\n";
}
file_put_contents('db_out.txt', $out);
