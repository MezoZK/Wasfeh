<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Prescription;

class Doctor extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'specialization', 'certificate'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function prescriptions(){
        return $this->hasMany(Prescription::class);
    }
}
