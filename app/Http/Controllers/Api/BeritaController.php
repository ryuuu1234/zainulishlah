<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use App\Berita;
// use App\User;
class BeritaController extends Controller
{
    public function data_list_params(){

        
        $data = Berita::orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($search) {
                $search = $search->where('title', 'LIKE', '%' . request()->q . '%');
        })->paginate(request()->per_page); 

        return response()->json([
            'status' => 'success', 
            'data' => $data
            ]
        );
    }

    public function data_save(Request $request)
    {
        $request->validate([
            'title'   => 'required',
            'content'  =>  'required',
        ]);

        $text = $request->title;
        $slug = $this->slugify($text);

        $data = Berita::create([
            'title' => $request->title,
            'content' => $request->content,
            'slug' => $slug,
        ]);

        if ($data) {
            return response()->json(['message' => 'Sukses'], 200);
        } else {
        return response()->json(['message' => 'Gagal'], 400);
        }

    }

    public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    public function data_by($id)
    {
        $data = Berita::where('id',$id)->first();
       
        return response()->json([
            'status' => 'success',
            'data' =>$data
        ], 200);
    }

    public function data_update(Request $request, $id)
    {
        $request->validate([
            'title'   => 'required',
            'content'  =>  'required',
        ]);
        
        $text = $request->title;
        $slug = $this->slugify($text);

        $data = Berita::where('id', $id)->update([
            'title' => $request->title,
            'content' => $request->content,
            'slug' => $slug,
        ]);

        // $data hasilnya  = 1

        if ($data) {
            // $data->load('user');
            return response()->json(['message'=>'sukses'],200);
        } else {
            return response()->json([
                'message'       => 'Error on Updated'
            ],500);
        } 
    }

    public function data_remove($id)
    {
        $data = Berita::where('id', $id)->first();
        Storage::delete('public/'.$data->image);
        $delete = $data->delete();
        if ($delete) {
            
            return response()->json(['message'=>'sukses'],200);
        } else {
            return response()->json(['message'=>'failed'],500);
        }
    }

    public function remove_selected(Request $request)
    {   
        $data = Berita::whereIn('id', $request->items);
        foreach ($data->get() as $key ) {
            Storage::delete('public/'.$key->image);
        }
        $delete  = $data->delete();
        
        if ($delete) {
            return response()->json(['message'=>'sukses'],200);
        } else {
            return response()->json(['message'=>'failed'],500);
        }
        
    }

    public function upload_image(Request $request, Berita $berita)
    {

        $old_path = $berita->image;
        Storage::delete('public/'.$old_path);
        if($request->hasFile('image')) {
            $request->validate([
                'image'=>'required|image|mimes:jpeg,png,jpg'
            ]);
            // $path = '/home/{your_shared_hosting_username}/public_html/cover_image';
            $path = $request->file('image')->store('images', 'public');
            $berita->image = $path;
        }
       
        if ($berita->save()) {
            return response()->json($berita,200);
        } else {
            return response()->json([
                'message'       => 'Error on Updated',
            ],500);
        } 

    }

}
