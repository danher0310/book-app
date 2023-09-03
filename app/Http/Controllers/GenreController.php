<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $genre = Genre::orderBy('name', 'ASC')->get();
        return response()->json($genre);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:100',
            'description' => 'required|min:5',

        ]);

        try {
            $genre = new Genre();
            $genre->name = $request->name;
            $genre->description = $request->description;
            $genre->save();
            return response()->json(['status' => true, 'message' =>'The genrer '. $genre->name . ' was create successfully']);

        } catch (\Throwable $th) {
            return response()->json(['status'=>false,'message' =>'Something is wrong please try again '.$th ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $genre = Genre::where('id', '=', $id)->first();

        return response()->json($genre);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|max:100',
            'description' => 'required|min:5',

        ]);
        try {
            $genre = Genre::findOrFail($id);
            $genre->name = $request->name;
            $genre->description = $request->description;
            $genre->save();
            return response()->json(['status' => true, 'message' =>'The genrer '. $genre->name . ' was updated successfully']);

        } catch (\Throwable $th) {
            return response()->json(['status'=>false,'message' =>'Something is wrong please try again '.$th ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $genre = Genre::findOrFail($id);
            $genre->delete();
            return response()->json(['status' => true, 'message' => 'The genre '. $genre->name . ' was delete successfully' ]);
        } catch (\Throwable $th) {
            return response()->json(['status'=>false,'message' =>'Something is wrong please try again '.$th ]);
        }
    }
}
