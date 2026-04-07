<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offre extends Model
{
    protected $table = 'offres';
    protected $primaryKey = 'idOffre';

    protected $fillable = [
        'idEntreprise', 'idDomaine', 'idCategorie', 'idRegion',
        'titre', 'typeContrat', 'description', 'competencesRequises', 'experience',
        'mode_travail', 'niveau_etude',
        'remuneration', 'datePublication', 'dateLimite', 'statut', 'localisation', 'vues',
    ];

    protected $casts = [
        'datePublication' => 'date',
        'dateLimite' => 'date',
        'remuneration' => 'float',
    ];

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class, 'idEntreprise');
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

    public function offresEnregistrees()
    {
        return $this->hasMany(OffreEnregistre::class, 'idOffre', 'idOffre');
    }
}
