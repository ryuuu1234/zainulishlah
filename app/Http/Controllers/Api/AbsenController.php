<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Validation\ValidationException;

use App\Absen;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class AbsenController extends Controller
{   
    public function data_save(Request $request)
    {
        $user = Auth::user()->id;
        $teacher = 'App\Teacher'::where('user_id', $user)->first();

        $save = Absen::firstOrCreate(
            ['hari' => $request->hari, 'teacher_id'=>$teacher->id], // cari berdasarkan hari dan teacher_id
            [
                'status' => 1,
            ]
        );

        if ($save) {
            return response()->json([
                'type'   => 'success',
                'message'      =>'Anda telah Absen Hari ini'
            ], 200);
        } else {
            return response()->json([
                'type'  => 'error',
                'message' =>'failed'
            ], 500);
        }

        // $data = Schedule::firstOrCreate(
        //     [   'hari' => $hari, 'clock_id'=>$clock->id, 'teacher_id' => $mapel->teacher_id ], //cari berdasarkan teacher_id, hari dan jam
        //     [
        //         'kode_mapel' => $kode_mapel, 
        //         'kelas' => $kelas,
        //         'rombel' => $rombel,
        //         'kelas_mapel_id' => $mapel->id,
        //         'institution_id' => $lembaga
            
        //     ]
        // );
    }

    public function data_cek()
    {
       $tanggal = request()->tanggal;
       $user_id =  Auth::user()->id;

       $teacher = 'App\Teacher'::where('user_id', $user_id)->first();

       $data = Absen::where([
           ['created_at', $tanggal], ['teacher_id', $teacher->id]
       ])->first();

       return response()->json(['data'=>$data]);
    }

}
