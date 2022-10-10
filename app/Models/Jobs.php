<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jobs extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "tb_jobs";
    protected $id = "id";

    public function jobfield() {

        return $this->belongsTo('\App\Models\JobField', 'id_jobfield');
    }
}
