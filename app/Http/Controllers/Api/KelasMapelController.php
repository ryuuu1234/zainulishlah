<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Validation\ValidationException;

use App\Kelas_mapel;
use Illuminate\Support\Facades\Auth;

class KelasMapelController extends Controller
{   
    public function data_all()
    {   
        $lembaga = Auth::user()->institution_id;
        $data = Kelas_mapel::where('institution_id', $lembaga)->get();
        return response()->json([
            'data' => $data
        ],200);
    }
    public function data_all_status()
    {   
        $lembaga = Auth::user()->institution_id;
        $data = Kelas_mapel::where([['institution_id', $lembaga], ['status', 0]])->get();
        return response()->json([
            'data' => $data
        ],200);
    }
    public function data_by($id)
    {   
        // $lembaga = Auth::user()->institution_id;
        $data = Kelas_mapel::where('teacher_id', $id)->get();
        return response()->json([
            'data' => $data
        ],200);
    }
    public function data_dropdown(){

        $lembaga = Auth::user()->institution_id;
        $data = Kelas_mapel::where('institution_id', $lembaga)
        ->when(request()->q, function($search) {
            $search = $search->where('name', 'LIKE', '%' . request()->q . '%');
        })
        ->orderBy('name', 'ASC')
        ->paginate(10);
        return response()->json([
            'data' => $data
        ],200);
    }

    public function data_all_with_params()
    {
        $lembaga = Auth::user()->institution_id;
        $data = Kelas_mapel::where([['institution_id', $lembaga],['status', 1]])
        ->when(request()->kelas != '', function($searchKelas) {
            $searchKelas = $searchKelas->where('kelas', '=', request()->kelas);
        })
        ->when(request()->rombel != '', function($searchR) {
            $searchR = $searchR->where('rombel', '=', request()->rombel);
        })
        ->orderBy('name', 'ASC')
        ->get();
        $data->load('teacher');
        return response()->json([
            'data' => $data
        ],200);
    }

    public function data_sinkron()
    {
        
        $lembaga = Auth::user()->institution_id;
        $kelas = 'App\Kelas_rombel'::where('institution_id', $lembaga)->get();
        $mapel = 'App\Mapel'::where('institution_id', $lembaga)->get();     
        
        
        foreach ($kelas as $key ) {
            foreach ($mapel as $row) {
                $save = Kelas_mapel::firstOrCreate(
                    ['kode' => $row->kode."-".$key->name, 'institution_id' => $lembaga],
                    ['name'=> $row->name,'kelas'=> $key->kelas, 'rombel'=>$key->rombel]
                );
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

    public function data_status_update(Request $request)
    {
       $data = Kelas_mapel::whereIn('kode', $request->items)->update([
           'status'=> 1,
            'teacher_id' => $request->teacher   
        ]);
       if ($data) {
            return response()->json([
                'message' => 'success'
            ], 200);
        } else {
            return response()->json([
                'message' => 'failed'
            ], 500);
        }
    }

    public function data_remove_teacher($id)
    {
        $data = Kelas_mapel::where('id', $id)->update([
            'status'=> 0,
             'teacher_id' => null   
         ]);
         if ($data) {
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
//         $save = Mapel::firstOrCreate(
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
//         $data = Mapel::where('id', $id)->first();
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
