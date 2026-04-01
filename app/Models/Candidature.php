<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidature extends Model
{
    use HasFactory;

    protected $primaryKey = 'idCandidature';

    protected $fillable = [
        'idCandidat',
        'idOffre',
        'dateCandidature',
        'statut',
        'lettreMotivation',
    ];

    protected $casts = [
        'dateCandidature' => 'date',
    ];

    public function candidat()
    {
        return $this->belongsTo(candidats::class, 'idCandidat', 'id');
    }

    public function offre()
    {
        return $this->belongsTo(Offre::class, 'idOffre', 'idOffre');
    }
}
