<?php

use App\Http\Controllers\CommunityController;
use App\Http\Controllers\IdentityDocumentController;
use App\Http\Controllers\ProfileDataController;
use App\Http\Controllers\ProfileFieldController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::put('/user', [UserController::class, 'update']);

    Route::post('/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();

        return response()->noContent();
    });

    Route::post('/refresh-token', function (Request $request) {
        $user = $request->user();
        $currentToken = $user->currentAccessToken();
        $tokenName = $currentToken->name;

        // Revocar el token actual
        $currentToken->delete();

        // Crear un nuevo token con el mismo nombre
        $newToken = $user->createToken($tokenName);

        return response()->json(['token' => $newToken->plainTextToken]);
    });
    Route::apiResource('communities', CommunityController::class);
    Route::post('communities/{community}/join', [CommunityController::class, 'join']);
    Route::post('communities/{community}/leave', [CommunityController::class, 'leave']);

    Route::get('me/profile-data', [ProfileDataController::class, 'index']);
    Route::put('me/profile-data', [ProfileDataController::class, 'update']);
    Route::delete('me/profile-data/{field_key}', [ProfileDataController::class, 'destroy']);

    Route::get('me/identity-documents', [IdentityDocumentController::class, 'index']);
    Route::post('me/identity-documents', [IdentityDocumentController::class, 'store']);
    Route::patch('me/identity-documents/{identityDocument}', [IdentityDocumentController::class, 'update']);
    Route::delete('me/identity-documents/{identityDocument}', [IdentityDocumentController::class, 'destroy']);

    Route::get('profile-fields', [ProfileFieldController::class, 'index']);
});

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    return response()->json([
        'token' => $user->createToken($request->device_name)->plainTextToken,
    ]);
});

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    Password::sendResetLink($request->only('email'));

    return response()->json([
        'status' => 'If an account with that email exists, we\'ve sent a password reset link.',
    ]);
});

Route::post('/register', function (Request $request) {
    $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
        'device_name' => 'required',
        'country_code' => 'nullable|string|size:2',
    ]);

    $user = User::create([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'username' => explode('@', $request->email)[0].rand(1000, 9999),
        'email' => $request->email,
        'country_code' => $request->country_code ? strtoupper($request->country_code) : null,
        'password' => Hash::make($request->password),
    ]);

    return response()->json([
        'token' => $user->createToken($request->device_name)->plainTextToken,
    ]);
});
