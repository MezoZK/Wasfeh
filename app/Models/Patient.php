<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Prescription;

class Patient extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'DOB', 'sex', 'blood_type', 'phone_number'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function prescriptions(){
        return $this->hasMany(Prescription::class);
    }
}
