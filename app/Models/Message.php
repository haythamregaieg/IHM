<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $primaryKey = 'idMessage';

    protected $fillable = [
        'idEntreprise',
        'idCandidat',
        'contenu',
        'dateEnvoi',
    ];

    protected $casts = [
        'dateEnvoi' => 'date',
    ];

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class, 'idEntreprise', 'id');
    }

    public function candidat()
    {
        return $this->belongsTo(candidats::class, 'idCandidat', 'id');
    }
}
