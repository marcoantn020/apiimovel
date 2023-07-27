<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\APIMessagesErrors;
use App\Http\Controllers\Controller;
use App\Models\RealStatePhoto;
use Illuminate\Support\Facades\Storage;

class RealStatePhotoController extends Controller
{
    private RealStatePhoto $realStatePhoto;

    public function __construct(RealStatePhoto $realStatePhoto)
    {
        $this->realStatePhoto = $realStatePhoto;
    }

    public function setThumb($photoId, $realStateId)
    {
        try {
            $photo = $this->realStatePhoto
                ->where("real_state_id", $realStateId)
                ->where("is_thumb", true);

            if($photo->count()) {
                $photo->first()->update(["is_thumb" => false]);
            }

            $photo_updated = $this->realStatePhoto->find($photoId);
            if($photo_updated) {
                $photo_updated->update(["is_thumb" => true]);
            }

            return response()->json([
                "message" => "Thumb atualizada com sucesso",
            ], 200);
        } catch (\Exception $e) {
            $messages = new APIMessagesErrors($e->getMessage());
            return response()->json($messages->getMessage(), 400);
        }
    }

    public function remove($photoId)
    {
        try {

            $photo = $this->realStatePhoto->find($photoId);

            if($photo) {
                Storage::disk('public')->delete($photo->photo);
                $photo->delete();
            }

            return response()->json([
                "message" => "Thumb deletada com sucesso",
            ], 200);
        } catch (\Exception $e) {
            $messages = new APIMessagesErrors($e->getMessage());
            return response()->json($messages->getMessage(), 400);
        }
    }
}
