<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    protected $primaryKey = 'idRegion';

    protected $fillable = [
        'pays',
        'ville',
    ];

    public function offres()
    {
        return $this->hasMany(Offre::class, 'idRegion', 'idRegion');
    }
}
