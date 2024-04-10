<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['payment_id', 'id_investisseur', 'id_floucis'];

    /**
     * Get the user (investisseur) associated with the transaction.
     */
    public function investisseur()
    {
        return $this->belongsTo(User::class, 'id_investisseur');
    }

    /**
     * Get the Flouci account associated with the transaction.
     */
    public function flouci()
    {
        return $this->belongsTo(Flouci::class, 'id_floucis');
    }




}
