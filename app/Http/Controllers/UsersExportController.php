<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

use Maatwebsite\Excel\Excel;
use App\Exports\UsersExport;
// use Facade\FlareClient\Http\Response;
use Response;

class UsersExportController extends Controller
{   
    // private $excel;
    // public function __construct(Excel $excel)
    // {   
    //     $this->excel = $excel;
    // }
    public function export()
    {
        // return Excel::download(new UsersExport, 'users.xlsx'); // INI JIKa TDK PAKAI eXPORTABLE
        // return (new UsersExport)->download('users.xlsx'); // ini jika tdk pake Responsable
        
        // return new UsersExport;
        // return $this->excel->download(new UsersExport, 'users.csv');// untuk csv
        // return $this->excel->download(new UsersExport, 'users.csv', Excel::CSV);// begini jg bisa
        // return $this->excel->download(new UsersExport, 'users.pdf', Excel::DOMPDF);// untuk pdf pake composer dompdf
        // return $this->excel->download(new UsersExport, 'users.xlsx'); // unruk xlsx
        // return $this->excel->store(new UsersExport, 'users.xlsx'); // unruk xlsx simpan di storage
        // return (new UsersExport(request()->id))->download('users.xlsx');
        // dd(request()->id);
        $filename = 'users.xlsx';
        $ret = (new UsersExport(request()->id))->store($filename);
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
