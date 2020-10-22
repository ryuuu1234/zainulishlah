<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Validation\ValidationException;

use App\Schedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ScheduleController extends Controller
{   
    public function data_list()
    {   
        $lembaga = Auth::user()->institution_id;

        $group_rombel = Schedule::where([['institution_id', $lembaga], ['hari', request()->hari]])
            ->when(request()->user_id, function($guru){
                $teacher = 'App\Teacher'::where('user_id', request()->user_id)->first();
                $guru = $guru->where('teacher_id', $teacher->id);
            })
            ->select(['rombel', 'kelas'], DB::raw('SUM(rombel) as total_rombel', 'SUM(kelas) as total_kelas'))
            ->groupBy(['rombel', 'kelas'])
            ->get();
        
        $hari= request()->hari;
        
        $data = $this->getArray($group_rombel, $hari);
        return response()->json([
            'type'   => 'success',
            'group_rombel' => $group_rombel,
            'sub_data' => $data
        ], 200);
       
    }
    

    public function getArray($data, $hari)
    {
        $sub= array();
        $lembaga = Auth::user()->institution_id;
        foreach ($data as $key) {
            $mboh = Schedule::where([['institution_id', $lembaga], ['hari', $hari],['kelas', $key->kelas], ['rombel', $key->rombel]])->get();  
            $mboh->load(['clock', 'teacher', 'kelas_mapel']);
            $sub[] = $mboh;
        }

        return $sub;
    }

    public function data_save(Request $request)
    {
        
        $lembaga = Auth::user()->institution_id;
        $kode_mapel = $request->kode_mapel;
        $hari = $request->hari;
        $kelas = $request->kelas;
        $rombel = $request->rombel;
        $jam = $request->jam;
        $mapel = 'App\Kelas_mapel'::where('kode', $kode_mapel)->first();
        if ($mapel) {
            $clock = 'App\Clock'::where('name', $jam)->first();

            $data = Schedule::firstOrCreate(
                [   'hari' => $hari, 'clock_id'=>$clock->id, 'teacher_id' => $mapel->teacher_id ], //cari berdasarkan teacher_id, hari dan jam
                [
                    'kode_mapel' => $kode_mapel, 
                    'kelas' => $kelas,
                    'rombel' => $rombel,
                    'kelas_mapel_id' => $mapel->id,
                    'institution_id' => $lembaga
                
                ]
            );

            if ($data) {
                return response()->json([
                    'type'   => 'success',
                    'message'      =>'Data tersimpan',
                    'status' => $data
                ], 200);
            } else {
                return response()->json([
                    'type'  => 'error',
                    'message' =>'failed'
                ], 500);
            }
        } else {
            return response()->json([
                'type'  => 'error',
                'message' =>'Pilih Mapel dengan Benar'
            ], 500);
        }
     
        
    }

    public function data_list_guru()
    {   
        $lembaga = Auth::user()->institution_id;
        $user_id = Auth::user()->id;

        $teacher = 'App\Teacher'::where('user_id', $user_id)->first();


        $data = Schedule::where([['institution_id', $lembaga], ['hari', request()->hari], ['teacher_id', $teacher->id]])
        ->orderBy('clock_id', 'asc')
        ->get();

        $data->load('clock');
        $data->load('kelas_mapel');

        return response()->json([
            'type'   => 'success',
            'data' => $data
        ], 200);

    }

    public function data_remove($id)
    {
        $data = Schedule::where('id', $id)->delete();
        if ($data) {
            return response()->json([
                'type'   => 'success',
                'message'      =>'Data tersimpan'
            ], 200);
        } else {
            return response()->json([
                'type'  => 'error',
                'message' =>'failed'
            ], 500);
        }
    }

    public function data_reset()
    {
        $data = Schedule::truncate();
        if ($data) {
            return response()->json([
                'type'   => 'success',
                'message'      =>'Data tersimpan'
            ], 200);
        } else {
            return response()->json([
                'type'  => 'error',
                'message' =>'failed'
            ], 500);
        }
    }

    

}
