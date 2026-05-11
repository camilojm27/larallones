<?php

namespace App\Http\Controllers;

use App\Models\ProfileFieldDefinition;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileFieldController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $countryCode = $request->user()?->country_code;

        $fields = ProfileFieldDefinition::all()
            ->filter(fn (ProfileFieldDefinition $def) => $def->appliesToCountry($countryCode))
            ->values();

        return response()->json(['data' => $fields]);
    }
}
