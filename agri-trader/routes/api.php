<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectBidController;
use App\Http\Controllers\FarmController;
use App\Http\Controllers\FarmOwnerController;
use App\Http\Controllers\FarmPartnerController;
use App\Http\Controllers\OnHandBidController;
use App\Http\Controllers\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

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

    Route::group(['middleware' => ['role:trader']], function () {

        Route::prefix('farm')->group(function () {

            Route::controller(FarmController::class)->group(function () {
                Route::post('/add', 'add');
                Route::post('/add/produce', 'addProduce');
            });

            Route::controller(FarmOwnerController::class)->group(function () {
                Route::post('/owner/add', 'add');
            });

            Route::controller(FarmPartnerController::class)->group(function () {
                Route::post('/partner/add', 'add');
                Route::post('/partner/assignToFarm', 'assignToFarm');
            });
        });

        Route::prefix('project')->group(function () {
            Route::controller(ProjectController::class)->group(function () {
                Route::post('/add', 'add');
            });
        });

        //Route::put('/bid/{id}', [ProjectBidController::class, 'approveProjectBid']);
        Route::prefix('bid/project/{id}')->group(function () {
            Route::controller(ProjectBidController::class)->group(function () {
                Route::put('/approve', 'approveProjectBid');
                Route::post('/payment', 'paymentProjectBid');
                Route::put('/deliver', 'deliverProjectBid');
            });
        });
        Route::prefix('bid/onhand/{id}')->group(function () {
            Route::controller(OnHandBidController::class)->group(function () {
                Route::put('/approve', 'approveOnHandBid');
                Route::post('/payment', 'paymentProjectBid');
                Route::put('/deliver', 'deliverProjectBid');
            });
        });
    });


    Route::group(['middleware' => ['role:distributor']], function () {
        Route::prefix('bid/project')->group(function () {
            Route::controller(ProjectBidController::class)->group(function () {
                Route::post('/add', 'addProjectBid');
            });
            Route::prefix('/{id}')->group(function () {
                Route::controller(ProjectBidController::class)->group(function () {
                    Route::put('/approve', 'approveProjectBid');
                    Route::post('/payment', 'paymentProjectBid');
                    Route::put('/deliver', 'deliverProjectBid');
                    //Route::post('/refund', 'refundProjectBid');              
                });
            }); 
        });

        Route::prefix('bid/onhand')->group(function (){
            Route::controller(OnHandBidController::class)->group(function (){
                Route::post('/add', 'addOnHandBid');
            });

            Route::prefix('/{id}')->group(function () {
                Route::controller(OnHandBidController::class)->group(function () {
                    Route::put('/approve', 'approveOnHandBid');
                    Route::post('/payment', 'paymentProjectBid');
                    Route::put('/deliver', 'deliverProjectBid');
                    //Route::post('/refund', 'refundProjectBid');              
                });
            }); 
        });
               
    });
});

// --------------------- END OF PROTECTED ROUTES ----------------------

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
