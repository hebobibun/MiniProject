<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobField extends Model
{
    use HasFactory;

    protected $table = "tb_jobfield";
    protected $id = "id";

    public function jobs() {
        return $this->hasMany('App\Models\Jobs', 'id', 'id_jobfield');
    }
}
