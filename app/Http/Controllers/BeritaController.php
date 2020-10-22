<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Berita;
// use App\User;
class BeritaController extends Controller
{   

    public function data_berita()
    {  
        $limit = request()->limit;
        $data = Berita::orderByDesc('created_at')->paginate(20);

        return response()->json([
                'status' => 'success', 
                'data' => $data
                ]
            );
    }
   
    public function data_berita_terbaru()
    {  
        $limit = request()->limit;
       $data = Berita::orderByDesc('created_at')->limit($limit)->get();

       return response()->json([
            'status' => 'success', 
            'data' => $data
            ]
        );
    }

    public function data_by($slug)
    {   
        
       $data = Berita::where('slug', $slug)->first();

       return response()->json([
            'status' => 'success', 
            'data' => $data
            ]
        );
    }

    
    

}
