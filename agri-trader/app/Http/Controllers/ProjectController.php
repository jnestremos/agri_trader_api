<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ContractShare;
use App\Models\Farm;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function add(Request $request)
    {
        $project = $request->validate([
            //'trader_id' => 'required',
            'farm_id' => 'required|exists:farms,id',
            'produce_id' => 'required|exists:produces,id',
            'project_status_id' => 'required|exists:project_statuses,id',
            // 'project_stageImg' => 'image',           
            'contract_estimatedHarvest' => 'required',
            'contract_estimatedPrice' => 'required',
            'contract_estimatedSales' => 'required',
            'contract_ownerShare' => 'required',
            'contract_traderShare' => 'required',
            'contractShare_type' => 'required',
            'contractShare_amount' => 'required',
            'project_completionDate' => 'required|date|after:project_commenceDate',
            'project_commenceDate' => 'required|date|before:project_completionDate',
            'project_floweringStart' => 'date',
            'project_floweringEnd' => 'date',
            'project_fruitBuddingStart' => 'date',
            'project_fruitBuddingEnd' => 'date',
            'project_devFruitStart' => 'date',
            'project_devFruitEnd' => 'date',
            'project_harvestableStart' => 'date',
            'project_harvestableEnd' => 'date',
        ]);

        if (!$project) {
            return response([
                'error' => 'Invalid creds!'
            ], 400);
        }

        $share = ContractShare::create([
            'contractShare_type' => $request->contractShare_type,
            'contractShare_amount' => $request->contractShare_amount,
        ]);

        $farm = Farm::find($request->farm_id);

        $contract = Contract::create([
            'trader_id' => auth()->id(),
            'farm_id' => $farm->id,
            'contract_share_id' => $share->id,
            'produce_id' => $request->produce_id,
            'contract_estimatedHarvest' => $request->contract_estimatedHarvest,
            'contract_estimatedPrice' => $request->contract_estimatedPrice,
            'contract_estimatedSales' => $request->contract_estimatedSales,
            'contract_ownerShare' => $request->contract_ownerShare,
            'contract_traderShare' => $request->contract_traderShare,
        ]);

        $newProj = Project::create([
            'contract_id' => $contract->id,
            'project_status_id' => $request->project_status_id,
            'project_completionDate' => $request->project_completionDate,
            'project_commenceDate' => $request->project_commenceDate,
            'project_floweringStart' => $request->project_floweringStart,
            'project_floweringEnd' => $request->project_floweringEnd,
            'project_fruitBuddingStart' => $request->project_fruitBuddingStart,
            'project_fruitBuddingEnd' => $request->project_fruitBuddingEnd,
            'project_devFruitStart' => $request->project_devFruitStart,
            'project_devFruitEnd' => $request->project_devFruitEnd,
            'project_harvestableStart' => $request->project_harvestableStart,
            'project_harvestableEnd' => $request->project_harvestableEnd,
        ]);

        $newProj->statuses()->attach($share);

        return response([
            'project' => $newProj,
            'contract' => $contract,
            'share' => $share
        ]);
    }
}
