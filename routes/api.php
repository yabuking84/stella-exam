<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\seeders\Property;
use App\Http\Controllers\seeders\Building;
use App\Http\Controllers\seeders\Reservation;
use App\Http\Controllers\seeders\Availability;
use App\Http\Controllers\AvailableUnits;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('seed')->group(function () {

    // Route::get('/building', [Building::class, 'seedBuilding']);
    // Route::get('/property', [Property::class, 'seedProperty']);
    // Route::get('/reservation', [Reservation::class, 'seedReservation']);
    // Route::get('/availability', [Availability::class, 'seedAvailability']);
    


});

Route::post('available-units', [AvailableUnits::class, 'getAvailableUnits']);



