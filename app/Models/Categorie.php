<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

    protected $primaryKey = 'idCategorie';

    protected $fillable = [
        'nom',
    ];

    public function offres()
    {
        return $this->hasMany(Offre::class, 'idCategorie', 'idCategorie');
    }
}
