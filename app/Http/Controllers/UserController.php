<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Pharmacist;
use Illuminate\Validation\Rule;
use Hash;

class UserController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['doctor', 'patient', 'pharmacist'])],
        ]);

        $role = Role::where('name', $request->role)->first();

        if($role->name == 'doctor'){
            $request->validate([
                'certificate' => 'required|mimes:jpeg,png,jpg,pdf|max:2048',
                'specialization' => 'required|string'
            ]);
        }
        if($role->name == 'patient'){
            $request->validate([
                'gender' => ['required', Rule::in(['male', 'female'])],
                'blood_type' => ['required', Rule::in(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'])],
                'phone_number' => 'required|string|min:10|max:10',
                'DOB' => 'required|date'
            ]);
        }
        if($role->name == 'pharmacist'){
            $request->validate([
                'certificate' => 'required|mimes:jpeg,png,jpg,pdf|max:2048'
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);


        $user->roles()->attach($role);

        if ($request->hasFile('certificate')) {
            $img = $request->file('certificate');
            $img_name = time(). '-' . $img->getClientOriginalName();
            $img_path = $img->storeAs('uploads', $img_name);
        }

        if($role->name == 'doctor'){
            Doctor::create([
                'user_id' => $user->id,
                'specialization' => $request->specialization,
                'certificate' => $img_path,
            ]);
        }
        if($role->name == 'pharmacist'){
            Pharmacist::create([
                'user_id' => $user->id,
                'certificate' => $img_path,
            ]);
        }
        if($role->name == 'patient'){
            Patient::create([
                'user_id' => $user->id,
                'gender' => $request->gender,
                'blood_type' => $request->blood_type,
                'phone_number' => $request->phone_number,
                'DOB' => $request->DOB
            ]);
        }

        return $this->login($request);

    }


    public function login(Request $request){
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required',
            'role' => ['required', 'string', Rule::in(['doctor', 'patient', 'pharmacist'])]
        ]);

        $user = User::where('email', $request->email)->first();
        if(!$user || !Hash::check($request->password, $user->password) || !$user->hasRole($request->role)){
            return response()->json([
                'message' => 'Invalid Credentials'
            ],401);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'access_token' => $token
        ]);

    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return response()->json([
            'message' => 'logged out'
        ]);
    }

}
