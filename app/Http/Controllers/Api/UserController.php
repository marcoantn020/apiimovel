<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\APIMessagesErrors;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        $user = $this->user->paginate(10);
        return response()->json($user, 200);
    }

    public function show($id)
    {
        try {
            $user = $this->user->with('profile')->findOrFail($id);
            $user->profile->social_networks = unserialize($user->profile->social_networks);

            return response()->json(["data" => $user], 200);
        } catch (\Exception $e) {
            $messages = new APIMessagesErrors($e->getMessage());
            return response()->json($messages->getMessage(), 400);
        }
    }

    public function store(UserRequest $request)
    {
        try {
            $data = $request->validated();

            $data['password'] = bcrypt($data["password"]);
            $user = $this->user->create($data);

            $user->profile()->create([
                "phone" => $data["phone"],
                "mobile_phone" => $data["mobile_phone"]
            ]);

            return response()->json([
                "message" => "Usuario criado com sucesso"
            ], 201);
        } catch (\Exception $e) {
            $messages = new APIMessagesErrors($e->getMessage());
            return response()->json($messages->getMessage(), 400);
        }
    }

    public function update($id, UserUpdateRequest $request)
    {
        try {
            $profile = $request->all()['profile'];
            $profile['social_networks'] = serialize($profile['social_networks']);

            $data = $request->validated();
            if($request->has('password') && $request->get('password')) {
                $data["password"] = bcrypt($data["password"]);
            }
            $user = $this->user->findOrFail($id);
            $user->update($data);

            $user->profile()->update($profile);

            return response()->json([
                "message" => "Usuario atualizado com sucesso"
            ], 201);
        } catch (\Exception $e) {
            $messages = new APIMessagesErrors($e->getMessage());
            return response()->json($messages->getMessage(), 400);
        }
    }

    public function destroy($id)
    {
        try {

            $user = $this->user->findOrFail($id);
            $user->delete();

            return response()->json([
                "message" => "Usuario deletado com sucesso",
            ], 201);
        } catch (\Exception $e) {
            $messages = new APIMessagesErrors($e->getMessage());
            return response()->json($messages->getMessage(), 400);
        }
    }
}
