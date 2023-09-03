<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $publisher = Publisher::orderBy('name', 'ASC')->get();
        return response()->json($publisher);
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:100',
            'website' => 'max:75',
            'country' => 'required|max:75',
            'email' => 'required|email',
            'description' => 'min:5'


        ]);

        try {
            $publisher = new Publisher();
            $publisher->name = $request->name;
            $publisher->website = $request->website;
            $publisher->country = $request->country;
            $publisher->email= $request->email;
            $publisher->description= $request->description;
            $publisher->save();
            return response()->json(['status' => true , 'message' =>"the publisher ".$publisher->name. " was added successfully"]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false , 'message' =>"We have a error please try again " .$th]);
        }

        

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $publisher = Publisher::where('id', '=', $id)->first();
        return response()->json($publisher);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|max:100',
            'website' => 'max:75',
            'country' => 'required|max:75',
            'email' => 'required|email',
            'description' => 'min:5'


        ]);

        try {
            $publisher = Publisher::findOrFail($id);
            $publisher->name = $request->name;
            $publisher->website = $request->website;
            $publisher->country = $request->country;
            $publisher->email= $request->email;
            $publisher->description= $request->description;
            $publisher->save();
            return response()->json(['status' => true , 'message' =>"the publisher ".$publisher->name. " was updated successfully"]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false , 'message' =>"We have a error please try again " .$th]);
        }

        

    

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $publisher = Publisher::findOrFail($id);
            $publisher->delete();
            return response()->json(['status' => true, 'message' => 'The publisher '. $publisher->name . ' was delete successfully' ]);
        } catch (\Throwable $th) {
            return response()->json(['status'=>false,'message' =>'Something is wrong please try again '.$th ]);
        }

    }
}
