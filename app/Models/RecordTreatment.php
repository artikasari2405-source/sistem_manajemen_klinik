<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecordTreatment extends Model
{
    protected $guarded = ['id'];

    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }
}
