<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Validation\ValidationException;

use App\Jurusan;
use Illuminate\Support\Facades\Auth;

class JurusanController extends Controller
{
    public function data_dropdown(){

        $lembaga = Auth::user()->institution_id;
        $data = Jurusan::where('institution_id', $lembaga)->get();
        return response()->json([
            'data' => $data
        ],200);
    }

}
