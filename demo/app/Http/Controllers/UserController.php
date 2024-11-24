<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index()
    {
        $users = User::orderBy("created_at")->get();
        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::find($id);
        return response()->json($user);
    }

    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
            'otc' => 'required|string|size:6',
        ]);

        // Verify the OTC
        $otcRecord = DB::table('otc_user')->where('userId', $user->id)->first();

        if (!$otcRecord || $otcRecord->otc_passcode !== $request->otc) {
            return response()->json(['message' => 'Invalid OTC'], 400);
        }

        // Update the password
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the OTC after successful password update
        DB::table('otc_user')->where('userId', $user->id)->delete();

        return response()->json(['message' => 'Password updated successfully.']);
    }


    public function generateOTC($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Generate a random 6-digit OTC
        $otc = random_int(100000, 999999);

        // Set the expiration time to 3 minutes from now
        $expiresAt = Carbon::now()->addMinutes(3);

        // Save or update the OTC in the otc_user table
        DB::table('otc_user')->updateOrInsert(
            ['userId' => $user->id],
            [
                'otc_passcode' => $otc,
                'expires_at' => $expiresAt,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Return the OTC (Note: In a real-world scenario, you should send this via email or SMS)
        return response()->json(['otc' => $otc]);
    }

    public function displayOTC($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Fetch the existing OTC for the user
        $otcRecord = DB::table('otc_user')
            ->where('userId', $user->id)
            ->first();

        // Check if OTC exists and is not expired
        if ($otcRecord && $otcRecord->expires_at && Carbon::now()->lessThanOrEqualTo($otcRecord->expires_at)) {
            // OTC is valid, return it
            return response()->json(['otc' => $otcRecord->otc_passcode]);
        } else {
            // OTC does not exist or is expired
            return response()->json(['message' => 'No valid OTC found'], 404);
        }
    }
}
