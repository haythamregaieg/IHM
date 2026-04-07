<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Domaine;
use App\Models\Categorie;
use App\Models\Region;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin par defaut
        Admin::firstOrCreate(["email" => "admin@jobplatform.com"], [
            "nom"     => "Super",
            "prenom"  => "Admin",
            "email"   => "admin@jobplatform.com",
            "password"=> Hash::make("Admin@12345"),
        ]);

        // Domaines
        $domaines = [
            "Informatique & Technologie", "Finance & Comptabilite",
            "Marketing & Communication", "Ressources Humaines",
            "Sante & Medecine", "Ingenierie & Industrie",
            "Commerce & Vente", "Education & Formation",
            "Droit & Juridique", "Architecture & BTP",
        ];
        foreach ($domaines as $nom) {
            Domaine::firstOrCreate(["nom" => $nom]);
        }

        // Categories
        $categories = ["Junior", "Senior", "Manager", "Expert", "Stagiaire", "Alternant"];
        foreach ($categories as $nom) {
            Categorie::firstOrCreate(["nom" => $nom]);
        }

        // Regions Tunisie (24 Gouvernorats)
        $regions = [
            "Ariana", "Béja", "Ben Arous", "Bizerte", "Gabès", "Gafsa", "Jendouba", 
            "Kairouan", "Kasserine", "Kébili", "Le Kef", "Mahdia", "La Manouba", 
            "Médenine", "Monastir", "Nabeul", "Sfax", "Sidi Bouzid", "Siliana", 
            "Sousse", "Tataouine", "Tozeur", "Tunis", "Zaghouan"
        ];
        foreach ($regions as $ville) {
            Region::firstOrCreate(["ville" => $ville], ["pays" => "Tunisie"]);
        }

        $this->command->info("Seeder termine avec succes !");
        $this->command->info("Admin: admin@jobplatform.com / Admin@12345");
    }
}