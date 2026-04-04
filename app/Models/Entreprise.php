<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entreprise extends Model
{
    use HasFactory;

    public function offres()
    {
        return $this->hasMany(Offre::class, 'idEntreprise', 'id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'idEntreprise', 'id');
    }
}
