<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Validation\ValidationException;

use App\Clock;
use Illuminate\Support\Facades\Auth;

class ClockController extends Controller
{
    public function data_dropdown(){

        // $tapel = 'App\Tapel'::where('status', 1)->first();
        // $semester = 'App\Semester'::where('status', 1)->first();
        $data = Clock::all();
        return response()->json([
            'data' => $data
        ],200);
    }

}
