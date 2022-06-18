<?php

namespace App\Http\Controllers;

use App\Models\Farm;
use App\Models\FarmAddress;
use App\Models\FarmOwner;
use App\Models\FarmPartner;
use App\Models\Produce;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class FarmController extends Controller
{
    //
    public function add(Request $request)
    {
        $newFarm = $request->validate([
            'owner_id' => 'required|exists:farm_owners,id',
            'farm_hectares' => 'required|numeric',
            'farm_titleNum' => 'required|string',
            'farm_name' => 'required|string',
            'farm_imageUrl' => 'required|image|mimes:jpg,jpeg,png|max:5048',
            //'farm_imageUrl' => 'required',
            'farm_province' => 'required',
            'farm_address' => 'required',
            'farm_city' => 'required',
            'farm_zipcode' => 'required'
        ]);

        if (!$newFarm) {
            return response([
                'error' => 'Invalid farm details!'
            ], 400);
        }
        
        $fileNameWithExt = $request->file('farm_imageUrl')->getClientOriginalName();
        $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
        $extension = $request->file('farm_imageUrl')->getClientOriginalExtension();
        $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
        $request->file('farm_imageUrl')->storeAs('public/farms', $fileNameToStore);       

        $farm = Farm::create([
            'farm_owner_id' => $request->owner_id,
            'trader_id' => auth()->id(),
            'farm_name' => $request->farm_name,
            'farm_hectares' => $request->farm_hectares,
            'farm_titleNum' => $request->farm_titleNum,
            'farm_imageUrl' => $fileNameToStore,
        ]);

        FarmAddress::create([
            'farm_id' => $farm->id,
            'farm_province' => $request->farm_province,
            'farm_address' => $request->farm_address,
            'farm_zipcode' => $request->farm_zipcode,
            'farm_city' => $request->farm_city,
        ]);

        return response([
            'farm_id' => $farm->id,
            'farm_owner_id' => $farm->farm_owner_id,
            'trader_id' => $farm->trader_id,
            'farm_hectares' => $farm->farm_hectares,
            'farm_titleNum' => $farm->farm_titleNum,
            'farm_imageUrl' => $farm->farm_imageUrl,
            'farm_province' => $request->farm_province,
            'farm_address' => $request->farm_address,
            'farm_zipcode' => $request->farm_zipcode,
            'farm_city' => $request->farm_city,
        ], 200);
    }

    public function addProduce(Request $request)
    {
        $query = $request->validate([
            'farm_id' => 'required|exists:farms,id',
            'produce_id' => 'required|exists:produces,id',
        ]);

        $result1 = DB::table('produce_trader')->where([['produce_id', '=', $request->produce_id], ['trader_id', '=', auth()->id()]])->first();
        $owner = Farm::find($request->farm_id)->farm_owner_id;
        $result2 = DB::table('owner_trader')->where([['farm_owner_id', '=', $owner], ['trader_id', '=', auth()->id()]])->first();

        if (!$query || !$result1 || !$result2) {
            return response([
                'error' => 'Invalid!'
            ], 400);
        }

        $farm = Farm::find($request->farm_id);
        $produce = Produce::find($request->produce_id);
        $farm->produces()->attach($produce);

        return response([
            'message' => 'Successful!'
        ], 200);
    }
}
