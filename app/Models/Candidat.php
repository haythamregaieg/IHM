<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Candidat extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'candidats';
    protected $primaryKey = 'idCandidat';

    protected $fillable = [
        'nom', 'prenom', 'email', 'password', 'telephone',
        'idDomaine', 'specialite', 'niveau_etudes', 'niveau_experience',
        'localisation', 'cv_path', 'photo_path',
    ];

    protected $hidden = ['password', 'remember_token'];

    public function domaine()
    {
        return $this->belongsTo(Domaine::class, 'idDomaine', 'idDomaine');
    }

    public function candidatures()
    {
        return $this->hasMany(Candidature::class, 'idCandidat');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'idCandidat');
    }

    public function certificats()
    {
        return $this->hasMany(Certificat::class, 'idCandidat');
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class, 'idCandidat', 'idCandidat');
    }

    public function offresEnregistrees()
    {
        return $this->hasMany(OffreEnregistre::class, 'idCandidat');
    }
}
