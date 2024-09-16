<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Http\Resources\PatientResource;
use Illuminate\Http\Request;


class PatientController extends Controller
{
    public function show(string $id){
        $patient = Patient::findOrFail($id);
        return response()->json([
            'patient_name' => $patient->user->name
        ]);
    }

    public function archive(){
        $prescriptions = auth()->user()->patient()->first()->prescriptions()
            ->with('doctor.user')
            ->where('status', 1)
            ->get();
        return PatientResource::collection($prescriptions);
    }
}
