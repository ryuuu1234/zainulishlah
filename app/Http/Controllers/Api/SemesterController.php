<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Validation\ValidationException;

use App\Semester;
class SemesterController extends Controller
{
    public function get_dropdown(){
        $data = Semester::all();
        return response()->json([
            'data' => $data
        ]);
    }
    public function get_list_aktif(){

        $data = Semester::where('status', 1)->first();
        return response()->json([
            'data' => $data
        ]);
    }

    public function data_update(Request $request, $id) {
        $request->validate([
            'id'=> 'required'
        ]);

        $save = Semester::where('id',$id)->update([
            'status'=> 1
            
            ]);
        if ($save) {
            Semester::where('id', '!=', $id)->update(['status'=>0]);
            return response()->json([
                'message'=> 'Success',
                'data' => Semester::where('status', 1)->first()
            ]);
        } else {
            return response()->json([
                'message'=> 'Data error'
            ]);
        }
    }

}
