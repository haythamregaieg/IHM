<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offre extends Model
{
    use HasFactory;

    protected $primaryKey = 'idOffre';

    protected $fillable = [
        'idEntreprise',
        'idDomaine',
        'idCategorie',
        'idRegion',
        'titre',
        'typeContrat',
        'description',
        'competencesRequises',
        'remuneration',
        'datePublication',
        'dateLimite',
        'statut',
        'localisation',
    ];

    protected $casts = [
        'datePublication' => 'date',
        'dateLimite' => 'date',
    ];

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class, 'idEntreprise', 'id');
    }

    public function domaine()
    {
        return $this->belongsTo(Domaine::class, 'idDomaine', 'idDomaine');
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'idCategorie', 'idCategorie');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'idRegion', 'idRegion');
    }

    public function candidatures()
    {
        return $this->hasMany(Candidature::class, 'idOffre', 'idOffre');
    }

    public function favoris()
    {
        return $this->hasMany(OffreEnregistre::class, 'idOffre', 'idOffre');
    }
}
