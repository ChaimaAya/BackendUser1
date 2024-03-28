<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Secteur extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nom',
    ];

    /**
     * Get the startups associated with the sector.
     */
    public function startups()
    {
        return $this->hasMany(Startup::class);
    }

    /**
     * Get the admins associated with the sector.
     */
    public function admins()
    {
        return $this->belongsToMany(User::class, 'admins_secteurs', 'secteur_id', 'admin_id');
    }
}
