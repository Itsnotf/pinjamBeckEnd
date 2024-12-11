<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $guarded = ['id'];

    public function users(){
        return $this->belongsTo(User::class, 'id_user');
    }

    public function barang(){
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}
