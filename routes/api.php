<?php

use App\Http\Controllers\Api\AdoptionApplicationController;
use App\Http\Controllers\Api\PetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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

Route::post('/login', function (Request $r) {
    $r->validate(['email' => 'required|email', 'password' => 'required']);
    $user = User::where('email', $r->email)->first();
    if (!$user || !Hash::check($r->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }
    $token = $user->createToken('api-token')->plainTextToken;
    return response()->json(['token' => $token, 'user' => $user]);

});

Route::get('/pets', [PetController::class, 'index']);
Route::get('/pets/{pet}', [PetController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/pets', [PetController::class, 'store']);
    Route::delete('/pets/{pet}', [PetController::class, 'destroy']);
    Route::put('/pets/{pet}', [PetController::class, 'update']);

    Route::post('/applications', [AdoptionApplicationController::class, 'store']);
    Route::patch('/applications/{application}', [AdoptionApplicationController::class, 'update']);
});
