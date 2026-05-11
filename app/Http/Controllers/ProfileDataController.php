<?php

namespace App\Http\Controllers;

use App\Models\ProfileFieldDefinition;
use App\Models\UserProfileDatum;
use App\Support\ProfileFieldRules;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProfileDataController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $definitions = ProfileFieldDefinition::all()->filter(
            fn (ProfileFieldDefinition $def) => $def->appliesToCountry($user->country_code),
        )->values();

        $values = UserProfileDatum::query()
            ->where('user_id', $user->id)
            ->get(['field_key', 'value', 'updated_at'])
            ->keyBy('field_key');

        $data = $definitions->mapWithKeys(function (ProfileFieldDefinition $def) use ($values) {
            $row = $values->get($def->field_key);

            return [
                $def->field_key => $row?->value,
            ];
        });

        return response()->json([
            'data' => $data,
            'definitions' => $definitions,
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $user = $request->user();
        $payload = $request->all();

        if ($payload === []) {
            throw ValidationException::withMessages([
                'payload' => ['At least one profile field must be supplied.'],
            ]);
        }

        $keys = array_keys($payload);
        $definitions = ProfileFieldDefinition::query()
            ->whereIn('field_key', $keys)
            ->get()
            ->keyBy('field_key');

        $keyErrors = [];
        foreach ($keys as $key) {
            if (! $definitions->has($key)) {
                $keyErrors[$key] = ["The field '{$key}' is not a recognized profile field."];

                continue;
            }

            if (! $definitions[$key]->appliesToCountry($user->country_code)) {
                $keyErrors[$key] = ["The field '{$key}' is not applicable for your country."];
            }
        }

        if ($keyErrors !== []) {
            throw ValidationException::withMessages($keyErrors);
        }

        $rules = [];
        foreach ($keys as $key) {
            $rules = array_merge($rules, ProfileFieldRules::buildFor($key, $definitions[$key]));
        }

        $validated = Validator::make($payload, $rules)->validate();

        DB::transaction(function () use ($user, $validated) {
            foreach ($validated as $key => $value) {
                UserProfileDatum::query()->updateOrInsert(
                    ['user_id' => $user->id, 'field_key' => $key],
                    [
                        'value' => json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                        'updated_at' => now(),
                    ],
                );
            }
        });

        return $this->index($request);
    }

    public function destroy(Request $request, string $fieldKey): JsonResponse
    {
        $user = $request->user();

        $deleted = UserProfileDatum::query()
            ->where('user_id', $user->id)
            ->where('field_key', $fieldKey)
            ->delete();

        if ($deleted === 0) {
            return response()->json(['message' => 'Field value not found.'], 404);
        }

        return response()->json(null, 204);
    }
}
