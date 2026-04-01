<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signalement extends Model
{
    use HasFactory;

    protected $primaryKey = 'idSignalement';

    protected $fillable = [
        'idAdmin',
        'description',
        'date',
        'statut',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function admin()
    {
        return $this->belongsTo(admins::class, 'idAdmin', 'id');
    }
}
