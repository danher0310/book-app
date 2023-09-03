<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class RaitingBookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'number_star' => 'required|integer',
            'book.id' => 'required|integer|exists:books,id',
            'user.id' => 'required|integer|exists:users,id'
        ]);

        try {
            $book = Book::findOrFail($request->book['id']);

            $book->users()->attach($request->user['id'],[
                'number_star' => $request->number_star
            ]);
            return response()->json([
                'status' => true,
                'message' => 'The book score ' .$book->title . ' was saved successfully'
            ]);

        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Error when we tried to register '. $th]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book = Book::findOrFail($id);
        return response()->json([
            'book' => $book,
            'raiting' => $book->users()->where('user_id', '=', auth()->user()->id)->select('userables.*')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = $request->validate([
            'number_star' => 'required|integer',
            'book.id' => 'required|integer|exists:books,id',
            'user.id' => 'required|integer|exists:users,id'
        ]);
        try {
            $book = Book::findOrFail($request->book['id']);
            $book->users()->updateExistingPivot($request->user['id'],[
                'number_star' => $request->number_star
            ]);
            return response()->json([
                'status' => true,
                'message' => ' The book raiting '. $book->title. ' was updated successfully '
            ]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Error to update the register '. $th]); 
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
