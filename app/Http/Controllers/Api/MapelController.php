<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Validation\ValidationException;

use App\Mapel;
use Illuminate\Support\Facades\Auth;

class MapelController extends Controller
{
    public function data_dropdown()
    {

        $lembaga = Auth::user()->institution_id;
        $data = Mapel::where('institution_id', $lembaga)->get();
        return response()->json([
            'data' => $data
        ],200);
    }

    public function data_save(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'kode' => 'required'
        ]);
        $lembaga = Auth::user()->institution_id;     
        $save = Mapel::firstOrCreate(
            ['name' => $request->name, 'kode'=>$request->kode, 'institution_id' => $lembaga]
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
        $data = Mapel::where('id', $id)->first();
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
