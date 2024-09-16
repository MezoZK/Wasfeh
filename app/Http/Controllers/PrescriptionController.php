<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prescription;
use App\Models\Patient;
use App\Models\PrescriptionItem;
use App\Http\Resources\patientPrescriptionsResource;
use Carbon\Carbon;

class PrescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'prescription_items.*.name' => 'required|string|max:255',
            'prescription_items.*.patient_condition' => 'required|string|max:255',
            'prescription_items.*.dosage' => 'required|numeric|min:1',
            'prescription_items.*.instructions' => 'string',
            'prescription_items.*.quantity' => 'required|numeric|min:1'
        ]);

        $prescription = Prescription::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => auth()->user()->first()->id,
            'prescription_items_count' => count($request->prescription_items),
        ]);

        $prescription->prescriptionItems()->createMany($request->prescription_items);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $prescription = Prescription::with(['prescriptionItems', 'doctor.user', 'patient.user'])->findOrFail($id);

        return $prescription;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
        $request->validate([
            'prescription_items.*.name' => 'required|string|max:255',
            'prescription_items.*.patient_condition' => 'required|string|max:255',
            'prescription_items.*.dosage' => 'required|numeric|min:1',
            'prescription_items.*.instructions' => 'string',
            'prescription_items.*.quantity' => 'required|numeric|min:1'
        ]);

        $prescription = Prescription::find($id);

        foreach($request->prescription_items as $prescription_item){
            if($prescription_item['id'] != null){
                PrescriptionItem::findOrFail($prescription_item['id'])->update($prescription_item);
            }else{
                $prescription->prescription_items_count++;
                $prescription->prescriptionItems()->create($prescription_item);
            }
        }

        $prescription->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Prescription::findOrFail($id)->delete();
        PrescriptionItem::where('prescription_id', $id)->delete();
    }

    public function updateDate(string $id){
        $prescription_item = PrescriptionItem::findOrFail($id);
        if($prescription_item->start_date == null){
            $prescription_item->start_date = now();
        }

        if(++$prescription_item->quantity_taken == $prescription_item->quantity){
            $prescription_item->next_remainder = null;
            $prescription = $prescription_item->prescription;
            $prescription->prescription_items_completed++;
            $prescription->save();
            $prescription->chackStatus();
        }else{
            $prescription_item->next_remainder = Carbon::parse(now())->addHours($prescription_item->dosage);
        }

        $prescription_item->last_taken = now();
        
        return $prescription_item->save();
    }

    public function activePrescriptionItems(){
        $active_prescriptions = auth()->user()->patient->prescriptions()
        ->where('status', 0)
        ->with(['prescriptionItems' => function($query){
                $query->whereColumn('quantity_taken', '<', 'quantity');
        }, 'doctor' => function($query){
                $query->select('id', 'user_id');
        }, 'doctor.user' => function($query){
                $query->select('id', 'name');
        }])
        ->get();
        return $active_prescriptions;
    }


    public function destroyPrescriptionItem(string $id){
        $prescription_item = PrescriptionItem::findOrFail($id);
        $prescription = $prescription_item->prescription;
        $prescription->prescription_items_count--;
        if($prescription_item->quantity == $prescription_item->quantity_taken){
            $prescription->prescription_items_completed--;
        }
        $prescription->save();
        $prescription_item->delete();
        if($prescription->prescription_items_count==0){
            $prescription->delete();
        }
    }


    public function patientPrescriptions(string $id){
        $patient = Patient::findOrFail($id);
        $prescriptions = $patient->prescriptions()->with('prescriptionItems')->get();
        return patientPrescriptionsResource::collection($prescriptions);
    }
    
}
