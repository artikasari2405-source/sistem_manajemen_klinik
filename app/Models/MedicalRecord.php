<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    protected $guarded = ['id'];

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function recordTreatments()
    {
        return $this->hasMany(RecordTreatment::class);
    }
}
