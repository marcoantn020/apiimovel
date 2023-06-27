<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\APIMessagesErrors;
use App\Http\Controllers\Controller;
use App\Http\Requests\userProfile\StoreUserProfileRequest;
use App\Http\Requests\userProfile\UpdateUserProfileRequest;

class UserProfileController extends Controller
{
    private UserProfileController $userProfile;

    public function __construct(UserProfileController $userProfile)
    {
        $this->userProfile = $userProfile;
    }

    public function index()
    {
        $userProfile = $this->userProfile->paginate(10);
        return response()->json($userProfile, 200);
    }

    public function show($id)
    {
        try {
            $userProfile = $this->userProfile->find($id);
            return response()->json(["data" => $userProfile], 200);
        } catch (\Exception $e) {
            $messages = new APIMessagesErrors($e->getMessage());
            return response()->json($messages->getMessage(), 400);
        }
    }

    public function store(StoreUserProfileRequest $request)
    {
        try {
            $data = $request->validated();

            $data['password'] = bcrypt($data["password"]);
            $user = $this->userProfile->create($data);

            return response()->json([
                "message" => "Perfil de usuario criado com sucesso"
            ], 201);
        } catch (\Exception $e) {
            $messages = new APIMessagesErrors($e->getMessage());
            return response()->json($messages->getMessage(), 400);
        }
    }

    public function update($id, UpdateUserProfileRequest $request)
    {
        try {
            $data = $request->validated();
            $user = $this->userProfile->findOrFail($id);
            $user->update($data);

            return response()->json([
                "message" => "Perfil de usuario atualizado com sucesso"
            ], 201);
        } catch (\Exception $e) {
            $messages = new APIMessagesErrors($e->getMessage());
            return response()->json($messages->getMessage(), 400);
        }
    }

    public function destroy($id)
    {
        try {

            $user = $this->userProfile->findOrFail($id);
            $user->delete();

            return response()->json([
                "message" => "Perfil de usuario deletado com sucesso",
            ], 201);
        } catch (\Exception $e) {
            $messages = new APIMessagesErrors($e->getMessage());
            return response()->json($messages->getMessage(), 400);
        }
    }
}
