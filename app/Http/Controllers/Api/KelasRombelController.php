<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Validation\ValidationException;

use App\Kelas_rombel;
use Illuminate\Support\Facades\Auth;

class KelasRombelController extends Controller
{
    public function data_dropdown(){

        $lembaga = Auth::user()->institution_id;
        $data = Kelas_rombel::where('institution_id', $lembaga)
        ->when(request()->name, function($search) {
            $search = $search->where('name', 'LIKE', '%' . request()->name . '%');
        })
        ->get();
        return response()->json([
            'data' => $data
        ],200);
    }

    public function data_sinkron()
   {
        
        $lembaga = Auth::user()->institution_id;
        $kelas = 'App\Kelas'::where('institution_id', $lembaga)->get();     
        $rombel = 'App\Rombel'::where('institution_id', $lembaga)->get(); 
        $jurusan = 'App\Jurusan'::where('institution_id', $lembaga)->get(); 
        
        foreach ($kelas as $key ) {
            if ($jurusan->isEmpty()) {

                foreach ($rombel as $row) {
                    $save = Kelas_rombel::firstOrCreate(
                        ['name' => $key->name."-".$row->name, 'institution_id' => $lembaga],
                        ['kelas'=> $key->name, 'rombel'=>$row->name]
                    );
                }

            } else {
                foreach($rombel as $row) {
                    foreach ($jurusan as $mboh) {
                        $save = Kelas_rombel::firstOrCreate(
                            ['name' => $mboh->name."-".$key->name."-".$row->name, 'institution_id' => $lembaga],
                            ['kelas'=> $key->name, 'rombel'=>$row->name, 'jurusan'=>$mboh->name]
                        );
                    }
        
                }
            }
        }
       
        if ($save) {
            return response()->json([
                'message' => 'success'
            ], 200);
        } else {
            return response()->json([
                'message' => 'failed'
            ], 500);
        }
   }

//    public function data_save(Request $request)
//    {
//         $request->validate([
//             'name' => 'required|max:3'
//         ]);
//         $lembaga = Auth::user()->institution_id;     
//         $save = Rombel::firstOrCreate(
//             ['name' => $request->name, 'institution_id' => $lembaga]
//         );
//         if ($save) {
//             return response()->json([
//                 'message' => 'success'
//             ], 200);
//         } else {
//             return response()->json([
//                 'message' => 'failed'
//             ], 400);
//         }
//    }

//    public function data_remove($id)   
//    {
//         $data = Rombel::where('id', $id)->first();
//         $del = $data->delete();
//         if ($del) {
//             return response()->json([
//                 'message' => 'success'
//             ], 200);
//         } else {
//             return response()->json([
//                 'message' => 'failed'
//             ], 400);
//         }
//    }

}
