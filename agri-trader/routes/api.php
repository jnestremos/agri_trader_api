<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\ProjectBidController;
use App\Http\Controllers\FarmController;
use App\Http\Controllers\FarmOwnerController;
use App\Http\Controllers\FarmPartnerController;
use App\Http\Controllers\OnHandBidController;
use App\Http\Controllers\ProduceController;
use App\Http\Controllers\ProduceYieldController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RefundController;
use App\Models\Farm;
use App\Models\FarmOwner;
use App\Models\Trader;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::get('/', function(){
//     return response([
//         'message' => 'I\'m here'
//     ], 200);
// });

// -------------------- PUBLIC ROUTES ----------------------
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
// -------------------- END OF PUBLIC ROUTES ----------------

// ------------------- PROTECTED ROUTES ----------------------

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('farm/images/{path}', function($fileName) {            
        $path = public_path().'/storage/farms/'.$fileName;
        return response()->download($path, 'Farm Image');        
    });

    Route::group(['middleware' => ['role:trader']], function () {

        Route::get('/dashboard', function(){
            return response([
                'name' => Trader::where('user_id',auth()->id())->first()->trader_firstName . " " . 
                Trader::where('user_id',auth()->id())->first()->trader_lastName
            ], 200);
        });

        

        Route::prefix('farm')->group(function () {

            Route::get('/', function (){
                $farm_produces = [];
                $farms = Farm::where('trader_id', auth()->id());
                foreach($farms as $farm){
                    $farm_produce = [
                        'id' => $farm->id,
                        'name' => $farm->farm_name,
                        'produces' => DB::table('farm_produce')->where('farm_id', $farm->id)->get()
                    ]; 
                    array_push($farm_produces, $farm_produce);
                }               
                if(count($farms->get()) > 6){
                    return response([   
                        'farm_produces' => $farm_produces,
                        'farms' => $farms->paginate(6)
                    ], 200);
                }
           
                return response([  
                    'farm_produces' => $farm_produces,                                                          
                    'farms' => $farms->get()
                ], 200);
                              
            });

            Route::get('/details/{id}', function ($id){
                return response([
                    'owner' => Farm::find($id)->farm_owner()->first(),
                    'farm' => Farm::find($id),                    
                    'produces' => DB::table('farm_produce')->where('farm_id', $id)->get(),
                    'farm_address' => DB::table('farm_addresses')->where('farm_id', $id)->first(),
                    'farm_partners' =>DB::table('farm_to_farm_partner_assignment')->where('farm_id', $id)->get(),                    
                ], 200);
            });

            Route::controller(FarmController::class)->group(function () {
                Route::post('/add', 'add');
                Route::post('/add/produce', 'addProduce');
            });

            Route::get('/owners', function (){
               $farmOwners = []; 
            
               $owners = DB::table('owner_trader')->where('trader_id', auth()->id())->get();

               foreach($owners as $owner){
                $farmOwner = [
                    'id' => $owner->farm_owner_id,
                    'owner_firstName' => FarmOwner::find($owner->farm_owner_id)->owner_firstName,
                    'owner_lastName' => FarmOwner::find($owner->farm_owner_id)->owner_lastName,
                ];
                array_push($farmOwners, $farmOwner);               
               }
               return response([                
                'owners' => $farmOwners
                ], 200);
              
            });

            Route::controller(FarmOwnerController::class)->group(function () {
                Route::post('/owner/add', 'add');
            });

            Route::controller(FarmPartnerController::class)->group(function () {
                Route::post('/partner/add', 'add');
                Route::post('/partner/assignToFarm', 'assignToFarm');
            });
        });

        Route::prefix('produce')->group(function () {
            Route::post('/add', [ProduceController::class, 'assignProduceToTrader']);
        });

        Route::prefix('yield')->group(function () {
            Route::post('/add', [ProduceYieldController::class, 'add']);
        });


        Route::prefix('project')->group(function () {
            Route::controller(ProjectController::class)->group(function () {
                Route::post('/add', 'add');
                Route::post('/add/pictures/{id}', 'addPictures');
            });
            Route::put('/refund/approve/{id}', [RefundController::class, 'approveRefund']);
        });



        //Route::put('/bid/{id}', [ProjectBidController::class, 'approveProjectBid']);        
    });

    Route::group(['middleware' => ['role:distributor|trader']], function () {
        Route::prefix('bid/project/{id}')->group(function () {
            Route::controller(ProjectBidController::class)->group(function () {
                Route::put('/approve', 'approveProjectBid');
                Route::post('/payment', 'paymentProjectBid');
            });
            Route::put('/deliver', [DeliveryController::class, 'deliverProjectBid']);
        });

        Route::prefix('bid/onhand/{id}')->group(function () {
            Route::controller(OnHandBidController::class)->group(function () {
                Route::put('/approve', 'approveOnHandBid');
                Route::post('/payment', 'paymentOnHandBid');
            });
            Route::put('/deliver', [DeliveryController::class, 'deliverOnHandBid']);
        });
    });

    Route::group(['middleware' => ['role:distributor']], function () {
        Route::prefix('bid/project')->group(function () {
            Route::controller(ProjectBidController::class)->group(function () {
                Route::post('/add', 'addProjectBid');
            });

            Route::prefix('refund')->group(function () {
                Route::controller(RefundController::class)->group(function () {
                    Route::post('/request/{id}', 'requestRefund');
                    Route::put('/confirm/{id}', 'confirmRefund');
                });
            });
        });

        Route::prefix('bid/onhand')->group(function () {
            Route::controller(OnHandBidController::class)->group(function () {
                Route::post('/add', 'addOnHandBid');
            });

            Route::prefix('refund')->group(function () {
                Route::controller(RefundController::class)->group(function () {
                    Route::post('/request/{id}', 'requestRefund');
                    Route::put('/confirm/{id}', 'confirmRefund');
                });
            });
        });
    });
});

// --------------------- END OF PROTECTED ROUTES ----------------------

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
