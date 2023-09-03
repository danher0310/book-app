<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteBookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $book = Book::findOrFail($id);        
        return response()->json(['book' => $book, 'note' => $book->note()->where('user_id', '=', auth()->user()->id)->get()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'description'=> 'required|min:5',
            'writing_date' => 'date|date_format:Y-m-d',
            'book.id' => 'required|integer|exists:books,id',
            'user.id' => 'required|integer|exists:users,id'
        ]);

        try {
            $book = Book::findOrFail($request->book['id']);
            $book->note()->create([
            'description' => $request->description,
            'writing_date' => $request->writing_date,
            'user_id' => $request->user['id']            
            ]);
            return response()->json([
                'status' => true, 
                'message' => "The book's note ".$book->title. " was created succesful" 
            ]);
        } catch (\Throwable $th) {
            return response()->json(['status' =>false, 'message' => 'Error when we tried to deleted the note' . $th]);
        }




        

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'description'=> 'required|min:5',
            'writing_date' => 'date|date_format:Y-m-d',
            'book.id' => 'required|integer|exists:books,id',
            'user.id' => 'required|integer|exists:users,id'
        ]);

        try {
            $book = Note::findOrFail($id);
            $book->description = $request->description;
            $book->writing_date = $request->writing_date;
            $book->save();
            return response()->json([
                'status' => true,
                'message' => "The book's note was update successfully"
            ]);            
        } catch (\Throwable $th) {
            return response()->json([
                'status' =>false, 
                'message' => 'Error when we tried to update the note' . $th]);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $note = Note::findOrFail($id);
            $note->delete();
            return response()->json([
                'status' =>true,
                'message' => 'The note was deleted successfully'

            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' =>false, 
                'message' => 'Error when we tried to deleted the note' . $th]);
        }
    }
}
