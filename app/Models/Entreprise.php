<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Entreprise extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'entreprises';
    protected $primaryKey = 'idEntreprise';

    protected $fillable = [
        'raison_sociale', 'description', 'identifiant_legal',
        'idDomaine', 'idRegion', 'telephone', 'email', 'password',
        'logo_path', 'site_web', 'statut',
    ];

    protected $hidden = ['password', 'remember_token'];

    public function domaine()
    {
        return $this->belongsTo(Domaine::class, 'idDomaine', 'idDomaine');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'idRegion', 'idRegion');
    }

    public function offres()
    {
        return $this->hasMany(Offre::class, 'idEntreprise');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'idEntreprise');
    }
}
