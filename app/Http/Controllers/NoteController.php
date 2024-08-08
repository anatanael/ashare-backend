<?php

namespace App\Http\Controllers;

use App\Http\Requests\Note\StoreNoteRequest;
use App\Http\Requests\Note\StorePublicNoteRequest;
use App\Http\Resources\NoteResource;
use App\Models\Category;
use App\Models\Note;

class NoteController extends Controller
{
  public function store(StoreNoteRequest $request)
  {
    $validatedData = $request->validationData();

    $userId = auth()->id();
    $validatedData['user_id'] = $userId;

    $categoryId = $validatedData['category_id'];

    $category = Category::where('id', $categoryId)->where('user_id', $userId)->first();
    if (!$category) {
      return response()->json(['message' => 'Category not found'], 403);
    }

    $note = Note::create($validatedData);

    return response()->json(['note' => new NoteResource($note)]);
  }

  public function show($id)
  {
    $userId = auth()->user()->id;
    $note = Note::where('id', $id)->where('user_id', $userId)->first();

    if (!$note) {
      return response()->noContent(404);
    }

    return response()->json(['note' => new NoteResource($note)]);
  }

  public function destroy($id)
  {
    $user_id = auth()->id();
    $note = Note::where('id', $id)->where('user_id', $user_id)->first();

    if (!$note) {
      return response()->noContent(404);
    }

    $note->delete();

    return response()->noContent(204);
  }

  public function indexPublic()
  {
    $notes = Note::where('user_id', null)
      ->orderBy('created_at')
      ->get();

    return response()->json(['notes' => NoteResource::collection($notes)]);
  }

  public function storePublic(StorePublicNoteRequest $request)
  {
    $validatedData = $request->validationData();

    $note = Note::create($validatedData);

    return response()->json(['note' => new NoteResource($note)]);
  }

  public function showPublic($id)
  {
    $note = Note::where('id', $id)->where('user_id', null)->first();

    if (!$note) {
      return response()->noContent(404);
    }

    return response()->json(['note' => new NoteResource($note)]);
  }

  public function destroyPublic($id)
  {
    $note = Note::where('id', $id)->where('user_id', null)->first();

    if (!$note) {
      return response()->noContent(404);
    }

    $note->delete();

    return response()->noContent(204);
  }
}
