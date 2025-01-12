<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Katagori extends Model
{
  protected $guarded = ['id'];

    public function barang(){
        return $this->hasMany(Barang::class,'katagori_id','id');
    }
}
