<?php

namespace App\Http\Controllers;

use App\Models\Distributor;
use App\Models\DistributorAddress;
use App\Models\DistributorContactNumber;
use App\Models\FarmOwner;
use App\Models\FarmOwnerAddress;
use App\Models\FarmOwnerContactNumber;
use App\Models\Trader;
use App\Models\TraderAddress;
use App\Models\TraderContactNumber;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        if (intval($request->role) == 1) {
            $user = $request->validate([
                'firstName' => 'required|string|unique:traders,trader_firstName',
                'lastName' => 'required|string|unique:traders,trader_lastName',
                'gender' => 'required|string',
                'contactNum' => 'required|unique:trader_contact_numbers,trader_contactNum',
                'birthDate' => 'required|date',
                'email' => 'required|email|unique:traders,trader_email|unique:users,email',
                'province' => 'required|string',
                'address' => 'required|string',
                'zipcode' => 'required|string',
                'role' => 'required|int|exists:roles,id',
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
            $trader = Trader::create([
                'user_id' => $newUser->id,
                'trader_firstName' => $request->firstName,
                'trader_lastName' => $request->lastName,
                'trader_gender' => $request->gender,
                'trader_birthDate' => $request->birthDate,
                'trader_email' => $request->email,
            ]);
            TraderAddress::create([
                'trader_id' => $trader->id,
                'trader_province' => $request->province,
                'trader_address' => $request->address,
                'trader_zipcode' => $request->zipcode,
            ]);
            $newUser->attachRole('1');
            if (gettype($request->contactNum) == 'array') {
                for ($i = 0; $i < count($request->contactNum); $i++) {
                    TraderContactNumber::create([
                        'trader_id' => $trader->id,
                        'trader_contactNum' => $request->contactNum[$i]
                    ]);
                }
            } else {
                TraderContactNumber::create([
                    'trader_id' => $trader->id,
                    'trader_contactNum' => $request->contactNum
                ]);
            }
            $token = $newUser->createToken('sessionToken')->plainTextToken;
            $role = DB::table('roles')->where('id', DB::table('role_user')->where('user_id', $newUser->id)->first()->role_id)->first()->name;
            return response([
                'token' => $token,
                'user' => $newUser,
                'role' => $role,
                'name' => $trader->trader_firstName . " " . $trader->trader_lastName
            ], 201);
        } else if (intval($request->role) == 2) {
            $user = $request->validate([
                'firstName' => 'required|string|unique:distributors,distributor_firstName',
                'lastName' => 'required|string|unique:distributors,distributor_lastName',
                'gender' => 'required|string',
                'contactNum' => 'required|unique:distributor_contact_numbers,distributor_contactNum',
                'birthDate' => 'required|date',
                'email' => 'required|email|unique:distributors,distributor_email|unique:users,email',
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
            $distributor = Distributor::create([
                'user_id' => $newUser->id,
                'distributor_firstName' => $request->firstName,
                'distributor_lastName' => $request->lastName,
                'distributor_gender' => $request->gender,
                'distributor_birthDate' => $request->birthDate,
                'distributor_email' => $request->email,
            ]);
            DistributorAddress::create([
                'distributor_id' => $distributor->id,
                'distributor_province' => $request->province,
                'distributor_address' => $request->address,
                'distributor_address' => $request->address,
                'distributor_zipcode' => $request->zipcode,
            ]);
            $newUser->attachRole('2');
            if (gettype($request->contactNum) == 'array') {
                for ($i = 0; $i < count($request->contactNum); $i++) {
                    DistributorContactNumber::create([
                        'distributor_id' => $distributor->id,
                        'distributor_contactNum' => $request->contactNum[$i]
                    ]);
                }
            } else {
                DistributorContactNumber::create([
                    'distributor_id' => $distributor->id,
                    'distributor_contactNum' => $request->contactNum
                ]);
            }
            $token = $newUser->createToken('sessionToken')->plainTextToken;
            $role = DB::table('roles')->where('id', DB::table('role_user')->where('user_id', $newUser->id)->first()->role_id)->first()->name;
            return response([
                'token' => $token,
                'user' => $newUser,
                'role' => $role,
                'name' => $distributor->distributor_firstName . " " . $distributor->trader_lastName
            ], 201);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'error' => 'Invalid email/password!'
            ], 401);
        }

        $token = $user->createToken('sessionToken')->plainTextToken;
        $role = DB::table('roles')->where('id', DB::table('role_user')->where('user_id', $user->id)->first()->role_id)->first()->name;
        if($role == 'distributor'){
            $name = Distributor::where('user_id',$user->id)->first()->distributor_firstName . ' ' . 
            Distributor::where('user_id',$user->id)->first()->distributor_lastName;
        }
        else{
            $name = Trader::where('user_id',$user->id)->first()->trader_firstName . ' ' . 
            Trader::where('user_id',$user->id)->first()->trader_lastName;
        }
        return response([
            'token' => $token,
            'user' => $user,
            'role' => $role,
            'name' => $name
        ], 200);
    }
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return response([
            'message' => 'You are logged out!'
        ], 200);
    }
}
