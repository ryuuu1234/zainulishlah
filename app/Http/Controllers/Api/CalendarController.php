<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Validation\ValidationException;

use App\Calendar;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function data_all(){

        $tapel = 'App\Tapel'::where('status', 1)->first();
        $semester = 'App\Semester'::where('status', 1)->first();
        $data = Calendar::where([['tapel_id',$tapel->id], ['semester_id', $semester->id]])
        ->get();
        $data->load('jenis_tgl');
        return response()->json([
            'data' => $data
        ],200);
    }
    
    public function data_all_with_params(){

        $tapel = 'App\Tapel'::where('status', 1)->first();
        $semester = 'App\Semester'::where('status', 1)->first();
        $data = Calendar::where([['tapel_id',$tapel->id], ['semester_id', $semester->id]])
        ->paginate(10);
        $data->load('jenis_tgl');
        return response()->json([
            'data' => $data
        ],200);
    }

    public function data_save(Request $request)
    {
        $lembaga = Auth::user()->institution_id;
        $items = $request->items;
        foreach ($items as $item ) {
            $save = Calendar::create([
                'tapel_id' => $request->tapel_id,
                'semester_id' => $request->semester_id,
                'institution_id' => $lembaga,
                'jenis_tgl_id' => $request->jenis_tgl_id,
                'tanggal_format' => $item
            ]);
        }

        if ($save) {
            return response()->json([
                'message' => 'Success'
            ],200);
        } else {
            return response()->json([
                'message' => 'Failed'
            ],500);
        }
    }

    public function data_remove($id)
    {
        $del = Calendar::where('id', $id)->delete();
        if ($del) {
            return response()->json([
                'type'=>'success',
                'message'=>'Data berhasil dihapus',
            ],200);
        } else {
            return response()->json([
                'type'=>'error',
                'message'=>'Data Error',
            ],500);
        }
    }

}
