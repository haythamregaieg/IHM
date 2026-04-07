<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';
    protected $primaryKey = 'idMessage';

    protected $fillable = [
        'idEntreprise', 'idCandidat', 'contenu', 'dateEnvoi', 'expediteur', 'lu',
    ];

    protected $casts = ['dateEnvoi' => 'datetime', 'lu' => 'boolean'];

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class, 'idEntreprise');
    }

    public function candidat()
    {
        return $this->belongsTo(Candidat::class, 'idCandidat');
    }
}
