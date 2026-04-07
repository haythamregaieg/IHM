<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthEntrepriseController;

try {
    $controller = new AuthEntrepriseController();
    
    // Create random identifier
    $id = uniqid();

    // 1. Register
    $req = Request::create('/api/entreprise/register', 'POST', [
        'raisonSociale' => 'Test Company ' . $id,
        'identifiantLegal' => 'SIRET-' . $id,
        'email' => 'test'.$id.'@entreprise.com',
        'motDePasse' => 'password123',
        'motDePasse_confirmation' => 'password123',
    ]);
    
    $response = $controller->register($req);
    echo "Register status: " . $response->getStatusCode() . "\n";
    $content = json_decode($response->getContent(), true);
    $entrepriseId = $content['entreprise']['idEntreprise'];
    
    // 2. Validate by admin
    $entreprise = \App\Models\Entreprise::find($entrepriseId);
    $entreprise->update(['statut' => 'valide']);
    
    // 3. Login
    $loginReq = Request::create('/api/entreprise/login', 'POST', [
        'email' => 'test'.$id.'@entreprise.com',
        'motDePasse' => 'password123',
    ]);
    
    $loginResponse = $controller->login($loginReq);
    echo "Login status: " . $loginResponse->getStatusCode() . "\n";
    echo "Login output: " . $loginResponse->getContent() . "\n";

} catch (\Exception $e) {
    if ($e instanceof \Illuminate\Validation\ValidationException) {
        echo "ValidationException: " . json_encode($e->errors()) . "\n";
    } else {
        echo "Exception: " . $e->getMessage() . "\n";
    }
}
