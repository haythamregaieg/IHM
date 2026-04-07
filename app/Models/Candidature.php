<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidature extends Model
{
    protected $table = 'candidatures';
    protected $primaryKey = 'idCandidature';

    protected $fillable = [
        'idCandidat', 'idOffre', 'dateCandidature',
        'statut', 'lettreMotivation', 'cv_path',
        'telephone', 'email_contact', 'specialite', 'niveau_experience',
    ];

    protected $casts = ['dateCandidature' => 'date'];

    public function candidat()
    {
        return $this->belongsTo(Candidat::class, 'idCandidat', 'idCandidat');
    }

    public function offre()
    {
        return $this->belongsTo(Offre::class, 'idOffre', 'idOffre');
    }
}
