<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'vehicle',
        'first_name',
        'last_name',
        'nickname',
        'address1',
        'address2',
        'address3',
        'address4',
        'address5',
        'address6',
        'mobile',
        'next_of_kin',
        'nok_phone',
        'nok_alt_phone',
        'dob',
        'allergies',
        'dietary_requirement',
        'past_medical_history',
        'current_medical_history',
        'current_medications',
        'vehicle_image',
        'images',
        'comments',
        'destroy_date',
    ];

    protected $casts = [
        'dob' => 'date',
        'destroy_date' => 'date',
        'images' => 'array',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
