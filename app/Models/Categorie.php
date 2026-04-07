<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $table = "categories";
    protected $primaryKey = "idCategorie";

    protected $fillable = ["nom"];

    public function offres()
    {
        return $this->hasMany(Offre::class, "idCategorie");
    }
}