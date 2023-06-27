<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\APIMessagesErrors;
use App\Http\Controllers\Controller;
use App\Http\Requests\category\StoreCategoryRequest;
use App\Http\Requests\category\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{

    private Category $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function index()
    {
        $category = $this->category->paginate('10');

        return response()->json($category, 200);
    }


    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();

        try{
            $data['slug'] = Str::slug($data['name']);
            $category = $this->category->create($data);
            return response()->json([
                'data' => [
                    'message' => 'Categoria cadastrada com sucesso!'
                ]
            ], 200);

        } catch (\Exception $e) {
            $message = new APIMessagesErrors($e->getMessage());
            return response()->json($message->getMessage(), 400);
        }
    }

    public function show($id)
    {
        try{

            $category = $this->category->findOrFail($id);

            return response()->json([
                'data' => $category
            ], 200);

        } catch (\Exception $e) {
            $message = new APIMessagesErrors($e->getMessage());
            return response()->json($message->getMessage(), 400);
        }
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        $data = $request->validated();

        try{

            $category = $this->category->findOrFail($id);
            $category->update($data);

            return response()->json([
                'data' => [
                    'message' => 'Categoria atualizada com sucesso!'
                ]
            ], 200);

        } catch (\Exception $e) {
            $message = new APIMessagesErrors($e->getMessage());
            return response()->json($message->getMessage(), 400);
        }
    }

    public function destroy($id)
    {
        try{

            $category = $this->category->findOrFail($id);

            return response()->json([
                'data' => [
                    'message' => 'Categoria removida com sucesso!'
                ]
            ], 200);

        } catch (\Exception $e) {
            $message = new APIMessagesErrors($e->getMessage());
            return response()->json($message->getMessage(), 400);
        }
    }

    public function realStates($id)
    {
        try{

            $category = $this->category->findOrFail($id);

            return response()->json([
                'data' => $category->realStates
            ], 200);

        } catch (\Exception $e) {
            $message = new APIMessagesErrors($e->getMessage());
            return response()->json($message->getMessage(), 400);
        }
    }
}
