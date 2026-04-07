<?php
$data = [
    'raisonSociale' => 'Curl Test',
    'identifiantLegal' => 'SIRET-CURL',
    'email' => 'curl@test.com',
    'motDePasse' => 'password123',
    'motDePasse_confirmation' => 'password123'
];
$ch = curl_init('http://localhost/api/entreprise/register');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
$res = curl_exec($ch);
echo "REGISTER: " . $res . "\n\n";

// Validate
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$e = App\Models\Entreprise::where('email', 'curl@test.com')->first();
if ($e) {
    $e->update(['statut' => 'valide']);
    echo "VALIDATED.\n\n";
}

// Login
$loginData = [
    'email' => 'curl@test.com',
    'motDePasse' => 'password123'
];
$ch2 = curl_init('http://localhost/api/entreprise/login');
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_POST, true);
curl_setopt($ch2, CURLOPT_POSTFIELDS, json_encode($loginData));
curl_setopt($ch2, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
$res2 = curl_exec($ch2);
echo "LOGIN: " . $res2 . "\n";
