<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Maatwebsite\Excel\Excel;
use App\Exports\StudentsExport;
use Response;

class StudentExportController extends Controller
{   
    public function export()
    {
        $filename = 'students.xlsx';
        $ret = (new StudentsExport(request()->id))->store($filename);
        if($ret){
            return response()->json([
                'message'       => 'Success',
                'data'   => Storage::url($filename),
            ],200);
        }
    }

    function getFile($filename){
    	return Storage::download($filename);
    }
}
