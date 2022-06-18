<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ProduceInventory;
use App\Models\ProduceYield;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProduceYieldController extends Controller
{
    public function add(Request $request)
    {
        $yield = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'produce_id' => 'required|exists:produces,id',
            'produce_yield_qtyHarvested' => 'required|array',
            'produce_yield_price' => 'required|array',
            'produce_yield_dateHarvestFrom' => 'required|date|after:now',
            'produce_yield_dateHarvestTo' => 'required|date|after:produce_yield_dateHarvestFrom'
        ]);
        $produce = Project::find($request->project_id)->contract()->first()->produce_id;
        $inventory = ProduceYield::where('project_id', $request->project_id)->first();
        $num = DB::table('produce_trader')->where([['trader_id', '=', auth()->id()], ['produce_id', '=', $request->produce_id]])->first()->produce_numOfGrades;

        if (
            !$yield || $produce != $request->produce_id || $inventory
            || count($request->produce_yield_qtyHarvested) != $num
        ) {
            return response([
                'error' => 'Invalid Harvest Details!'
            ], 400);
        }
        if (count($request->produce_yield_qtyHarvested) == 3) {
            $classes = ['A', 'B', 'C'];
            for ($i = 0; $i < count($request->produce_yield_qtyHarvested); $i++) {
                $yield = ProduceYield::create([
                    'project_id' => $request->project_id,
                    'produce_id' => $request->produce_id,
                    'produce_yield_class' => $classes[$i],
                    'produce_yield_qtyHarvested' => $request->produce_yield_qtyHarvested[$i],
                    'produce_yield_price' => $request->produce_yield_price[$i],
                    'produce_yield_dateHarvestFrom' => $request->produce_yield_dateHarvestFrom,
                    'produce_yield_dateHarvestTo' => $request->produce_yield_dateHarvestTo,
                ]);
                ProduceInventory::create([
                    'produce_yield_id' => $yield->id,
                    'produce_inventory_qtyOnHand' => $yield->produce_yield_qtyHarvested
                ]);
            }
        } else {
            $yield = ProduceYield::create([
                'project_id' => $request->project_id,
                'produce_id' => $request->produce_id,
                'produce_yield_class' => 'N/A',
                'produce_yield_qtyHarvested' => $request->produce_yield_qtyHarvested[0],
                'produce_yield_price' => $request->produce_yield_price[0],
                'produce_yield_dateHarvestFrom' => $request->produce_yield_dateHarvestFrom,
                'produce_yield_dateHarvestTo' => $request->produce_yield_dateHarvestTo,
            ]);
            ProduceInventory::create([
                'produce_yield_id' => $yield->id,
                'produce_inventory_qtyOnHand' => $yield->produce_yield_qtyHarvested
            ]);
        }


        return response([
            'message' => 'Harvest Successful!'
        ], 200);
    }
}
