<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Signalement extends Model
{
    protected $table = 'signalements';
    protected $primaryKey = 'idSignalement';

    protected $fillable = [
        'idAdmin', 'signaleur_type', 'signaleur_id',
        'cible_type', 'cible_id', 'description', 'date', 'statut',
    ];

    protected $casts = ['date' => 'date'];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'idAdmin');
    }

    public function signaleur()
    {
        return $this->morphTo();
    }

    public function cible()
    {
        return $this->morphTo();
    }
}
