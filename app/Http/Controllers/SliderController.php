<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Slide;
// use App\User;
class SliderController extends Controller
{   
   
    public function data_list_front()
    {
       $data = Slide::where('status', 1)->get();

       return response()->json([
            'status' => 'success', 
            'data' => $data
            ]
        );
    }
    

}
