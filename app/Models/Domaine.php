<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domaine extends Model
{
    protected $table = "domaines";
    protected $primaryKey = "idDomaine";

    protected $fillable = ["nom"];

    public function offres()
    {
        return $this->hasMany(Offre::class, "idDomaine");
    }

    public function candidats()
    {
        return $this->hasMany(Candidat::class, "idDomaine");
    }
}