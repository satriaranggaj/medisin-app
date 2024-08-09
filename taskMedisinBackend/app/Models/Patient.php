<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'medical_record_number',
        'name',
        'id_number',
        'address',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($patient) {
            $latestPatient = Patient::lockForUpdate()->orderBy('created_at', 'desc')->first();
            $lastNumber = $latestPatient ? intval(substr($latestPatient->medical_record_number, 2)) : 0;
            $patient->medical_record_number = 'RM' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        });
    }
}

