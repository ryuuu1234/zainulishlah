<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Validation\ValidationException;

use App\Tapel;
class TapelController extends Controller
{
    public function get_list_aktif(){

        $menu = Tapel::where('status', 1)->get();
        return response()->json([
            'data' => $menu
        ]);
    }

    public function data_save(Request $request) {
        $request->validate([
            'name'=> 'required'
        ]);

        $save = Tapel::create([
            'name'=>$request->name,
            'status'=> 1
            
            ]);
        if ($save) {
            Tapel::where('id', '!=', $save->id)->update(['status'=>0]);
            // $data = Tapel::where('status', 1)->first();
            return response()->json([
                'message'=> 'Success',
                // 'data' => $data
            ]);
        } else {
            return response()->json([
                'message'=> 'Data error'
            ]);
        }
    }

    public function data_by($id){
        $data= Tapel::where('id', $id)->first();
        return response()->json(['data'=>$data], 200);
    }

    public function data_update(Request $request, $id){

        $request->validate([
            'name'=> 'required'
        ]);
        $save = Tapel::where('id', $id)->update([
            'name' =>$request->name
        ]);
        
        if ($save) {
            return response()->json([
                'message'=> 'Success'
            ]);
        }else {
            return response()->json([
                'message'=> 'Data error'
            ]);
        }
    }

}
