<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\DoctorResource;

class DoctorController extends Controller
{
    
    public function archive(){
        $prescriptions = auth()->user()->doctor()->first()->prescriptions()
            ->with('patient.user')
            ->get();
        return DoctorResource::collection($prescriptions);
    }

}
