<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\PasswordReset;
use Carbon\Carbon;
use Hash;
use DB;

class PasswordResetController extends Controller
{
    public function requestOtp(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);
        $otp = random_int(1000, 9999);
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            ['otp' => $otp, 'created_at' => now()]
        );
        Mail::to($request->email)->send(new PasswordReset($otp));
    }

    public function checkOtp(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:4'
        ]);
        $password_reset = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();

        if(! $password_reset){
            return response()->json([
                'status' => false,
                'message' => 'Invalid OTP'
            ]);
        }

        if(Carbon::parse($password_reset->created_at)->addMinutes(15)->isPast()){
            return response()->json([
                'status' => false,
                'message' => 'OTP has been expired'
            ]);
        }

        DB::table('password_resets')->where('email', $request->email)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Valid OTP'
        ]);
    }

    public function updatePassword(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed|min:8'
        ]);

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json([
            'message' => 'Password updated successfully'
        ],200);
    }
}
