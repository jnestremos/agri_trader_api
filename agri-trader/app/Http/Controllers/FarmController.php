<?php

namespace App\Http\Controllers;

use App\Models\Farm;
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
            'farm_hectares' => 'required',
            'farm_titleNum' => 'required',
            // 'farm_imageUrl' => 'required|image',
            'farm_imageUrl' => 'required'
        ]);

        if (!$newFarm) {
            return response([
                'error' => 'Invalid farm details!'
            ], 400);
        }

        $farm = Farm::create([
            'farm_owner_id' => $request->owner_id,
            'trader_id' => auth()->id(),
            'farm_hectares' => $request->farm_hectares,
            'farm_titleNum' => $request->farm_titleNum,
            'farm_imageUrl' => $request->farm_imageUrl,
        ]);

        return response([
            'farm_id' => $farm->id,
            'farm_owner_id' => $farm->farm_owner_id,
            'trader_id' => $farm->trader_id,
            'farm_hectares' => $farm->farm_hectares,
            'farm_titleNum' => $farm->farm_titleNum,
            'farm_imageUrl' => $farm->farm_imageUrl,
        ], 200);
    }

    public function addProduce(Request $request)
    {
        $query = $request->validate([
            'farm_id' => 'required|exists:farms,id',
            'produce_id' => 'required|exists:produces,id',
        ]);

        if (!$query) {
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
