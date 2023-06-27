<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\APIMessagesErrors;
use App\Http\Controllers\Controller;
use App\Http\Requests\realSate\RealStateRequest;
use App\Http\Requests\realSate\UpdateRealStateRequest;
use App\Models\RealState;
use Illuminate\Support\Str;

class RealStateController extends Controller
{
    private RealState $realState;

    public function __construct(RealState $realState)
    {
        $this->realState = $realState;
    }

    public function index()
    {
        $real_state = $this->realState->paginate(10);
        return response()->json($real_state, 200);
    }

    public function show($id)
    {
        try {
            $real_state = $this->realState->find($id);
            return response()->json(["data" => $real_state], 200);
        } catch (\Exception $e) {
            $messages = new APIMessagesErrors($e->getMessage());
            return response()->json($messages->getMessage(), 400);
        }
    }

    public function store(RealStateRequest $request)
    {
        try {
            $data = $request->validated();
            $data['slug'] = Str::slug($data['title']);
            $real_state = $this->realState->create($data);
            if(isset($data["categories"]) && count($data["categories"])) {
                $r = $real_state->categories()->sync($data["categories"]);
            }

            return response()->json([
                "message" => "Imovel criado com sucesso"
            ], 201);
        } catch (\Exception $e) {
            $messages = new APIMessagesErrors($e->getMessage());
            return response()->json($messages->getMessage(), 400);
        }
    }

    public function update($id, UpdateRealStateRequest $request)
    {
        try {
            $data = $request->validated();

            if($data["title"]) {
                $data['slug'] = Str::slug($data['title']);
            }

            $real_state = $this->realState->findOrFail($id);
            $real_state->update($data);

            if(isset($data["categories"]) && count($data["categories"])) {
                $real_state->categories()->sync($data["categories"]);
            }

            return response()->json([
                "message" => "Imovel atualizado com sucesso",
                "real_state" => $data
            ], 201);
        } catch (\Exception $e) {
            $messages = new APIMessagesErrors($e->getMessage());
            return response()->json($messages->getMessage(), 400);
        }
    }

    public function destroy($id)
    {
        try {

            $real_state = $this->realState->findOrFail($id);
            $real_state->delete();

            return response()->json([
                "message" => "Imovel deletado com sucesso",
            ], 201);
        } catch (\Exception $e) {
            $messages = new APIMessagesErrors($e->getMessage());
            return response()->json($messages->getMessage(), 400);
        }
    }
}
