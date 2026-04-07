<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $table = "regions";
    protected $primaryKey = "idRegion";

    protected $fillable = ["pays", "ville"];

    public function offres()
    {
        return $this->hasMany(Offre::class, "idRegion");
    }
}