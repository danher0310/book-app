<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::with(['genre','publishers', 'authors'])->orderBy('title', 'asc')->get();
        $count = 0;
        foreach($books as $book){
            $books[$count]->raitings = $book->users()->select('userables.*')->get();

        };
        return response()->json($books);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'title' => 'required|max:100',
            'subtitle'=>'min:3|max:255',
            'language' => 'min:3|max:50',
            'page' => 'integer | digits_between:2,4',
            'published' => 'date|date_format:Y-m-d',
            'description' => 'min:20',
            'genre_id' => 'required | integer | exists:genres,id',
            'publisher_id' => 'required |integer | exists:publishers,id',
            'image' => 'nullable|sometimes|image',
        ]);

        try {
            $book = new Book();
            $book->title = $request->title;
            $book->subtitle = $request->subtitle;
            $book->language = $request->language;
            $book->published = $request->published;
            $book->description = $request->description;
            $book->genre_id = $request->genre_id;
            $book->publisher_id =  $request->publisher_id;
            $book->save();
            $image_name = $this->loadImage($request);
            if($image_name != ''){
                $book->image()->create(['url' =>'images/'.$image_name]);
            };

            return response()->json(['state' => 'true', 'message' => 'The book '. $book->title . ' was create successfully.']);

        } catch (\Throwable $th) {
            return response()->json(['status'=>false,'message' =>'Something is wrong please try again.'.$th ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book = Book::with(['genre','publishers', 'authors'])->where('id', '=', $id)->first();
        $image = null;
        if($book->image){
            $image = Storage::url($book->image['url']);
        }
        return response()->json([
            'book' => $book,
            'image' => $image
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = $request->validate([
            'title' => 'required|max:100',
            'subtitle'=>'min:3|max:255',
            'language' => 'min:3|max:50',
            'page' => 'integer | digits_between:2,4',
            'published' => 'date|date_format:Y-m-d',
            'description' => 'min:20',
            'genre_id' => 'required|integer|exists:genres,id',
            'publisher_id' => 'required|integer|exists:publishers,id',
            'image' => 'nullable|sometimes|image',
        ]);

        try {
            $book = Book::findOrFail($id);
            $book->title = $request->title;
            $book->subtitle = $request->subtitle;
            $book->language = $request->language;
            $book->published = $request->published;
            $book->description = $request->description;
            $book->genre_id = $request->genre_id;
            $book->publisher_id =  $request->publisher_id;
            $book->save();
            $image_name = $this->loadImage($request);
            if($image_name != ''){
                $book->image()->create(['url' =>'images/'.$image_name]);
            };

            return response()->json(['statue' => 'true', 'message' => 'The book '. $book->title . ' was create successfully']);

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
            $book = Book::findOrFail($id);
            $book->delete();
            if($book->image()){
                $book->image()->delete();
            }
            
            return response()->json(['status' => true, 'message' => 'The author '. $book->title . ' was delete successfully' ]);
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
