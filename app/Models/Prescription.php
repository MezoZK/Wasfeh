<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\prescriptionItems;
use App\Models\Doctor;
use App\Models\Patient;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'doctor_id', 'status', 'purchased', 'prescription_items_count', 'prescription_items_completed'
    ];

    public function prescriptionItems(){
        return $this->hasMany(prescriptionItem::class);
    }

    public function doctor(){
        return $this->belongsTo(Doctor::class);
    }

    public function patient(){
        return $this->belongsTo(Patient::class);
    }

    public function chackStatus(){
        if($this->prescription_items_completed == $this->prescription_items_count){
            $this->status = 1;
            $this->save();
        }
    }
}
