# ================================================================
#  JOBPLATFORM - SCRIPT 1/3 : BACKEND COMPLET
#  Basé sur : Diagramme de classes + Cahier des charges
#  Usage: powershell -ExecutionPolicy Bypass -File 01_backend.ps1
# ================================================================
$p = "C:\xampp\htdocs\job-platformm"
Set-Location $p
Write-Host "▶ BACKEND - Migrations, Modeles, Controllers, Routes" -ForegroundColor Cyan

# ── Packages ─────────────────────────────────────────────────────
composer require laravel/sanctum --no-interaction -q
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider" --quiet

# ── Dossiers ─────────────────────────────────────────────────────
New-Item -ItemType Directory -Force "app\Http\Controllers\Auth" | Out-Null
New-Item -ItemType Directory -Force "app\Http\Middleware" | Out-Null

# ================================================================
# MIGRATIONS (ordre exact selon dépendances FK)
# ================================================================
$m = "database\migrations"
Remove-Item "$m\*create_users*"     -Force -ErrorAction SilentlyContinue
Remove-Item "$m\*create_password_r*" -Force -ErrorAction SilentlyContinue
Remove-Item "$m\*create_failed*"    -Force -ErrorAction SilentlyContinue
Remove-Item "$m\*create_personal_a*" -Force -ErrorAction SilentlyContinue

# -- admins
@'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('admins', function (Blueprint $t) {
            $t->id('idAdmin');
            $t->string('nom');
            $t->string('prenom')->nullable();
            $t->string('email')->unique();
            $t->string('motDePasse');
            $t->rememberToken();
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('admins'); }
};
'@ | Set-Content "$m\2024_01_01_000001_create_admins_table.php" -Encoding UTF8

# -- domaines
@'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('domaines', function (Blueprint $t) {
            $t->id('idDomaine');
            $t->string('nom')->unique();
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('domaines'); }
};
'@ | Set-Content "$m\2024_01_01_000002_create_domaines_table.php" -Encoding UTF8

# -- categories
@'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('categories', function (Blueprint $t) {
            $t->id('idCategorie');
            $t->string('nom')->unique();
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('categories'); }
};
'@ | Set-Content "$m\2024_01_01_000003_create_categories_table.php" -Encoding UTF8

# -- regions
@'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('regions', function (Blueprint $t) {
            $t->id('idRegion');
            $t->string('pays');
            $t->string('ville');
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('regions'); }
};
'@ | Set-Content "$m\2024_01_01_000004_create_regions_table.php" -Encoding UTF8

# -- candidats
@'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('candidats', function (Blueprint $t) {
            $t->id('idCandidat');
            $t->string('nom');
            $t->string('prenom');
            $t->string('email')->unique();
            $t->string('motDePasse');
            $t->unsignedBigInteger('idDomaine')->nullable();
            $t->foreign('idDomaine')->references('idDomaine')->on('domaines')->nullOnDelete();
            $t->string('specialite')->nullable();
            $t->string('niveauEtude')->nullable();
            $t->string('niveauExperience')->nullable();
            $t->string('localisation')->nullable();
            $t->string('photo')->nullable();
            $t->string('cv_path')->nullable();
            $t->boolean('actif')->default(true);
            $t->rememberToken();
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('candidats'); }
};
'@ | Set-Content "$m\2024_01_01_000005_create_candidats_table.php" -Encoding UTF8

# -- entreprises
@'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('entreprises', function (Blueprint $t) {
            $t->id('idEntreprise');
            $t->string('raisonSociale');
            $t->text('description')->nullable();
            $t->string('identifiantLegal')->unique();
            $t->unsignedBigInteger('idDomaine')->nullable();
            $t->foreign('idDomaine')->references('idDomaine')->on('domaines')->nullOnDelete();
            $t->string('pays')->nullable();
            $t->string('ville')->nullable();
            $t->string('telephone')->nullable();
            $t->string('logo')->nullable();
            $t->string('siteWeb')->nullable();
            $t->string('email')->unique();
            $t->string('motDePasse');
            $t->enum('statut', ['en_attente','valide','bloque'])->default('en_attente');
            $t->rememberToken();
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('entreprises'); }
};
'@ | Set-Content "$m\2024_01_01_000006_create_entreprises_table.php" -Encoding UTF8

# -- offres
@'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('offres', function (Blueprint $t) {
            $t->id('idOffre');
            $t->unsignedBigInteger('idEntreprise');
            $t->foreign('idEntreprise')->references('idEntreprise')->on('entreprises')->cascadeOnDelete();
            $t->unsignedBigInteger('idDomaine')->nullable();
            $t->foreign('idDomaine')->references('idDomaine')->on('domaines')->nullOnDelete();
            $t->unsignedBigInteger('idCategorie')->nullable();
            $t->foreign('idCategorie')->references('idCategorie')->on('categories')->nullOnDelete();
            $t->unsignedBigInteger('idRegion')->nullable();
            $t->foreign('idRegion')->references('idRegion')->on('regions')->nullOnDelete();
            $t->string('titre');
            $t->enum('typeContrat', ['CDI','CDD','Stage','Freelance','Alternance']);
            $t->text('description');
            $t->text('competencesRequises');
            $t->decimal('remuneration', 10, 2)->nullable();
            $t->date('datePublication')->nullable();
            $t->date('dateLimite');
            $t->enum('statut', ['active','expiree','suspendue'])->default('active');
            $t->string('localisation');
            $t->unsignedInteger('vues')->default(0);
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('offres'); }
};
'@ | Set-Content "$m\2024_01_01_000007_create_offres_table.php" -Encoding UTF8

# -- candidatures
@'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('candidatures', function (Blueprint $t) {
            $t->id('idCandidature');
            $t->unsignedBigInteger('idCandidat');
            $t->foreign('idCandidat')->references('idCandidat')->on('candidats')->cascadeOnDelete();
            $t->unsignedBigInteger('idOffre');
            $t->foreign('idOffre')->references('idOffre')->on('offres')->cascadeOnDelete();
            $t->date('dateCandidature');
            $t->enum('statut', ['en_attente','en_cours','retenu','refuse'])->default('en_attente');
            $t->text('lettreMotivation');
            $t->string('cv_path')->nullable();
            $t->timestamps();
            $t->unique(['idCandidat','idOffre']);
        });
    }
    public function down(): void { Schema::dropIfExists('candidatures'); }
};
'@ | Set-Content "$m\2024_01_01_000008_create_candidatures_table.php" -Encoding UTF8

# -- messages
@'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('messages', function (Blueprint $t) {
            $t->id('idMessage');
            $t->unsignedBigInteger('idEntreprise');
            $t->foreign('idEntreprise')->references('idEntreprise')->on('entreprises')->cascadeOnDelete();
            $t->unsignedBigInteger('idCandidat');
            $t->foreign('idCandidat')->references('idCandidat')->on('candidats')->cascadeOnDelete();
            $t->text('contenu');
            $t->timestamp('dateEnvoi')->useCurrent();
            $t->enum('expediteur', ['entreprise','candidat']);
            $t->boolean('lu')->default(false);
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('messages'); }
};
'@ | Set-Content "$m\2024_01_01_000009_create_messages_table.php" -Encoding UTF8

# -- certificats
@'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('certificats', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('idCandidat');
            $t->foreign('idCandidat')->references('idCandidat')->on('candidats')->cascadeOnDelete();
            $t->string('nom');
            $t->string('organisme');
            $t->date('dateObtention');
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('certificats'); }
};
'@ | Set-Content "$m\2024_01_01_000010_create_certificats_table.php" -Encoding UTF8

# -- offres_enregistrees
@'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('offres_enregistrees', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('idCandidat');
            $t->foreign('idCandidat')->references('idCandidat')->on('candidats')->cascadeOnDelete();
            $t->unsignedBigInteger('idOffre');
            $t->foreign('idOffre')->references('idOffre')->on('offres')->cascadeOnDelete();
            $t->timestamp('dateAjout')->useCurrent();
            $t->timestamps();
            $t->unique(['idCandidat','idOffre']);
        });
    }
    public function down(): void { Schema::dropIfExists('offres_enregistrees'); }
};
'@ | Set-Content "$m\2024_01_01_000011_create_offres_enregistrees_table.php" -Encoding UTF8

# -- signalements
@'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('signalements', function (Blueprint $t) {
            $t->id('idSignalement');
            $t->unsignedBigInteger('idAdmin')->nullable();
            $t->foreign('idAdmin')->references('idAdmin')->on('admins')->nullOnDelete();
            $t->string('signaleur_type')->nullable();
            $t->unsignedBigInteger('signaleur_id')->nullable();
            $t->string('cible_type');
            $t->unsignedBigInteger('cible_id');
            $t->text('description');
            $t->date('date');
            $t->enum('statut', ['en_attente','traite','rejete'])->default('en_attente');
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('signalements'); }
};
'@ | Set-Content "$m\2024_01_01_000012_create_signalements_table.php" -Encoding UTF8

# -- personal_access_tokens (Sanctum)
@'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('personal_access_tokens'); }
};
'@ | Set-Content "$m\2024_01_01_000013_create_personal_access_tokens_table.php" -Encoding UTF8

Write-Host "  ✓ Migrations crees" -ForegroundColor Green

# ================================================================
# MODELS
# ================================================================
$mo = "app\Models"

@'
<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
class Admin extends Authenticatable {
    use HasApiTokens, Notifiable;
    protected $table = 'admins';
    protected $primaryKey = 'idAdmin';
    protected $fillable = ['nom','prenom','email','motDePasse'];
    protected $hidden = ['motDePasse','remember_token'];
    public function getAuthPassword() { return $this->motDePasse; }
    public function signalements() { return $this->hasMany(Signalement::class,'idAdmin','idAdmin'); }
}
'@ | Set-Content "$mo\Admin.php" -Encoding UTF8

@'
<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
class Candidat extends Authenticatable {
    use HasApiTokens, Notifiable;
    protected $table = 'candidats';
    protected $primaryKey = 'idCandidat';
    protected $fillable = ['nom','prenom','email','motDePasse','idDomaine','specialite','niveauEtude','niveauExperience','localisation','photo','cv_path','actif'];
    protected $hidden = ['motDePasse','remember_token'];
    protected $casts = ['actif' => 'boolean'];
    public function getAuthPassword() { return $this->motDePasse; }
    public function domaine() { return $this->belongsTo(Domaine::class,'idDomaine','idDomaine'); }
    public function candidatures() { return $this->hasMany(Candidature::class,'idCandidat','idCandidat'); }
    public function messages() { return $this->hasMany(Message::class,'idCandidat','idCandidat'); }
    public function certificats() { return $this->hasMany(Certificat::class,'idCandidat','idCandidat'); }
    public function offresEnregistrees() { return $this->hasMany(OffreEnregistree::class,'idCandidat','idCandidat'); }
}
'@ | Set-Content "$mo\Candidat.php" -Encoding UTF8

@'
<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
class Entreprise extends Authenticatable {
    use HasApiTokens, Notifiable;
    protected $table = 'entreprises';
    protected $primaryKey = 'idEntreprise';
    protected $fillable = ['raisonSociale','description','identifiantLegal','idDomaine','pays','ville','telephone','logo','siteWeb','email','motDePasse','statut'];
    protected $hidden = ['motDePasse','remember_token'];
    public function getAuthPassword() { return $this->motDePasse; }
    public function domaine() { return $this->belongsTo(Domaine::class,'idDomaine','idDomaine'); }
    public function offres() { return $this->hasMany(Offre::class,'idEntreprise','idEntreprise'); }
    public function messages() { return $this->hasMany(Message::class,'idEntreprise','idEntreprise'); }
}
'@ | Set-Content "$mo\Entreprise.php" -Encoding UTF8

@'
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Domaine extends Model {
    protected $table = 'domaines';
    protected $primaryKey = 'idDomaine';
    protected $fillable = ['nom'];
    public function offres() { return $this->hasMany(Offre::class,'idDomaine','idDomaine'); }
    public function candidats() { return $this->hasMany(Candidat::class,'idDomaine','idDomaine'); }
    public function entreprises() { return $this->hasMany(Entreprise::class,'idDomaine','idDomaine'); }
}
'@ | Set-Content "$mo\Domaine.php" -Encoding UTF8

@'
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Categorie extends Model {
    protected $table = 'categories';
    protected $primaryKey = 'idCategorie';
    protected $fillable = ['nom'];
    public function offres() { return $this->hasMany(Offre::class,'idCategorie','idCategorie'); }
}
'@ | Set-Content "$mo\Categorie.php" -Encoding UTF8

@'
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Region extends Model {
    protected $table = 'regions';
    protected $primaryKey = 'idRegion';
    protected $fillable = ['pays','ville'];
    public function offres() { return $this->hasMany(Offre::class,'idRegion','idRegion'); }
    public function entreprises() { return $this->hasMany(Entreprise::class,'idRegion','idRegion'); }
}
'@ | Set-Content "$mo\Region.php" -Encoding UTF8

@'
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Offre extends Model {
    protected $table = 'offres';
    protected $primaryKey = 'idOffre';
    protected $fillable = ['idEntreprise','idDomaine','idCategorie','idRegion','titre','typeContrat','description','competencesRequises','remuneration','datePublication','dateLimite','statut','localisation','vues'];
    protected $casts = ['datePublication'=>'date','dateLimite'=>'date','remuneration'=>'float','vues'=>'integer'];
    public function entreprise() { return $this->belongsTo(Entreprise::class,'idEntreprise','idEntreprise'); }
    public function domaine() { return $this->belongsTo(Domaine::class,'idDomaine','idDomaine'); }
    public function categorie() { return $this->belongsTo(Categorie::class,'idCategorie','idCategorie'); }
    public function region() { return $this->belongsTo(Region::class,'idRegion','idRegion'); }
    public function candidatures() { return $this->hasMany(Candidature::class,'idOffre','idOffre'); }
    public function offresEnregistrees() { return $this->hasMany(OffreEnregistree::class,'idOffre','idOffre'); }
}
'@ | Set-Content "$mo\Offre.php" -Encoding UTF8

@'
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Candidature extends Model {
    protected $table = 'candidatures';
    protected $primaryKey = 'idCandidature';
    protected $fillable = ['idCandidat','idOffre','dateCandidature','statut','lettreMotivation','cv_path'];
    protected $casts = ['dateCandidature'=>'date'];
    public function candidat() { return $this->belongsTo(Candidat::class,'idCandidat','idCandidat'); }
    public function offre() { return $this->belongsTo(Offre::class,'idOffre','idOffre'); }
}
'@ | Set-Content "$mo\Candidature.php" -Encoding UTF8

@'
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Message extends Model {
    protected $table = 'messages';
    protected $primaryKey = 'idMessage';
    protected $fillable = ['idEntreprise','idCandidat','contenu','dateEnvoi','expediteur','lu'];
    protected $casts = ['dateEnvoi'=>'datetime','lu'=>'boolean'];
    public function entreprise() { return $this->belongsTo(Entreprise::class,'idEntreprise','idEntreprise'); }
    public function candidat() { return $this->belongsTo(Candidat::class,'idCandidat','idCandidat'); }
}
'@ | Set-Content "$mo\Message.php" -Encoding UTF8

@'
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Certificat extends Model {
    protected $table = 'certificats';
    protected $fillable = ['idCandidat','nom','organisme','dateObtention'];
    protected $casts = ['dateObtention'=>'date'];
    public function candidat() { return $this->belongsTo(Candidat::class,'idCandidat','idCandidat'); }
}
'@ | Set-Content "$mo\Certificat.php" -Encoding UTF8

@'
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class OffreEnregistree extends Model {
    protected $table = 'offres_enregistrees';
    protected $fillable = ['idCandidat','idOffre','dateAjout'];
    protected $casts = ['dateAjout'=>'datetime'];
    public function candidat() { return $this->belongsTo(Candidat::class,'idCandidat','idCandidat'); }
    public function offre() { return $this->belongsTo(Offre::class,'idOffre','idOffre'); }
}
'@ | Set-Content "$mo\OffreEnregistree.php" -Encoding UTF8

@'
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Signalement extends Model {
    protected $table = 'signalements';
    protected $primaryKey = 'idSignalement';
    protected $fillable = ['idAdmin','signaleur_type','signaleur_id','cible_type','cible_id','description','date','statut'];
    protected $casts = ['date'=>'date'];
    public function admin() { return $this->belongsTo(Admin::class,'idAdmin','idAdmin'); }
}
'@ | Set-Content "$mo\Signalement.php" -Encoding UTF8

Write-Host "  ✓ Modeles crees" -ForegroundColor Green

# ================================================================
# CONTROLLERS AUTH
# ================================================================
$c = "app\Http\Controllers"

@'
<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
class AuthAdminController extends Controller {
    public function register(Request $r) {
        $v = $r->validate(['nom'=>'required|string|max:255','prenom'=>'nullable|string','email'=>'required|email|unique:admins,email','motDePasse'=>'required|min:8|confirmed']);
        $admin = Admin::create(['nom'=>$v['nom'],'prenom'=>$v['prenom']??null,'email'=>$v['email'],'motDePasse'=>Hash::make($v['motDePasse'])]);
        return response()->json(['admin'=>$admin,'token'=>$admin->createToken('admin')->plainTextToken],201);
    }
    public function login(Request $r) {
        $r->validate(['email'=>'required|email','motDePasse'=>'required']);
        $admin = Admin::where('email',$r->email)->first();
        if(!$admin||!Hash::check($r->motDePasse,$admin->motDePasse)) throw ValidationException::withMessages(['email'=>['Identifiants incorrects.']]);
        return response()->json(['admin'=>$admin,'token'=>$admin->createToken('admin')->plainTextToken]);
    }
    public function logout(Request $r) { $r->user()->currentAccessToken()->delete(); return response()->json(['message'=>'Déconnecté.']); }
    public function me(Request $r) { return response()->json($r->user()); }
}
'@ | Set-Content "$c\Auth\AuthAdminController.php" -Encoding UTF8

@'
<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\Candidat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
class AuthCandidatController extends Controller {
    public function register(Request $r) {
        $v = $r->validate([
            'nom'=>'required|string|max:255','prenom'=>'required|string|max:255',
            'email'=>'required|email|unique:candidats,email',
            'motDePasse'=>'required|min:8|confirmed',
            'idDomaine'=>'nullable|exists:domaines,idDomaine',
            'specialite'=>'nullable|string|max:255',
            'niveauEtude'=>'nullable|string|max:255',
            'niveauExperience'=>'nullable|string|max:255',
            'localisation'=>'nullable|string|max:255',
        ]);
        $c = Candidat::create(array_merge($v,['motDePasse'=>Hash::make($v['motDePasse'])]));
        unset($c->motDePasse);
        return response()->json(['candidat'=>$c,'token'=>$c->createToken('candidat')->plainTextToken],201);
    }
    public function login(Request $r) {
        $r->validate(['email'=>'required|email','motDePasse'=>'required']);
        $c = Candidat::where('email',$r->email)->first();
        if(!$c||!Hash::check($r->motDePasse,$c->motDePasse)) throw ValidationException::withMessages(['email'=>['Identifiants incorrects.']]);
        if(!$c->actif) return response()->json(['message'=>'Votre compte a été suspendu.'],403);
        return response()->json(['candidat'=>$c->load('domaine'),'token'=>$c->createToken('candidat')->plainTextToken]);
    }
    public function logout(Request $r) { $r->user()->currentAccessToken()->delete(); return response()->json(['message'=>'Déconnecté.']); }
    public function me(Request $r) { return response()->json($r->user()->load(['domaine','certificats'])); }
    public function updateProfile(Request $r) {
        $c = $r->user();
        $v = $r->validate([
            'nom'=>'sometimes|string','prenom'=>'sometimes|string',
            'idDomaine'=>'nullable|exists:domaines,idDomaine',
            'specialite'=>'nullable|string','niveauEtude'=>'nullable|string',
            'niveauExperience'=>'nullable|string','localisation'=>'nullable|string',
            'photo'=>'nullable|image|max:2048','cv'=>'nullable|mimes:pdf,doc,docx|max:5120',
        ]);
        if($r->hasFile('photo')){ if($c->photo)Storage::disk('public')->delete($c->photo); $v['photo']=$r->file('photo')->store('photos','public'); }
        if($r->hasFile('cv')){ if($c->cv_path)Storage::disk('public')->delete($c->cv_path); $v['cv_path']=$r->file('cv')->store('cvs','public'); }
        unset($v['photo_file'],$v['cv_file']);
        $c->update($v);
        return response()->json($c->fresh()->load('domaine'));
    }
    public function changePassword(Request $r) {
        $r->validate(['ancien'=>'required','motDePasse'=>'required|min:8|confirmed']);
        if(!Hash::check($r->ancien,$r->user()->motDePasse)) return response()->json(['message'=>'Ancien mot de passe incorrect.'],422);
        $r->user()->update(['motDePasse'=>Hash::make($r->motDePasse)]);
        return response()->json(['message'=>'Mot de passe modifié.']);
    }
    public function importerCV(Request $r) {
        $r->validate(['cv'=>'required|mimes:pdf,doc,docx|max:5120']);
        $c=$r->user(); if($c->cv_path)Storage::disk('public')->delete($c->cv_path);
        $c->update(['cv_path'=>$r->file('cv')->store('cvs','public')]);
        return response()->json(['cv_path'=>$c->cv_path,'message'=>'CV importé avec succès.']);
    }
}
'@ | Set-Content "$c\Auth\AuthCandidatController.php" -Encoding UTF8

@'
<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\Entreprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
class AuthEntrepriseController extends Controller {
    public function register(Request $r) {
        $v = $r->validate([
            'raisonSociale'=>'required|string','identifiantLegal'=>'required|string|unique:entreprises,identifiantLegal',
            'description'=>'nullable|string','idDomaine'=>'nullable|exists:domaines,idDomaine',
            'pays'=>'nullable|string','ville'=>'nullable|string','telephone'=>'nullable|string',
            'siteWeb'=>'nullable|url','email'=>'required|email|unique:entreprises,email',
            'motDePasse'=>'required|min:8|confirmed',
        ]);
        $e = Entreprise::create(array_merge($v,['motDePasse'=>Hash::make($v['motDePasse']),'statut'=>'en_attente']));
        return response()->json(['message'=>'Compte créé. En attente de validation administrateur.','entreprise'=>$e],201);
    }
    public function login(Request $r) {
        $r->validate(['email'=>'required|email','motDePasse'=>'required']);
        $e = Entreprise::where('email',$r->email)->first();
        if(!$e||!Hash::check($r->motDePasse,$e->motDePasse)) throw ValidationException::withMessages(['email'=>['Identifiants incorrects.']]);
        if($e->statut==='en_attente') return response()->json(['message'=>'Compte en attente de validation.'],403);
        if($e->statut==='bloque') return response()->json(['message'=>'Compte bloqué. Contactez l\'administrateur.'],403);
        return response()->json(['entreprise'=>$e->load(['domaine']),'token'=>$e->createToken('entreprise')->plainTextToken]);
    }
    public function logout(Request $r) { $r->user()->currentAccessToken()->delete(); return response()->json(['message'=>'Déconnecté.']); }
    public function me(Request $r) { return response()->json($r->user()->load(['domaine'])); }
    public function updateProfile(Request $r) {
        $e=$r->user();
        $v=$r->validate(['description'=>'nullable|string','pays'=>'nullable|string','ville'=>'nullable|string','telephone'=>'nullable|string','siteWeb'=>'nullable|url','logo'=>'nullable|image|max:2048']);
        if($r->hasFile('logo')){ if($e->logo)Storage::disk('public')->delete($e->logo); $v['logo']=$r->file('logo')->store('logos','public'); }
        $e->update($v);
        return response()->json($e->fresh()->load('domaine'));
    }
}
'@ | Set-Content "$c\Auth\AuthEntrepriseController.php" -Encoding UTF8

# ================================================================
# CONTROLLERS METIER
# ================================================================
@'
<?php
namespace App\Http\Controllers;
use App\Models\Offre;
use Illuminate\Http\Request;
class OffreController extends Controller {
    // PUBLIC - recherche simple et avancee (use case: Chercher d emploi)
    public function index(Request $r) {
        $q = Offre::with(['entreprise:idEntreprise,raisonSociale,logo','domaine:idDomaine,nom','categorie:idCategorie,nom','region:idRegion,pays,ville'])
            ->where('statut','active')->where('dateLimite','>=',now()->toDateString());
        // Recherche simple
        if($r->filled('q')) { $kw=$r->q; $q->where(fn($s)=>$s->where('titre','like',"%$kw%")->orWhere('description','like',"%$kw%")->orWhere('competencesRequises','like',"%$kw%")->orWhere('localisation','like',"%$kw%")); }
        // Recherche avancee (filtres)
        if($r->filled('idDomaine'))    $q->where('idDomaine',$r->idDomaine);
        if($r->filled('idCategorie'))  $q->where('idCategorie',$r->idCategorie);
        if($r->filled('idRegion'))     $q->where('idRegion',$r->idRegion);
        if($r->filled('typeContrat'))  $q->where('typeContrat',$r->typeContrat);
        if($r->filled('localisation')) $q->where('localisation','like',"%{$r->localisation}%");
        if($r->filled('remunMin'))     $q->where('remuneration','>=',$r->remunMin);
        if($r->filled('remunMax'))     $q->where('remuneration','<=',$r->remunMax);
        return response()->json($q->latest('datePublication')->paginate(12));
    }
    public function show($id) {
        $o = Offre::with(['entreprise','domaine','categorie','region'])->findOrFail($id);
        $o->increment('vues');
        return response()->json($o);
    }
    // ENTREPRISE
    public function mesOffres(Request $r) {
        return response()->json(Offre::with(['domaine','categorie','region'])->where('idEntreprise',$r->user()->idEntreprise)->withCount('candidatures')->latest()->get());
    }
    public function store(Request $r) {
        $v=$r->validate(['idDomaine'=>'required|exists:domaines,idDomaine','idCategorie'=>'required|exists:categories,idCategorie','idRegion'=>'required|exists:regions,idRegion','titre'=>'required|string|max:255','typeContrat'=>'required|in:CDI,CDD,Stage,Freelance,Alternance','description'=>'required|string','competencesRequises'=>'required|string','remuneration'=>'nullable|numeric|min:0','dateLimite'=>'required|date|after:today','localisation'=>'required|string']);
        $v['idEntreprise']=$r->user()->idEntreprise; $v['datePublication']=now()->toDateString(); $v['statut']='active';
        $o=Offre::create($v);
        return response()->json($o->load(['domaine','categorie','region']),201);
    }
    public function update(Request $r,$id) {
        $o=Offre::where('idOffre',$id)->where('idEntreprise',$r->user()->idEntreprise)->firstOrFail();
        $v=$r->validate(['idDomaine'=>'sometimes|exists:domaines,idDomaine','idCategorie'=>'sometimes|exists:categories,idCategorie','idRegion'=>'sometimes|exists:regions,idRegion','titre'=>'sometimes|string','typeContrat'=>'sometimes|in:CDI,CDD,Stage,Freelance,Alternance','description'=>'sometimes|string','competencesRequises'=>'sometimes|string','remuneration'=>'nullable|numeric','dateLimite'=>'sometimes|date','statut'=>'sometimes|in:active,suspendue','localisation'=>'sometimes|string']);
        $o->update($v);
        return response()->json($o->fresh()->load(['domaine','categorie','region']));
    }
    public function destroy(Request $r,$id) {
        Offre::where('idOffre',$id)->where('idEntreprise',$r->user()->idEntreprise)->firstOrFail()->delete();
        return response()->json(null,204);
    }
    public function suspendre(Request $r,$id) {
        $o=Offre::where('idOffre',$id)->where('idEntreprise',$r->user()->idEntreprise)->firstOrFail();
        $o->update(['statut'=>$o->statut==='active'?'suspendue':'active']);
        return response()->json($o);
    }
    // ADMIN
    public function adminIndex() { return response()->json(Offre::with(['entreprise:idEntreprise,raisonSociale','domaine:idDomaine,nom'])->withCount('candidatures')->latest()->paginate(20)); }
    public function adminStatut(Request $r,$id) { $r->validate(['statut'=>'required|in:active,expiree,suspendue']); Offre::findOrFail($id)->update(['statut'=>$r->statut]); return response()->json(Offre::findOrFail($id)); }
}
'@ | Set-Content "$c\OffreController.php" -Encoding UTF8

@'
<?php
namespace App\Http\Controllers;
use App\Models\Candidature;
use App\Models\Offre;
use Illuminate\Http\Request;
class CandidatureController extends Controller {
    // CANDIDAT: postuler
    public function store(Request $r) {
        $v=$r->validate(['idOffre'=>'required|exists:offres,idOffre','lettreMotivation'=>'required|string|min:50','cv'=>'nullable|mimes:pdf,doc,docx|max:5120']);
        $c=$r->user();
        if(Candidature::where('idCandidat',$c->idCandidat)->where('idOffre',$v['idOffre'])->exists()) return response()->json(['message'=>'Vous avez déjà postulé à cette offre.'],422);
        $o=Offre::findOrFail($v['idOffre']);
        if($o->statut!=='active'||$o->dateLimite<now()->toDateString()) return response()->json(['message'=>'Cette offre n\'est plus disponible.'],422);
        $cvPath=$c->cv_path;
        if($r->hasFile('cv')) $cvPath=$r->file('cv')->store('cvs/candidatures','public');
        $cand=Candidature::create(['idCandidat'=>$c->idCandidat,'idOffre'=>$v['idOffre'],'dateCandidature'=>now()->toDateString(),'statut'=>'en_attente','lettreMotivation'=>$v['lettreMotivation'],'cv_path'=>$cvPath]);
        return response()->json($cand->load(['offre.entreprise']),201);
    }
    // CANDIDAT: mes candidatures
    public function mesCandidatures(Request $r) {
        return response()->json(Candidature::with(['offre.entreprise:idEntreprise,raisonSociale,logo','offre.domaine:idDomaine,nom','offre.region:idRegion,ville'])->where('idCandidat',$r->user()->idCandidat)->latest()->get());
    }
    // CANDIDAT: retirer
    public function destroy(Request $r,$id) {
        $cand=Candidature::where('idCandidature',$id)->where('idCandidat',$r->user()->idCandidat)->firstOrFail();
        if($cand->statut!=='en_attente') return response()->json(['message'=>'Impossible de retirer cette candidature.'],422);
        $cand->delete(); return response()->json(null,204);
    }
    // ENTREPRISE: candidatures d une offre
    public function parOffre(Request $r,$idOffre) {
        Offre::where('idOffre',$idOffre)->where('idEntreprise',$r->user()->idEntreprise)->firstOrFail();
        $q=Candidature::with(['candidat:idCandidat,nom,prenom,email,cv_path,photo,localisation,niveauExperience'])->where('idOffre',$idOffre);
        if($r->filled('statut')) $q->where('statut',$r->statut);
        return response()->json($q->latest()->get());
    }
    // ENTREPRISE: changer statut candidature
    public function changerStatut(Request $r,$id) {
        $cand=Candidature::findOrFail($id);
        Offre::where('idOffre',$cand->idOffre)->where('idEntreprise',$r->user()->idEntreprise)->firstOrFail();
        $r->validate(['statut'=>'required|in:en_attente,en_cours,retenu,refuse']);
        $cand->update(['statut'=>$r->statut]);
        return response()->json($cand->fresh());
    }
    // ADMIN
    public function adminIndex() { return response()->json(Candidature::with(['candidat:idCandidat,nom,prenom','offre:idOffre,titre'])->latest()->paginate(20)); }
}
'@ | Set-Content "$c\CandidatureController.php" -Encoding UTF8

@'
<?php
namespace App\Http\Controllers;
use App\Models\Message;
use Illuminate\Http\Request;
class MessageController extends Controller {
    public function mesConversations(Request $r) {
        $user=$r->user(); $type=$r->attributes->get('user_type');
        if($type==='entreprise') {
            $convs=Message::with('candidat:idCandidat,nom,prenom,photo')->where('idEntreprise',$user->idEntreprise)->selectRaw('idCandidat, MAX(idMessage) as last_id')->groupBy('idCandidat')->get()->map(fn($c)=>Message::with('candidat:idCandidat,nom,prenom,photo')->find($c->last_id));
        } else {
            $convs=Message::with('entreprise:idEntreprise,raisonSociale,logo')->where('idCandidat',$user->idCandidat)->selectRaw('idEntreprise, MAX(idMessage) as last_id')->groupBy('idEntreprise')->get()->map(fn($c)=>Message::with('entreprise:idEntreprise,raisonSociale,logo')->find($c->last_id));
        }
        return response()->json($convs->values());
    }
    public function conversation(Request $r,$id) {
        $user=$r->user(); $type=$r->attributes->get('user_type');
        $q=Message::with(['entreprise:idEntreprise,raisonSociale','candidat:idCandidat,nom,prenom']);
        if($type==='entreprise') $q->where('idEntreprise',$user->idEntreprise)->where('idCandidat',$id);
        else $q->where('idCandidat',$user->idCandidat)->where('idEntreprise',$id);
        $msgs=$q->orderBy('dateEnvoi')->get();
        $msgs->where('lu',false)->where('expediteur',$type==='entreprise'?'candidat':'entreprise')->each(fn($m)=>$m->update(['lu'=>true]));
        return response()->json($msgs);
    }
    public function store(Request $r) {
        $user=$r->user(); $type=$r->attributes->get('user_type');
        $v=$r->validate(['contenu'=>'required|string','idEntreprise'=>'required_if:user_type,candidat|exists:entreprises,idEntreprise','idCandidat'=>'required_if:user_type,entreprise|exists:candidats,idCandidat']);
        $m=Message::create(['idEntreprise'=>$type==='entreprise'?$user->idEntreprise:$v['idEntreprise'],'idCandidat'=>$type==='candidat'?$user->idCandidat:$v['idCandidat'],'contenu'=>$v['contenu'],'dateEnvoi'=>now(),'expediteur'=>$type,'lu'=>false]);
        return response()->json($m->load(['entreprise:idEntreprise,raisonSociale','candidat:idCandidat,nom,prenom']),201);
    }
    public function destroy(Request $r,$id) {
        $m=Message::findOrFail($id); $user=$r->user(); $type=$r->attributes->get('user_type');
        $ok=($type==='entreprise'&&$m->idEntreprise===$user->idEntreprise)||($type==='candidat'&&$m->idCandidat===$user->idCandidat);
        if(!$ok) return response()->json(['message'=>'Non autorisé.'],403);
        $m->delete(); return response()->json(null,204);
    }
}
'@ | Set-Content "$c\MessageController.php" -Encoding UTF8

@'
<?php
namespace App\Http\Controllers;
use App\Models\Certificat;
use Illuminate\Http\Request;
class CertificatController extends Controller {
    public function index(Request $r) { return response()->json(Certificat::where('idCandidat',$r->user()->idCandidat)->get()); }
    public function store(Request $r) {
        $v=$r->validate(['nom'=>'required|string|max:255','organisme'=>'required|string|max:255','dateObtention'=>'required|date']);
        $v['idCandidat']=$r->user()->idCandidat;
        return response()->json(Certificat::create($v),201);
    }
    public function update(Request $r,$id) {
        $c=Certificat::where('id',$id)->where('idCandidat',$r->user()->idCandidat)->firstOrFail();
        $c->update($r->validate(['nom'=>'sometimes|string','organisme'=>'sometimes|string','dateObtention'=>'sometimes|date']));
        return response()->json($c);
    }
    public function destroy(Request $r,$id) { Certificat::where('id',$id)->where('idCandidat',$r->user()->idCandidat)->firstOrFail()->delete(); return response()->json(null,204); }
}
'@ | Set-Content "$c\CertificatController.php" -Encoding UTF8

@'
<?php
namespace App\Http\Controllers;
use App\Models\OffreEnregistree;
use Illuminate\Http\Request;
class OffreEnregistreeController extends Controller {
    public function index(Request $r) { return response()->json(OffreEnregistree::with(['offre.entreprise:idEntreprise,raisonSociale,logo','offre.domaine','offre.region'])->where('idCandidat',$r->user()->idCandidat)->latest()->get()); }
    public function store(Request $r) {
        $r->validate(['idOffre'=>'required|exists:offres,idOffre']);
        if(OffreEnregistree::where('idCandidat',$r->user()->idCandidat)->where('idOffre',$r->idOffre)->exists()) return response()->json(['message'=>'Déjà sauvegardé.'],422);
        return response()->json(OffreEnregistree::create(['idCandidat'=>$r->user()->idCandidat,'idOffre'=>$r->idOffre,'dateAjout'=>now()]),201);
    }
    public function destroy(Request $r,$id) { OffreEnregistree::where('id',$id)->where('idCandidat',$r->user()->idCandidat)->firstOrFail()->delete(); return response()->json(null,204); }
    public function toggle(Request $r,$idOffre) {
        $f=OffreEnregistree::where('idCandidat',$r->user()->idCandidat)->where('idOffre',$idOffre)->first();
        if($f){$f->delete(); return response()->json(['saved'=>false]);}
        OffreEnregistree::create(['idCandidat'=>$r->user()->idCandidat,'idOffre'=>$idOffre,'dateAjout'=>now()]);
        return response()->json(['saved'=>true]);
    }
}
'@ | Set-Content "$c\OffreEnregistreeController.php" -Encoding UTF8

@'
<?php
namespace App\Http\Controllers;
use App\Models\Signalement;
use Illuminate\Http\Request;
class SignalementController extends Controller {
    public function store(Request $r) {
        $user=$r->user(); $type=$r->attributes->get('user_type');
        $v=$r->validate(['cible_type'=>'required|in:offre,candidat,entreprise,message','cible_id'=>'required|integer','description'=>'required|string|min:10']);
        return response()->json(Signalement::create(['signaleur_type'=>get_class($user),'signaleur_id'=>$user->getKey(),'cible_type'=>$v['cible_type'],'cible_id'=>$v['cible_id'],'description'=>$v['description'],'date'=>now()->toDateString(),'statut'=>'en_attente']),201);
    }
    public function index(Request $r) { $q=Signalement::with('admin:idAdmin,nom,prenom')->latest(); if($r->filled('statut'))$q->where('statut',$r->statut); return response()->json($q->paginate(20)); }
    public function show($id) { return response()->json(Signalement::with('admin:idAdmin,nom,prenom')->findOrFail($id)); }
    public function update(Request $r,$id) {
        $s=Signalement::findOrFail($id); $r->validate(['statut'=>'required|in:traite,rejete']);
        $s->update(['statut'=>$r->statut,'idAdmin'=>$r->user()->idAdmin]);
        return response()->json($s);
    }
    public function destroy($id) { Signalement::findOrFail($id)->delete(); return response()->json(null,204); }
}
'@ | Set-Content "$c\SignalementController.php" -Encoding UTF8

@'
<?php
namespace App\Http\Controllers;
use App\Models\{Admin,Candidat,Candidature,Entreprise,Offre,Signalement};
use Illuminate\Http\Request;
class DashboardController extends Controller {
    // ADMIN - tableau de bord avec statistiques (use case: Consulter tableau de bord)
    public function adminStats() {
        return response()->json([
            'entreprises'=>['total'=>Entreprise::count(),'validees'=>Entreprise::where('statut','valide')->count(),'en_attente'=>Entreprise::where('statut','en_attente')->count(),'bloquees'=>Entreprise::where('statut','bloque')->count()],
            'candidats'=>Candidat::count(),'candidats_actifs'=>Candidat::where('actif',true)->count(),
            'offres'=>['total'=>Offre::count(),'actives'=>Offre::where('statut','active')->count(),'expirees'=>Offre::where('statut','expiree')->count(),'suspendues'=>Offre::where('statut','suspendue')->count()],
            'candidatures'=>Candidature::count(),
            'signalements_attente'=>Signalement::where('statut','en_attente')->count(),
            'taux_conversion'=>round(Offre::withCount('candidatures')->get()->avg('candidatures_count')??0,2),
        ]);
    }
    // ENTREPRISE
    public function entrepriseStats(Request $r) {
        $eid=$r->user()->idEntreprise;
        $offres=Offre::where('idEntreprise',$eid)->withCount(['candidatures','candidatures as retenues'=>fn($q)=>$q->where('statut','retenu')])->get();
        return response()->json(['offres_total'=>$offres->count(),'offres_actives'=>$offres->where('statut','active')->count(),'candidatures_total'=>$offres->sum('candidatures_count'),'retenues'=>$offres->sum('retenues'),'offres_detail'=>$offres]);
    }
    // CANDIDAT
    public function candidatStats(Request $r) {
        $cid=$r->user()->idCandidat;
        $c=Candidature::where('idCandidat',$cid)->get();
        return response()->json(['candidatures_total'=>$c->count(),'en_attente'=>$c->where('statut','en_attente')->count(),'en_cours'=>$c->where('statut','en_cours')->count(),'retenues'=>$c->where('statut','retenu')->count(),'refusees'=>$c->where('statut','refuse')->count(),'favoris'=>\App\Models\OffreEnregistree::where('idCandidat',$cid)->count()]);
    }
}
'@ | Set-Content "$c\DashboardController.php" -Encoding UTF8

@'
<?php
namespace App\Http\Controllers;
use App\Models\{Candidat,Entreprise};
use Illuminate\Http\Request;
class AdminGestionController extends Controller {
    // Candidats
    public function candidats() { return response()->json(Candidat::with('domaine:idDomaine,nom')->latest()->paginate(20)); }
    public function showCandidat($id) { return response()->json(Candidat::with(['domaine','certificats'])->findOrFail($id)); }
    public function validerCandidat($id) { $c=Candidat::findOrFail($id); $c->update(['actif'=>true]); return response()->json($c); }
    public function bloquerCandidat($id) { $c=Candidat::findOrFail($id); $c->update(['actif'=>false]); return response()->json($c); }
    public function supprimerCandidat($id) { Candidat::findOrFail($id)->delete(); return response()->json(null,204); }
    // Entreprises
    public function entreprises() { return response()->json(Entreprise::with('domaine:idDomaine,nom')->latest()->paginate(20)); }
    public function validerEntreprise($id) { Entreprise::findOrFail($id)->update(['statut'=>'valide']); return response()->json(Entreprise::findOrFail($id)); }
    public function bloquerEntreprise($id) { Entreprise::findOrFail($id)->update(['statut'=>'bloque']); return response()->json(Entreprise::findOrFail($id)); }
    public function supprimerEntreprise($id) { Entreprise::findOrFail($id)->delete(); return response()->json(null,204); }
}
'@ | Set-Content "$c\AdminGestionController.php" -Encoding UTF8

@'
<?php
namespace App\Http\Controllers;
use App\Models\{Domaine,Categorie,Region};
use Illuminate\Http\Request;
class ReferentielController extends Controller {
    // Domaines
    public function domaines() { return response()->json(Domaine::all()); }
    public function storeDomaine(Request $r) { $v=$r->validate(['nom'=>'required|string|unique:domaines,nom']); return response()->json(Domaine::create($v),201); }
    public function updateDomaine(Request $r,$id) { $d=Domaine::findOrFail($id); $d->update($r->validate(['nom'=>'required|string'])); return response()->json($d); }
    public function destroyDomaine($id) { Domaine::findOrFail($id)->delete(); return response()->json(null,204); }
    // Categories
    public function categories() { return response()->json(Categorie::all()); }
    public function storeCategorie(Request $r) { return response()->json(Categorie::create($r->validate(['nom'=>'required|string|unique:categories,nom'])),201); }
    public function updateCategorie(Request $r,$id) { $c=Categorie::findOrFail($id); $c->update($r->validate(['nom'=>'required|string'])); return response()->json($c); }
    public function destroyCategorie($id) { Categorie::findOrFail($id)->delete(); return response()->json(null,204); }
    // Regions
    public function regions() { return response()->json(Region::all()); }
    public function storeRegion(Request $r) { return response()->json(Region::create($r->validate(['pays'=>'required|string','ville'=>'required|string'])),201); }
    public function updateRegion(Request $r,$id) { $reg=Region::findOrFail($id); $reg->update($r->validate(['pays'=>'sometimes|string','ville'=>'sometimes|string'])); return response()->json($reg); }
    public function destroyRegion($id) { Region::findOrFail($id)->delete(); return response()->json(null,204); }
}
'@ | Set-Content "$c\ReferentielController.php" -Encoding UTF8

Write-Host "  ✓ Controllers crees" -ForegroundColor Green

# ================================================================
# MIDDLEWARE
# ================================================================
@'
<?php
namespace App\Http\Middleware;
use App\Models\Admin;
use Closure; use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
class AdminMiddleware {
    public function handle(Request $request, Closure $next) {
        $pat = PersonalAccessToken::findToken($request->bearerToken() ?? '');
        if(!$pat||!($pat->tokenable instanceof Admin)) return response()->json(['message'=>'Accès réservé à l\'administrateur.'],403);
        $request->setUserResolver(fn()=>$pat->tokenable);
        return $next($request);
    }
}
'@ | Set-Content "app\Http\Middleware\AdminMiddleware.php" -Encoding UTF8

@'
<?php
namespace App\Http\Middleware;
use App\Models\Candidat;
use Closure; use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
class CandidatMiddleware {
    public function handle(Request $request, Closure $next) {
        $pat = PersonalAccessToken::findToken($request->bearerToken() ?? '');
        if(!$pat||!($pat->tokenable instanceof Candidat)) return response()->json(['message'=>'Accès réservé aux candidats.'],403);
        $request->setUserResolver(fn()=>$pat->tokenable);
        $request->attributes->set('user_type','candidat');
        return $next($request);
    }
}
'@ | Set-Content "app\Http\Middleware\CandidatMiddleware.php" -Encoding UTF8

@'
<?php
namespace App\Http\Middleware;
use App\Models\Entreprise;
use Closure; use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
class EntrepriseMiddleware {
    public function handle(Request $request, Closure $next) {
        $pat = PersonalAccessToken::findToken($request->bearerToken() ?? '');
        if(!$pat||!($pat->tokenable instanceof Entreprise)) return response()->json(['message'=>'Accès réservé aux entreprises.'],403);
        $request->setUserResolver(fn()=>$pat->tokenable);
        $request->attributes->set('user_type','entreprise');
        return $next($request);
    }
}
'@ | Set-Content "app\Http\Middleware\EntrepriseMiddleware.php" -Encoding UTF8

@'
<?php
namespace App\Http\Middleware;
use App\Models\{Candidat,Entreprise};
use Closure; use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
class AuthUserMiddleware {
    public function handle(Request $request, Closure $next) {
        $pat = PersonalAccessToken::findToken($request->bearerToken() ?? '');
        if(!$pat) return response()->json(['message'=>'Non authentifié.'],401);
        $t=$pat->tokenable;
        if($t instanceof Entreprise) $request->attributes->set('user_type','entreprise');
        elseif($t instanceof Candidat) $request->attributes->set('user_type','candidat');
        else return response()->json(['message'=>'Non autorisé.'],403);
        $request->setUserResolver(fn()=>$t);
        return $next($request);
    }
}
'@ | Set-Content "app\Http\Middleware\AuthUserMiddleware.php" -Encoding UTF8

Write-Host "  ✓ Middlewares crees" -ForegroundColor Green

# ================================================================
# ROUTES API
# ================================================================
@'
<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\{AuthAdminController,AuthCandidatController,AuthEntrepriseController};
use App\Http\Controllers\{OffreController,CandidatureController,CertificatController,OffreEnregistreeController,MessageController,SignalementController,DashboardController,AdminGestionController,ReferentielController};

// ─── PUBLIQUES ────────────────────────────────────────────────────
Route::post('/admin/login',              [AuthAdminController::class,'login']);
Route::post('/admin/register',           [AuthAdminController::class,'register']);
Route::post('/candidat/register',        [AuthCandidatController::class,'register']);
Route::post('/candidat/login',           [AuthCandidatController::class,'login']);
Route::post('/entreprise/register',      [AuthEntrepriseController::class,'register']);
Route::post('/entreprise/login',         [AuthEntrepriseController::class,'login']);

// Référentiels publics
Route::get('/domaines',   [ReferentielController::class,'domaines']);
Route::get('/categories', [ReferentielController::class,'categories']);
Route::get('/regions',    [ReferentielController::class,'regions']);

// Offres publiques (chercher emploi, consulter details)
Route::get('/offres',      [OffreController::class,'index']);
Route::get('/offres/{id}', [OffreController::class,'show']);

// Entreprises publiques (validées)
Route::get('/entreprises',      [AdminGestionController::class,'entreprises']);

// ─── ADMIN ────────────────────────────────────────────────────────
Route::middleware('admin')->prefix('admin')->group(function() {
    Route::post('/logout', [AuthAdminController::class,'logout']);
    Route::get('/me',      [AuthAdminController::class,'me']);
    Route::get('/dashboard', [DashboardController::class,'adminStats']);

    // Gérer candidats (valider, bloquer)
    Route::get('/candidats',             [AdminGestionController::class,'candidats']);
    Route::get('/candidats/{id}',        [AdminGestionController::class,'showCandidat']);
    Route::patch('/candidats/{id}/valider', [AdminGestionController::class,'validerCandidat']);
    Route::patch('/candidats/{id}/bloquer', [AdminGestionController::class,'bloquerCandidat']);
    Route::delete('/candidats/{id}',     [AdminGestionController::class,'supprimerCandidat']);

    // Gérer entreprises
    Route::get('/entreprises',                 [AdminGestionController::class,'entreprises']);
    Route::patch('/entreprises/{id}/valider',  [AdminGestionController::class,'validerEntreprise']);
    Route::patch('/entreprises/{id}/bloquer',  [AdminGestionController::class,'bloquerEntreprise']);
    Route::delete('/entreprises/{id}',         [AdminGestionController::class,'supprimerEntreprise']);

    // Gérer offres
    Route::get('/offres',                   [OffreController::class,'adminIndex']);
    Route::patch('/offres/{id}/statut',     [OffreController::class,'adminStatut']);

    // Gérer signalements
    Route::get('/signalements',          [SignalementController::class,'index']);
    Route::get('/signalements/{id}',     [SignalementController::class,'show']);
    Route::patch('/signalements/{id}',   [SignalementController::class,'update']);
    Route::delete('/signalements/{id}',  [SignalementController::class,'destroy']);

    // Gérer catégories et domaines
    Route::post('/domaines',         [ReferentielController::class,'storeDomaine']);
    Route::put('/domaines/{id}',     [ReferentielController::class,'updateDomaine']);
    Route::delete('/domaines/{id}',  [ReferentielController::class,'destroyDomaine']);
    Route::post('/categories',         [ReferentielController::class,'storeCategorie']);
    Route::put('/categories/{id}',     [ReferentielController::class,'updateCategorie']);
    Route::delete('/categories/{id}',  [ReferentielController::class,'destroyCategorie']);
    Route::post('/regions',        [ReferentielController::class,'storeRegion']);
    Route::put('/regions/{id}',    [ReferentielController::class,'updateRegion']);
    Route::delete('/regions/{id}', [ReferentielController::class,'destroyRegion']);

    // Candidatures
    Route::get('/candidatures', [CandidatureController::class,'adminIndex']);
});

// ─── CANDIDAT ─────────────────────────────────────────────────────
Route::middleware('candidat')->prefix('candidat')->group(function() {
    Route::post('/logout',      [AuthCandidatController::class,'logout']);
    Route::get('/me',           [AuthCandidatController::class,'me']);
    Route::post('/profile',     [AuthCandidatController::class,'updateProfile']);
    Route::post('/password',    [AuthCandidatController::class,'changePassword']);
    Route::post('/importer-cv', [AuthCandidatController::class,'importerCV']);
    Route::get('/dashboard',    [DashboardController::class,'candidatStats']);

    // Postuler, consulter candidatures
    Route::get('/candidatures',        [CandidatureController::class,'mesCandidatures']);
    Route::post('/candidatures',       [CandidatureController::class,'store']);
    Route::delete('/candidatures/{id}',[CandidatureController::class,'destroy']);

    // Sauvegarder offres
    Route::get('/favoris',                   [OffreEnregistreeController::class,'index']);
    Route::post('/favoris',                  [OffreEnregistreeController::class,'store']);
    Route::delete('/favoris/{id}',           [OffreEnregistreeController::class,'destroy']);
    Route::post('/favoris/toggle/{idOffre}', [OffreEnregistreeController::class,'toggle']);

    // Certificats (ajouterCertification)
    Route::get('/certificats',        [CertificatController::class,'index']);
    Route::post('/certificats',       [CertificatController::class,'store']);
    Route::put('/certificats/{id}',   [CertificatController::class,'update']);
    Route::delete('/certificats/{id}',[CertificatController::class,'destroy']);

    // Messages (envoyer message)
    Route::get('/messages',              [MessageController::class,'mesConversations']);
    Route::get('/messages/{id}',         [MessageController::class,'conversation']);
    Route::post('/messages',             [MessageController::class,'store']);
    Route::delete('/messages/{id}',      [MessageController::class,'destroy']);

    // Signalements
    Route::post('/signalements', [SignalementController::class,'store']);
});

// ─── ENTREPRISE ───────────────────────────────────────────────────
Route::middleware('entreprise')->prefix('entreprise')->group(function() {
    Route::post('/logout',    [AuthEntrepriseController::class,'logout']);
    Route::get('/me',         [AuthEntrepriseController::class,'me']);
    Route::post('/profile',   [AuthEntrepriseController::class,'updateProfile']);
    Route::get('/dashboard',  [DashboardController::class,'entrepriseStats']);

    // Gérer offres (créer, publier, modifier, suspendre, supprimer)
    Route::get('/offres',             [OffreController::class,'mesOffres']);
    Route::post('/offres',            [OffreController::class,'store']);
    Route::put('/offres/{id}',        [OffreController::class,'update']);
    Route::delete('/offres/{id}',     [OffreController::class,'destroy']);
    Route::patch('/offres/{id}/suspendre', [OffreController::class,'suspendre']);

    // Candidatures reçues (consulter, changer statut)
    Route::get('/offres/{idOffre}/candidatures', [CandidatureController::class,'parOffre']);
    Route::patch('/candidatures/{id}/statut',    [CandidatureController::class,'changerStatut']);

    // Messages
    Route::get('/messages',         [MessageController::class,'mesConversations']);
    Route::get('/messages/{id}',    [MessageController::class,'conversation']);
    Route::post('/messages',        [MessageController::class,'store']);
    Route::delete('/messages/{id}', [MessageController::class,'destroy']);

    // Signalements
    Route::post('/signalements', [SignalementController::class,'store']);
});
'@ | Set-Content "routes\api.php" -Encoding UTF8

Write-Host "  ✓ Routes API creees" -ForegroundColor Green

# ================================================================
# BOOTSTRAP / MIDDLEWARE ALIAS
# ================================================================
$bs = Get-Content "bootstrap\app.php" -Raw
if($bs -notmatch 'AdminMiddleware'){
    $bs = $bs -replace "->withMiddleware\(function \(Middleware \`$middleware\) \{", @'
->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin'      => \App\Http\Middleware\AdminMiddleware::class,
            'candidat'   => \App\Http\Middleware\CandidatMiddleware::class,
            'entreprise' => \App\Http\Middleware\EntrepriseMiddleware::class,
            'auth.user'  => \App\Http\Middleware\AuthUserMiddleware::class,
        ]);
'@
    Set-Content "bootstrap\app.php" $bs -Encoding UTF8
}

# ================================================================
# AUTH CONFIG
# ================================================================
@'
<?php
return [
    'defaults' => ['guard'=>'web','passwords'=>'candidats'],
    'guards' => [
        'web'        => ['driver'=>'session','provider'=>'users'],
        'admin'      => ['driver'=>'sanctum','provider'=>'admins'],
        'candidat'   => ['driver'=>'sanctum','provider'=>'candidats'],
        'entreprise' => ['driver'=>'sanctum','provider'=>'entreprises'],
    ],
    'providers' => [
        'users'       => ['driver'=>'eloquent','model'=>App\Models\Candidat::class],
        'admins'      => ['driver'=>'eloquent','model'=>App\Models\Admin::class],
        'candidats'   => ['driver'=>'eloquent','model'=>App\Models\Candidat::class],
        'entreprises' => ['driver'=>'eloquent','model'=>App\Models\Entreprise::class],
    ],
    'passwords' => [
        'candidats'   => ['provider'=>'candidats',  'table'=>'password_reset_tokens','expire'=>60,'throttle'=>60],
        'entreprises' => ['provider'=>'entreprises','table'=>'password_reset_tokens','expire'=>60,'throttle'=>60],
        'admins'      => ['provider'=>'admins',     'table'=>'password_reset_tokens','expire'=>60,'throttle'=>60],
    ],
    'password_timeout' => 10800,
];
'@ | Set-Content "config\auth.php" -Encoding UTF8

# ================================================================
# SEEDER
# ================================================================
@'
<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\{Admin,Domaine,Categorie,Region,Entreprise,Candidat,Offre,Candidature};
class DatabaseSeeder extends Seeder {
    public function run(): void {
        Admin::firstOrCreate(['email'=>'admin@jobplatform.tn'],['nom'=>'Super','prenom'=>'Admin','email'=>'admin@jobplatform.tn','motDePasse'=>Hash::make('Admin@1234')]);
        $domaines=['Informatique & Tech','Finance & Comptabilité','Marketing & Communication','Ressources Humaines','Santé & Médecine','Ingénierie & Industrie','Commerce & Vente','Éducation & Formation','Droit & Juridique','Architecture & BTP'];
        $dIds=[]; foreach($domaines as $n){$d=Domaine::firstOrCreate(['nom'=>$n]); $dIds[]=$d->idDomaine;}
        $categories=['Junior','Senior','Manager','Expert','Stagiaire','Alternant'];
        $cIds=[]; foreach($categories as $n){$c=Categorie::firstOrCreate(['nom'=>$n]); $cIds[]=$c->idCategorie;}
        $regions=[['Tunisie','Tunis'],['Tunisie','Sfax'],['Tunisie','Sousse'],['Tunisie','Bizerte'],['Tunisie','Gabes'],['Tunisie','Nabeul'],['Tunisie','Monastir'],['Tunisie','Ariana'],['Tunisie','Kairouan'],['Tunisie','Gafsa']];
        $rIds=[]; foreach($regions as [$p,$v]){$reg=Region::firstOrCreate(['pays'=>$p,'ville'=>$v]); $rIds[]=$reg->idRegion;}

        // Entreprise demo
        $e=Entreprise::firstOrCreate(['email'=>'rh@techcorp.tn'],['raisonSociale'=>'TechCorp Tunisie','identifiantLegal'=>'TN-2024-001','description'=>'Société leader en solutions informatiques.','idDomaine'=>$dIds[0],'pays'=>'Tunisie','ville'=>'Tunis','email'=>'rh@techcorp.tn','motDePasse'=>Hash::make('Entreprise@1234'),'statut'=>'valide']);

        // Candidat demo
        $c=Candidat::firstOrCreate(['email'=>'candidat@demo.tn'],['nom'=>'Ben Ali','prenom'=>'Mehdi','email'=>'candidat@demo.tn','motDePasse'=>Hash::make('Candidat@1234'),'idDomaine'=>$dIds[0],'specialite'=>'Développement Web','niveauEtude'=>'Bac+5 (Master)','niveauExperience'=>'Junior (1-3 ans)','localisation'=>'Tunis']);

        // Offres demo
        if(Offre::count()===0){
            $types=['CDI','CDD','Stage','Freelance','Alternance'];
            for($i=0;$i<6;$i++){
                Offre::create(['idEntreprise'=>$e->idEntreprise,'idDomaine'=>$dIds[$i%count($dIds)],'idCategorie'=>$cIds[$i%count($cIds)],'idRegion'=>$rIds[$i%count($rIds)],'titre'=>['Développeur Laravel','Chef de Projet IT','Data Analyst','UX/UI Designer','DevOps Engineer','Consultant SAP'][$i],'typeContrat'=>$types[$i%5],'description'=>'Poste passionnant dans une équipe dynamique. Vous serez responsable du développement et de la maintenance des applications.','competencesRequises'=>'PHP, Laravel, MySQL, REST API','remuneration'=>2000+($i*500),'datePublication'=>now()->subDays($i)->toDateString(),'dateLimite'=>now()->addDays(30+$i)->toDateString(),'statut'=>'active','localisation'=>$regions[$i%count($regions)][1]]);
            }
        }
        $this->command->info("Seeder OK ✓  |  admin@jobplatform.tn / Admin@1234");
        $this->command->info("Entreprise: rh@techcorp.tn / Entreprise@1234");
        $this->command->info("Candidat:   candidat@demo.tn / Candidat@1234");
    }
}
'@ | Set-Content "database\seeders\DatabaseSeeder.php" -Encoding UTF8

# ================================================================
# FINALISER
# ================================================================
php artisan storage:link 2>$null
php artisan migrate:fresh --seed
php artisan config:clear
php artisan route:clear
Write-Host "`n✅ BACKEND COMPLET !" -ForegroundColor Green