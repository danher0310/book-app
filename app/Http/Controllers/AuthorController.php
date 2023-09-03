<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authors = Author::orderBy('full_name', 'ASC')->get();
        $count = 0;
        foreach ($authors as $author) {
            $authors[$count]->raitings = $author->users()->select('userables.*')->get();
            $count++;
            
        }
        return response()->json($authors);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)

    {
        $validated = $request->validate([
            'full_name' => 'required|max:75',
            'birth_date' => 'date|date_format:Y-m-d',
            'country' => 'max:75',
            'image' => 'nullable|sometimes|image',
        ]);

        
        try {
            $author = New Author();
            $author->full_name = $request->full_name;
            $author->birth_date = $request->birth_date;
            $author->country = $request->country;
            $author->save();
            $image_name = $this->loadImage($request);
            if($image_name != ''){
                $author->image()->create(['url' => 'images/'.$image_name]);
            }

            return response()->json(['status' => true, 'message' =>'The author '. $author->full_name . ' was create successfully']);

        } catch (\Throwable $th) {
            return response()->json(['status'=>false,'message' =>'Something is wrong please try again '.$th ]);

            
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $author = Author::with(['profile'])->where('id', '=', $id)->first();
        $image = null;
        if($author->image){
            $image = Storage::url($author->image['url']);

        }
        return response()->json(['author' => $author, 'image'=> $image]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'full_name' => 'required|max:75',
            'birth_date' => 'date|date_format:Y-m-d',
            'country' => 'max:75',
            'image' =>'nullable|sometimes|image',
        ]);


        try {
            $author = Author::findOrFail($id);
            
            $author->full_name = $request->full_name;
            $author->birth_date = $request->birth_date;
            $author->country = $request->country;
            $author->save();
            $image_name = $this->loadImage($request);
            if($image_name != ''){
                $author->image()->update(['url'=> 'images/'.$image_name]);
            }
            
            return response()->json(['status' => true, 'message' => 'The author '.$author->full_name. ' was update successfully']);
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
            $author = Author::findOrFail($id);
            $author->delete();
            if($author->image()){
                $author->image()->delete();
            }
            return response()->json(['status' => true, 'message' => 'The author '. $author->full_name . ' was delete successfully' ]);
        } catch (\Throwable $th) {
            return response()->json(['status'=>false,'message' =>'Something is wrong please try again '.$th ]);
        }
    }

    public function loadImage(Request $request){
        $image_name = '';
        if($request->hasFile('image')){
            $destination_path = 'public/images';
            $image = $request->file('image');
            $image_name = time().'_'.$image->getClientOriginalName();
            $request->file('image')->storeAs($destination_path, $image_name);

        }
        return $image_name;

    }
}
