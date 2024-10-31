<?php

namespace App\Http\Controllers;

use App\Models\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KeyController extends Controller
{
    public function store(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'key' => 'required|string|max:255', // Add your key validation rules here
            'type' => 'required|in:inclusive,exclusive',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Create the key and associate it with the authenticated user
        $key = Key::create([
            'user_id' => $request->user()->id, // Use the authenticated user's ID
            'key' => $request->key, // The key value from the request
            'type' => $request->type, // the key type from request
        ]);

        return response()->json(['message' => 'Key added successfully', 'key' => $key], 201);
    }

    public function destroy($keyId)
{


    $user = request()->user();

    if (!$user) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $key = Key::find($keyId);

    if (!$key || $key->user_id !== $user->id) {
        return response()->json(['message' => 'Key not found or you do not have permission to delete it.'], 404);
    }

    $key->delete();

    return response()->json(['message' => 'Key deleted successfully.'], 200);



}

}
