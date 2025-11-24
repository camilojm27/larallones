<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\CustomFieldValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CommunityController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $ownedCommunitiesIds = $user->ownedCommunities->pluck('id');
        
        // Exclude owned communities from my_communities to prevent duplicates
        $myJoinedCommunities = $user->communities->whereNotIn('id', $ownedCommunitiesIds)->values();
        $myCommunitiesIds = $myJoinedCommunities->pluck('id');
        
        $otherCommunities = Community::whereNotIn('id', $myCommunitiesIds)
            ->whereNotIn('id', $ownedCommunitiesIds)
            ->get();

        return response()->json([
            'my_communities' => $myJoinedCommunities,
            'owned_communities' => $user->ownedCommunities,
            'other_communities' => $otherCommunities
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
        ]);

        $community = Community::create([
            'owner_id' => $request->user()->id,
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . rand(1000, 9999),
            'description' => $request->description,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'website' => $request->website,
        ]);

        // Add owner as a member with admin role
        $community->members()->attach($request->user()->id, ['role' => 'admin']);

        return response()->json($community, 201);
    }

    public function show(Request $request, $id)
    {
        $community = Community::with(['customFields', 'events'])->findOrFail($id);
        $user = $request->user();

        // Append membership status
        $community->is_member = $community->members()->where('user_id', $user->id)->exists();
        $community->is_owner = $community->owner_id === $user->id;

        return response()->json($community);
    }

    public function join(Request $request, $id)
    {
        $community = Community::with('customFields')->findOrFail($id);
        $user = $request->user();

        if ($community->members()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'Already a member'], 409);
        }

        // Validate custom fields
        $rules = [];
        foreach ($community->customFields as $field) {
            if ($field->is_required) {
                $rules['custom_fields.' . $field->slug] = 'required';
            }
        }
        $request->validate($rules);

        DB::transaction(function () use ($request, $community, $user) {
            // Add user to community
            $community->members()->attach($user->id, ['role' => 'member']);

            // Save custom field values
            if ($request->has('custom_fields')) {
                foreach ($request->custom_fields as $slug => $value) {
                    $field = $community->customFields()->where('slug', $slug)->first();
                    if ($field) {
                        CustomFieldValue::updateOrCreate(
                            ['user_id' => $user->id, 'custom_field_id' => $field->id],
                            ['value' => $value]
                        );
                    }
                }
            }
        });

        return response()->json(['message' => 'Joined successfully']);
    }

    public function leave(Request $request, $id)
    {
        $community = Community::findOrFail($id);
        $user = $request->user();

        if ($community->owner_id === $user->id) {
            return response()->json(['message' => 'Owner cannot leave community'], 403);
        }

        $community->members()->detach($user->id);

        return response()->json(['message' => 'Left community successfully']);
    }
}
