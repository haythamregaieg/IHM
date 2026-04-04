<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class candidats extends Model
{
    use HasFactory;

    public function candidatures()
    {
        return $this->hasMany(Candidature::class, 'idCandidat', 'id');
    }

    public function certificats()
    {
        return $this->hasMany(Certificat::class, 'idCandidat', 'id');
    }

    public function offresEnregistrees()
    {
        return $this->hasMany(OffreEnregistre::class, 'idCandidat', 'id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'idCandidat', 'id');
    }
}
