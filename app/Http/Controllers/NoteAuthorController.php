<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteAuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $author = Author::findOrFail($id);
        
        return response()->json(['author' => $author, 'note' => $author->note()->where('user_id', '=', auth()->user()->id)->get()]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)

    {
        $validated = $request->validate([
            'description' => 'required|min:5',
            'writing_date' => 'date|date_format:Y-m-d',
            'author.id' => 'required|integer|exists:authors,id',
            'user.id' => 'required|integer|exists:users,id',
        ]);

        try {
            $author = Author::findOrFail($request->author['id']);

            $author->note()->create(['description' => $request->description, 'writing_date' => $request->writing_date, 'user_id'=> $request->user['id']]);
            return response()->json(['status' => true, 'message' => "The author's note ". $author->full_name . " was create succesfully"]);

        } catch (\Throwable $th) {
            return response()->json(['status' =>false, 'message' => 'Error when we tried to register the note' . $th]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'description' => 'required|min:5',
            'writing_date' => 'date|date_format:Y-m-d',
            'author.id' => 'required|integer|exists:authors,id',
            'user.id' => 'required|integer|exists:users,id',
        ]);
        try {
            $note = Note::findOrFail($id);
            $note->description = $request->description;
            $note->writing_date = $request->writing_date;
            $note->save();
            return response()->json(['status' => true, 'message' => "The author's note  was update successfully"]);
        } catch (\Throwable $th) {
            return response()->json(['status' =>false, 'message' => 'Error when we tried to update the note' . $th]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $note = Note::findOrFail($id);
            $note->delete();
            return response()->json(['status' => true, 'message' => 'The note was deleted successfully']);
        }catch (\Throwable $th) {
            return response()->json(['status' =>false, 'message' => 'Error when we tried to deleted the note' . $th]);
        }
    }
}
