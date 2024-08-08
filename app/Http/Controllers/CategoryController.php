<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\NoteResource;
use App\Models\Category;
use App\Models\Note;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
  public function index(Request $request)
  {
    $user = $request->user();
    $categories = $user->categories()->get();

    return response()->json(['categories' => CategoryResource::collection($categories)]);
  }

  public function store(StoreCategoryRequest $request)
  {
    $validatedData = $request->validationData();

    $userId = auth()->id();
    $validatedData['user_id'] = $userId;

    $category = Category::create($validatedData);

    return response()->json(['category' => new CategoryResource($category)]);
  }

  public function destroy(Request $request, $id)
  {
    $user = $request->user();

    $category = Category::where('id', $id)->where('user_id', $user->id)->first();

    if (!$category) {
      return response()->noContent(404);
    }

    $category->delete();

    return response()->noContent(204);
  }

  public function getNotes(Request $request, $idCategory)
  {
    $user = $request->user();

    $category = Category::where('id', $idCategory)->where('user_id', $user->id)->first();
    if (!$category) {
      return response()->noContent(404);
    }

    $notes = Note::where('category_id', $idCategory)->where('user_id', $user->id)->orderBy('created_at')->get();

    return response()->json(['notes' => NoteResource::collection($notes)]);
  }
}
