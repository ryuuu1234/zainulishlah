<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Validation\ValidationException;

use App\Rombel;
use Illuminate\Support\Facades\Auth;

class RombelController extends Controller
{
    public function data_dropdown(){

        $lembaga = Auth::user()->institution_id;
        $data = Rombel::where('institution_id', $lembaga)->get();
        return response()->json([
            'data' => $data
        ],200);
    }

   public function data_save(Request $request)
   {
        $request->validate([
            'name' => 'required|max:3'
        ]);
        $lembaga = Auth::user()->institution_id;     
        $save = Rombel::firstOrCreate(
            ['name' => $request->name, 'institution_id' => $lembaga]
        );
        if ($save) {
            return response()->json([
                'message' => 'success'
            ], 200);
        } else {
            return response()->json([
                'message' => 'failed'
            ], 400);
        }
   }

   public function data_remove($id)   
   {
        $data = Rombel::where('id', $id)->first();
        $del = $data->delete();
        if ($del) {
            return response()->json([
                'message' => 'success'
            ], 200);
        } else {
            return response()->json([
                'message' => 'failed'
            ], 400);
        }
   }

}
