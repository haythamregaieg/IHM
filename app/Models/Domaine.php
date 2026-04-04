<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domaine extends Model
{
    use HasFactory;

    protected $primaryKey = 'idDomaine';

    protected $fillable = [
        'nom',
    ];

    public function offres()
    {
        return $this->hasMany(Offre::class, 'idDomaine', 'idDomaine');
    }
}
