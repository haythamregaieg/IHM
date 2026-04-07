<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificat extends Model
{
    protected $table = 'certificats';

    protected $fillable = ['idCandidat', 'nom', 'organisme', 'dateObtention'];

    protected $casts = ['dateObtention' => 'date'];

    public function candidat()
    {
        return $this->belongsTo(Candidat::class, 'idCandidat');
    }
}
