<?php

namespace App\Http\Controllers;

use App\Models\Farm;
use App\Models\FarmPartner;
use App\Models\FarmPartnerAddress;
use App\Models\FarmPartnerContactNumber;
use Illuminate\Http\Request;
// use Illuminate\Http\Response;

class FarmPartnerController extends Controller
{
    public function add(Request $request)
    {
        $newPartner = $request->validate([
            'partner_firstName' => 'required',
            'partner_lastName' => 'required',
            'partner_birthDate' => 'required|date',
            'partner_contactNum' => 'required|unique:farm_partner_contact_numbers,partner_contactNum',
            'partner_province' => 'required|string',
            'partner_address' => 'required|string',
            'partner_zipcode' => 'required|string',

        ]);
        if (!$newPartner) {
            return response([
                'error' => 'Invalid credentials!'
            ], 400);
        }
        $partner = FarmPartner::create([
            'trader_id' => auth()->id(),
            'partner_firstName' => $request->partner_firstName,
            'partner_lastName' => $request->partner_lastName,
            'partner_birthDate' => $request->partner_birthDate,
        ]);
        FarmPartnerAddress::create([
            'farm_partner_id' => $partner->id,
            'partner_province' => $request->partner_province,
            'partner_address' => $request->partner_address,
            'partner_zipcode' => $request->partner_zipcode,
        ]);
        if (gettype($request->partner_contactNum) == 'array') {
            for ($i = 0; $i < count($request->partner_contactNum); $i++) {
                FarmPartnerContactNumber::create([
                    'farm_partner_id' => $partner->id,
                    'partner_contactNum' => $request->partner_contactNum[$i]
                ]);
            }
        } else {
            FarmPartnerContactNumber::create([
                'farm_partner_id' => $partner->id,
                'partner_contactNum' => $request->partner_contactNum
            ]);
        }
        return response([
            'partner' => $partner
        ], 200);
    }

    public function assignToFarm(Request $request)
    {
        $query = $request->validate([
            'farm_id' => 'required|exists:farms,id',
            'farm_partner_id' => 'required|exists:farm_partners,id',
        ]);

        if (!$query) {
            return response([
                'error' => 'Invalid!'
            ], 400);
        }
        $farm = Farm::find($request->farm_id);
        $partner = FarmPartner::find($request->farm_partner_id);
        $farm->farm_partners()->attach($partner);

        return response([
            'message' => 'Successful!'
        ], 200);
    }
}
