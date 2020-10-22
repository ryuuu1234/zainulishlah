<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Validation\ValidationException;

use App\Jenis_tgl;
use Illuminate\Support\Facades\Auth;

class Jenis_tglController extends Controller
{
    public function data_dropdown(){

        $data = Jenis_tgl::all();
        return response()->json([
            'data' => $data
        ],200);
    }

}
