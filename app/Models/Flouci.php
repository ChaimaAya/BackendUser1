<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flouci extends Model
{
    use HasFactory;
    protected $fillable = ['app_public', 'app_secret', 'id_startup'];

    public function startup()
    {
        return $this->belongsTo(Startup::class, 'id_startup');
    }
}
