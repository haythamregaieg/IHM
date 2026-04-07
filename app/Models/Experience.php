<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

    protected $fillable = [
        'idCandidat',
        'poste',
        'entreprise',
        'dateDebut',
        'dateFin',
        'description',
    ];

    public function candidat()
    {
        return $this->belongsTo(Candidat::class, 'idCandidat', 'idCandidat');
    }
}
