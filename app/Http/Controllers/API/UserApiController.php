<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserApiController extends Controller
{
    /**
     * Get data user login.
     */
    public function index()
    {
        $users = User::paginate(2);

        return $this->sendResponse(UserResource::collection($users)->resource, 'Get data success!');
    }

    /**
     * Save data user.
     */
    public function store(StoreUserRequest $request)
    {
        $request->validated();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return $this->sendResponse(UserResource::collection($user), 'Save data success!');
    }

    /**
     * Show data user.
     */
    public function show($id)
    {
        $user = User::find($id);

        return $this->sendResponse(UserResource::collection($user), 'Show data user success!');
    }

    /**
     * Update data user.
     */
    public function update(StoreUserRequest $request, $id)
    {
        $user = User::find($id);

        $request->validated();

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return $this->sendResponse(new UserResource($user), 'Update data user success!');
    }

    /**
     * Delete data user.
     */
    public function destroy($id)
    {
        try {
            // Search user by ID
            $user = User::find($id);
            // Delete user
            $user->delete();

            return $this->sendResponse(null, 'Delete data user success!');
        } catch (Exception $e) {
            return $this->sendError('Error delete', $e->getMessage());
        }
    }
}
