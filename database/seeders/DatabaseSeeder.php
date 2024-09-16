<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Pharmacist;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::insert([
            [
                'name' => 'doctor',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'patient',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'pharmacist',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        User::insert([
            [
                'name' => 'John Doe',
                'email' => 'johndoe@gmail.com',
                'password' => Hash::make('12345678'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'D.John Doe',
                'email' => 'djohndoe@gmail.com',
                'password' => Hash::make('12345678'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'P.John Doe',
                'email' => 'pjohndoe@gmail.com',
                'password' => Hash::make('12345678'),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);


        $patient = Patient::create([
            'user_id' => 1,
            'DOB' => '2000-10-10',
            'gender' => 'male',
            'blood_type' => 'A+',
            'phone_number' => '0912345678'
        ]);

        $role = Role::where('name', 'patient')->first();
        User::find(1)->roles()->attach($role);

        $doctor = Doctor::create([
            'user_id' => 2,
            'certificate' => 'image.jpg',
            'specialization' => 'surgery',
        ]);

        $role = Role::where('name', 'doctor')->first();
        User::find(2)->roles()->attach($role);

        $pharmacist = Pharmacist::create([
            'user_id' => 3,
            'certificate' => 'image.jpg',
        ]);

        $role = Role::where('name', 'pharmacist')->first();
        User::find(3)->roles()->attach($role);


        Prescription::insert([
            [
                'patient_id' => 1,
                'doctor_id' => 1,
                'prescription_items_count' => 1,
                'prescription_items_completed' => 0,
                'status' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'patient_id' => 1,
                'doctor_id' => 1,
                'prescription_items_count' => 1,
                'prescription_items_completed' => 1,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        PrescriptionItem::insert([
            [
                'prescription_id' => 1,
                'name' => 'Panadol',
                'patient_condition' => 'Fever',
                'start_date' => null,
                'dosage' => 12,
                'last_taken' => null,
                'next_remainder' => null,
                'quantity' => 5,
                'quantity_taken' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'prescription_id' => 2,
                'name' => 'Panadol',
                'patient_condition' => 'Fever',
                'start_date' => null,
                'dosage' => 12,
                'last_taken' => null,
                'next_remainder' => null,
                'quantity' => 5,
                'quantity_taken' => 5,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
