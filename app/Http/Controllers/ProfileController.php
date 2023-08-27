<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
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

        $validated = $request->validate([

            'career' => 'required|max:75',            
            'website' => 'max:75',
            'email' => 'email|max:75',
            'author.id' => 'required|integer|exists:authors,id',

        ]);

        try {
            $profile = new Profile();
            $profile->career = $request->career;
            $profile->biography = $request->biography;
            $profile->website = $request->website;
            $profile->email = $request->email;
            $profile->author_id = $request->author['id'];
            $profile->save();
            return response()->json(['status' => true, 'message' => 'The author profile '. $request->author['full_name']. ' was create successfully.']);

        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message'=>'Something is wrong, when we are trying to register the profile '.$th]);

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
            
            'career' => 'required|max:75',            
            'website' => 'max:75',
            'email' => 'email|max:75',
            'author.id' => 'required|integer|exists:authors,id',
            

        ]);

        try {
            $profile = Profile::findOrFail($id);
            $profile->career = $request->career;
            $profile->biography = $request->biography;
            $profile->website = $request->website;
            $profile->email = $request->email;
            $profile->save();
            return response()->json(['status'=> true, 'message' => 'The profile of the author '.$request->author['full_name']. ' was update successfuly']);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message'=>'Something is wrong, when we are trying to updated the profile '.$th]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
    }
}
