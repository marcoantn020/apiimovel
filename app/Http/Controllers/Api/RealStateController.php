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
        $real_state = auth('api')->user()->real_state();
        return response()->json($real_state->paginate(10), 200);
    }

    public function show($id)
    {
        try {
            $real_state = auth('api')->user()->real_state()->with('photos')->find($id);
            return response()->json(["data" => $real_state], 200);
        } catch (\Exception $e) {
            $messages = new APIMessagesErrors($e->getMessage());
            return response()->json($messages->getMessage(), 400);
        }
    }

    public function store(RealStateRequest $request)
    {
        try {
            $images = $request->file('images');

            $data = $request->validated();
            $data['user_id'] = auth('api')->user()->id;
            $data['slug'] = Str::slug($data['title']);

            $real_state = $this->realState->create($data);
            if(isset($data["categories"]) && count($data["categories"])) {
                $real_state->categories()->sync($data["categories"]);
            }

            if($images) {
                $imagesUploaded = [];
                foreach ($images as $image) {
                    $path = $image->store('images', 'public');
                    $imagesUploaded[] = [
                        'photo' => $path,
                        'is_thumb' => false,
                        'real_state_id' => $real_state->id
                    ];
                }

                $real_state->photos()->insert($imagesUploaded);
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
            $images = $request->file("images");

            if($data["title"]) {
                $data['slug'] = Str::slug($data['title']);
            }

            $real_state = auth('api')->user()->real_state()->findOrFail($id);
            $real_state->update($data);

            if(isset($data["categories"]) && count($data["categories"])) {
                $real_state->categories()->sync($data["categories"]);
            }

            if($images) {
                $imagesUploaded = [];
                foreach ($images as $image) {
                    $path = $image->store('images', 'public');
                    $imagesUploaded[] = [
                        'photo' => $path,
                        'is_thumb' => false,
                        'real_state_id' => $real_state->id
                    ];
                }
                $real_state->photos()->insert($imagesUploaded);
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

            $real_state = auth('api')->user()->real_state()->findOrFail($id);
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
