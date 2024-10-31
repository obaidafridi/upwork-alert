<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function updateProfileImage(Request $request)
    {
        // Validate that the request contains an image
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Limit file types and size
        ]);

        $user = $request->user();

        // Delete the old profile image if it exists
        if ($user->profile_image) {
            Storage::delete($user->profile_image);
        }

        // Store the new image
        $path = $request->file('profile_image')->store('profile_images');

        // Update the user's profile image path
        $user->profile_image = $path;
        $user->save();

        return response()->json(['message' => 'Profile image updated successfully', 'profile_image_url' => Storage::url($path)], 200);
    }

    public function updateProfile(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $request->user()->id,
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Update the user's name and email
        $user = $request->user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return response()->json(['message' => 'Profile updated successfully', 'user' => $user], 200);
    }
}
