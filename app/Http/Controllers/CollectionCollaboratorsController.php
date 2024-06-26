<?php

namespace App\Http\Controllers;

use App\Helpers\PaginationMeta;
use App\Http\Requests\CollectionCollaboratorRequest;
use App\Http\Resources\CollectionCollaboratorResource;
use App\Http\Resources\CollectionResource;
use App\Models\Collection;
use App\Models\CollectionCollaborator;
use App\Models\User;
use Exception;

class CollectionCollaboratorsController extends Controller
{
    public function index()
    {
        try {
            $collaborators = CollectionCollaborator::query()->with('user', 'collection')->paginate(20);
            return response()->json([
                'data' => CollectionCollaboratorResource::collection($collaborators),
                'meta' => PaginationMeta::getPaginationMeta($collaborators)
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function show(CollectionCollaborator $collaboration)
    {
        try {
            return response()->json(CollectionCollaboratorResource::make($collaboration->load('user', 'collection')));
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function store(CollectionCollaboratorRequest $request)
    {
        try {
            $collection = Collection::query()->findOrFail($request->collection_id);

            if (!$collection)
                return response()->json(['message' => __('collection_not_found')], 404);

            if ($collection->user_id != auth()->id())
                return response()->json(['message' => __("you_are_not_allowed_to_add_collaboration_to_this_collection")], 403);

            $user = User::where('wallet_address', $request->wallet_address)->first();

            if (!$user)
                return response()->json(['message' => 'user with " ' . $request->wallet_address . ' " wallet address can\'t be found, please check it and try again'], 400);

            $collaboration_exist = CollectionCollaborator::where('user_id', $user->id)->where('collection_id', $collection->id)->exists();
            if ($collaboration_exist)
                return response()->json(['message' => __("this_user_already_collaborated_with_this_collection")], 403);

            CollectionCollaborator::query()->create([
                'collection_id' => $collection->id,
                'user_id' => $user->id
            ]);


            return response()->json(['data' => ['collection' => CollectionResource::make($collection), 'collaborator' => $user], 'message' => 'collection collaboration created successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e], 500);
        }
    }

    public function destroy(CollectionCollaborator $collaboration)
    {
        try {
            if ($collaboration->collection->user_id != auth()->id())
                return response()->json(['message' => __('you_are_not_allowed_to_remove_collaboration_from_this_collection')], 403);
            $collaboration->delete();
            $collaboration->collection->refresh();
            return response()->json(['message' => __('collaboration_deleted_successfully')], 200);

        } catch (Exception $e) {
            return response()->json(['message' => $e], 500);
        }
    }

}
