<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\PharmacistResource;
use App\Models\Prescription;

class PharmacistController extends Controller
{
    
    public function archive(){
        $prescriptions = auth()->user()->pharmacist()->first()->prescriptions()
            ->with('patient.user')
            ->get();
        return PharmacistResource::collection($prescriptions);
    }

    public function purchase(string $id){
        $prescription = Prescription::findOrFail($id);
        $prescription->purchased = true;
        $prescription->pharmacist_id = auth()->user()->pharmacist()->first()->id;
        $prescription->save();
    }

}
