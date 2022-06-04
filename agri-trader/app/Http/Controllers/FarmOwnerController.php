<?php

namespace App\Http\Controllers;

use App\Models\FarmOwner;
use App\Models\FarmOwnerAddress;
use App\Models\FarmOwnerContactNumber;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class FarmOwnerController extends Controller
{
    public function add(Request $request)
    {
        $user = $request->validate([
            'firstName' => 'required|string|unique:farm_owners,owner_firstName',
            'lastName' => 'required|string|unique:farm_owners,owner_lastName',
            'gender' => 'required|string',
            'contactNum' => 'required|unique:farm_owner_contact_numbers,owner_contactNum',
            'birthDate' => 'required|date',
            'email' => 'required|email|unique:farm_owners,owner_email|unique:users,email',
            'province' => 'required|string',
            'address' => 'required|string',
            'zipcode' => 'required|string',
            'role' => 'required|int',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);
        if (!$user) {
            return response([
                'error' => 'Invalid credentials',
            ], 401);
        }
        $newUser = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $farmOwner = FarmOwner::create([
            'user_id' => $newUser->id,
            'owner_firstName' => $request->firstName,
            'owner_lastName' => $request->lastName,
            'owner_gender' => $request->gender,
            'owner_birthDate' => $request->birthDate,
            'owner_email' => $request->email,
        ]);
        FarmOwnerAddress::create([
            'farm_owner_id' => $farmOwner->id,
            'owner_province' => $request->province,
            'owner_address' => $request->address,
            'owner_address' => $request->address,
            'owner_zipcode' => $request->zipcode,
        ]);
        $newUser->attachRole('3');
        if (gettype($request->contactNum) == 'array') {
            for ($i = 0; $i < count($request->contactNum); $i++) {
                FarmOwnerContactNumber::create([
                    'farm_owner_id' => $farmOwner->id,
                    'owner_contactNum' => $request->contactNum[$i]
                ]);
            }
        } else {
            FarmOwnerContactNumber::create([
                'farm_owner_id' => $farmOwner->id,
                'owner_contactNum' => $request->contactNum
            ]);
        }
        return response([
            'message' => 'Farm Owner Added!',
        ], 200);
    }
}
