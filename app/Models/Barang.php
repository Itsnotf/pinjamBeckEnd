<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
  protected $guarded = ['id'];

  public function peminjaman(){
    return $this->hasMany(Peminjaman::class,'id_barang','id');
  }
    public function katagori(){
        return $this->belongsTo(Katagori::class,'katagori_id');
    }
}
