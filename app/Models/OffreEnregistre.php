<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OffreEnregistre extends Model
{
    use HasFactory;

    protected $fillable = [
        'idCandidat',
        'idOffre',
        'dateAjout',
    ];

    protected $casts = [
        'dateAjout' => 'date',
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
