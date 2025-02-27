<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ArtistController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlaybackController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\TagController;

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

Route::name('tracks.')->prefix('tracks')->group(function () {
    Route::get('/', [TrackController::class, 'index'])->name('index');
    Route::get('/{track:id}', [TrackController::class, 'show'])->name('show');
    Route::post('/', [TrackController::class, 'store'])->name('store');
    Route::put('/{track:id}', [TrackController::class, 'update'])->name('update');
    Route::delete('/{track:id}', [TrackController::class, 'destroy'])
        ->name('destroy');
});

Route::name('popular-tracks.')->prefix('popular/tracks')->group(function () {
    Route::get('/all-time', [TrackController::class, 'allTimePopulars'])->name('all-time');
    Route::get('/all-time/top/{top}', [TrackController::class, 'allTimeTop'])->name("all-time-top");
});

Route::name('popular-recommendations.')->prefix('popular/recommendations')->group(function () {
    Route::get('/', [TrackController::class, 'recommendationsByPopularity'])->name('index');
});

Route::name('playbacks.')->prefix('playbacks')->group(function () {
    Route::post('/', [PlaybackController::class, 'store'])->name('store')->middleware('auth:sanctum');
    Route::delete('/{playback:id}', [PlaybackController::class, 'destroy'])->name('destroy');
});

Route::name('users.')->prefix('users')->group(function () {
    Route::get('/me', [UserController::class, 'get'])->middleware('auth:sanctum')->name('get');
    Route::get('/', [UserController::class, 'index'])->middleware('auth:sanctum')->name('index');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::get('/{user:id}', [UserController::class, 'show'])->name('show');
    Route::put('/{user:id}', [UserController::class, 'update'])->name('update');
    Route::delete('/{user:id}', [UserController::class, 'destroy'])->name('destroy');
});

// Specific for the current user
Route::name('user.')->prefix('user')->middleware('auth:sanctum')->group(function () {
    Route::name('preferred-artists')->group(function () {
        Route::get('/preferred-artists', [UserController::class, 'getPreferredArtists']);
        Route::patch('/preferred-artists', [UserController::class, 'addPreferredArtists']);
        Route::delete('/preferred-artists', [UserController::class, 'removePreferredArtists']);
        Route::get('/preferred-tracks', [UserController::class, 'getPreferredTracks']);
        Route::patch('/preferred-tracks', [UserController::class, 'addPreferredTracks']);
        Route::delete('/preferred-tracks', [UserController::class, 'removePreferredTracks']);
    });
});

Route::name('artists.')->prefix('artists')->group(function () {
    Route::get('/', [ArtistController::class, 'index'])->name('index');
});

Route::name('auth.')->prefix('auth')->group(function () {
    Route::post('token', [AuthController::class, 'requestToken'])->name('token');
    Route::delete('token', [AuthController::class, 'unauthenticate'])->name('unauthenticate')->middleware('auth:sanctum');
});

Route::name('reccs.')->prefix('reccs')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [RecommendationController::class, 'index'])->name('index');
});

Route::name('playlists.')->prefix('playlists')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [PlaylistController::class, 'index'])->name('index');
    Route::get('/me', [PlaylistController::class, 'me'])->name('me');
    Route::get('/{id}', [PlaylistController::class, 'show'])->name('show');
    Route::post('/', [PlaylistController::class, 'create'])->name('create');
});

Route::name("tags")->prefix('tags')->group(function () {
    Route::get('/', [TagController::class, 'index'])->name('indexTags');
    Route::get('/{tag:id}', [TagController::class, 'show'])->name('getTag');
});
