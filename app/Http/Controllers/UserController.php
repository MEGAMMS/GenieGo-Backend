<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\UpdateUserRequest;
use App\Http\Requests\UploadIconRequest;
use App\Http\Resources\UserResource;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use ApiResponses;

    public function currentUser(Request $request)
    {
        return new UserResource($request->user());
    }

    public function update(UpdateUserRequest $request)
    {
        $user = $request->user();

        // Check if the provided password matches the user's current password
        if (! Hash::check($request->password, $user->password)) {
            return $this->error('Current password is incorrect.', 401);
        }
        // If the password is being updated, hash it
        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password);
        }

        // Update the user's details
        $user->update($request->except('password', 'new_password'));

        return $this->success('User updated successfully', [
            'user' => new UserResource($user),
        ]);
    }

    public function delete(Request $request)
    {
        $user = $request->user();

        // Check if the user is an owner or customer
        if (isset($user->owner)) {
            $user->owner->store->delete();
        }
        if (isset($user->customer)) {
            $user->customer->orders()->delete();
            $user->customer->delete();
        }

        // Finally, delete the user
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully',
        ]);
    }

    /**
     * Handle uploading the user icon.
     */
    public function uploadIcon(UploadIconRequest $request)
    {
        $user = $request->user();

        // Store the uploaded file
        $icon_path = $request->file('icon')->store('user-icons', 'public');

        // Update the user's icon path
        $user->update(['icon' => $icon_path]);

        return $this->success('User icon uploaded successfully', ['icon_url' => asset('storage/'.$icon_path)]);

    }
}
