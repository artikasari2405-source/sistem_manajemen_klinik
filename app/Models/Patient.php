<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $guarded = ['id'];

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
}
