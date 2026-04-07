<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = "admins";
    protected $primaryKey = "idAdmin";

    protected $fillable = ["nom", "prenom", "email", "password"];

    protected $hidden = ["password", "remember_token"];

    public function signalements()
    {
        return $this->hasMany(Signalement::class, "idAdmin");
    }
}