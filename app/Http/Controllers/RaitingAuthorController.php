<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;


class RaitingAuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'number_star'=> 'required|integer',
            'author.id' => 'required|integer|exists:authors,id',
            'user.id' => 'required|integer|exists:users,id'
        ]);
        try {
            $author = Author::findOrFail($request->author['id']);
            $author->users()->attach($request->user['id'], ['number_star' => $request->number_star]);
            return response()->json(['status' => true, 'message' => 'The Author score '.$author->full_name. 'was created successfully' ]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Error when we tried to register '. $th]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $author = Author::findOrFail($id);
        return response()->json(['author'=> $author, 'raiting' => $author->users()->where('user_id', '=', auth()->user()->id)->select('userables.*')->get()] );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = $request->validate([
            'number_star'=> 'required|integer',
            'author.id' => 'required|integer|exists:authors,id',
            'user.id' => 'required|integer|exists:users,id'
        ]);
        
        try {
            $author = Author::findOrFail($request->author['id']);
            $author->users()->updateExistingPivot($request->user['id'], ['number_star' => $request->number_star]);
            return response()->json(['status' => true, 'message'=> 'The author raiting '. $author->full_name. ' was updated succesfully. ' ]);

        } catch (\Throwable $th) {
            //throw $th;
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
