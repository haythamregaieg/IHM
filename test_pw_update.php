<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$e = App\Models\Entreprise::where('email', 'test@gmail.com')->first();
$out = "BEFORE admin update: " . $e->password . "\n";

$request = Illuminate\Http\Request::create('/api/admin/entreprises/'.$e->idEntreprise.'/statut', 'PATCH', [
    'statut' => 'valide'
]);
$controller = new App\Http\Controllers\EntrepriseController();
$controller->updateStatut($request, $e->idEntreprise);

$e->refresh();
$out .= "AFTER admin update: " . $e->password . "\n";
$out .= "MATCH? " . ($out[21] === $e->password[0] ? 'YES' : 'NO');
file_put_contents('test_pw_update.txt', $out);
