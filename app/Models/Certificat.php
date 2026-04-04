<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificat extends Model
{
    use HasFactory;

    protected $fillable = [
        'idCandidat',
        'nom',
        'organisme',
        'dateObtention',
    ];

    protected $casts = [
        'dateObtention' => 'date',
    ];

    public function candidat()
    {
        return $this->belongsTo(candidats::class, 'idCandidat', 'id');
    }
}
