<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = [
        'name',
        'specialty',
        'phone',
        'image',
        'description',
    ];

    // 🔥 العلاقة مع المواعيد (Appointments)
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
