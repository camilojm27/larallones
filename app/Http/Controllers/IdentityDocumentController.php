<?php

namespace App\Http\Controllers;

use App\Models\UserIdentityDocument;
use App\Support\IdentityDocumentNormalizer;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IdentityDocumentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $documents = UserIdentityDocument::query()
            ->where('user_id', $request->user()->id)
            ->orderByDesc('is_primary')
            ->orderBy('id')
            ->get();

        return response()->json(['data' => $documents]);
    }

    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'country_code' => ['required', 'string', 'size:2'],
            'document_type' => ['required', 'string', 'max:16'],
            'document_number' => ['required', 'string', 'max:64'],
            'is_primary' => ['sometimes', 'boolean'],
        ]);

        $isPrimary = (bool) ($validated['is_primary'] ?? false);

        try {
            $document = DB::transaction(function () use ($user, $validated, $isPrimary) {
                if ($isPrimary) {
                    $this->clearPrimaryFlag($user->id);
                }

                return UserIdentityDocument::create([
                    'user_id' => $user->id,
                    'country_code' => $validated['country_code'],
                    'document_type' => $validated['document_type'],
                    'document_number' => $validated['document_number'],
                    'is_primary' => $isPrimary,
                ]);
            });
        } catch (QueryException $e) {
            if ($this->isUniqueViolation($e)) {
                return response()->json([
                    'message' => 'This identity document is already registered.',
                    'normalized_number' => IdentityDocumentNormalizer::normalize($validated['document_number']),
                ], 409);
            }

            throw $e;
        }

        return response()->json($document, 201);
    }

    public function update(Request $request, UserIdentityDocument $identityDocument): JsonResponse
    {
        $this->ensureOwner($request, $identityDocument);

        $validated = $request->validate([
            'is_primary' => ['sometimes', 'boolean'],
            'document_number' => ['sometimes', 'string', 'max:64'],
            'document_type' => ['sometimes', 'string', 'max:16'],
            'country_code' => ['sometimes', 'string', 'size:2'],
        ]);

        try {
            DB::transaction(function () use ($identityDocument, $validated) {
                if (array_key_exists('is_primary', $validated) && $validated['is_primary']) {
                    $this->clearPrimaryFlag($identityDocument->user_id, $identityDocument->id);
                }

                $identityDocument->fill($validated)->save();
            });
        } catch (QueryException $e) {
            if ($this->isUniqueViolation($e)) {
                return response()->json([
                    'message' => 'This identity document collides with another already registered.',
                ], 409);
            }

            throw $e;
        }

        return response()->json($identityDocument->refresh());
    }

    public function destroy(Request $request, UserIdentityDocument $identityDocument): JsonResponse
    {
        $this->ensureOwner($request, $identityDocument);

        $identityDocument->delete();

        return response()->json(null, 204);
    }

    private function clearPrimaryFlag(int $userId, ?int $exceptId = null): void
    {
        $query = UserIdentityDocument::query()
            ->where('user_id', $userId)
            ->where('is_primary', true);

        if ($exceptId !== null) {
            $query->where('id', '!=', $exceptId);
        }

        $query->update(['is_primary' => false]);
    }

    private function ensureOwner(Request $request, UserIdentityDocument $document): void
    {
        if ($document->user_id !== $request->user()->id) {
            throw new AuthorizationException('You may only access your own identity documents.');
        }
    }

    private function isUniqueViolation(QueryException $e): bool
    {
        return $e->getCode() === '23505';
    }
}
