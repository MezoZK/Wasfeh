<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Prescription;

class PrescriptionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'prescription_id', 'name', 'patient_condition', 'start_date', 'dosage','last_taken',
        'next_remainder', 'instructions', 'quantity', 'quantity_taken',   
    ];

    public function prescription(){
        return $this->belongsTo(Prescription::class);
    }

}
