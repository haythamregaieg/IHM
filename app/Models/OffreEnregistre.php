<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OffreEnregistre extends Model
{
    protected $table = 'offre_enregistres';

    protected $fillable = ['idCandidat', 'idOffre', 'dateAjout'];

    protected $casts = ['dateAjout' => 'datetime'];

    public function candidat()
    {
        return $this->belongsTo(Candidat::class, 'idCandidat');
    }

    public function offre()
    {
        return $this->belongsTo(Offre::class, 'idOffre', 'idOffre');
    }
}
