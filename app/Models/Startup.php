<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Startup extends Model
{
    protected $fillable = [
        'nom', 'description', 'secteur_id', 'admin_id',
    ];

    public function secteur()
    {
        return $this->belongsTo(Secteur::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
