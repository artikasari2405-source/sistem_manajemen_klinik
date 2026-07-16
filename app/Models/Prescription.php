<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $guarded = ['id'];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}
