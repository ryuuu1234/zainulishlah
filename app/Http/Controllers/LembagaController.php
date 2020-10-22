<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Lembaga;
// use App\User;
class LembagaController extends Controller
{   
   
    public function data_list_front()
    {
       $data = Lembaga::where('status', 1)->get();

       return response()->json([
            'status' => 'success', 
            'data' => $data
            ]
        );
    }

    public function data_by($id)
    {
       $data = Lembaga::where('id', $id)->first();

       return response()->json([
            'status' => 'success', 
            'data' => $data
            ]
        );
    }
    

}
