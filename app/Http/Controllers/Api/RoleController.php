<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Validation\ValidationException;

use App\Role;
class RoleController extends Controller
{
    public function role_dropdown(){

        $role = Role::where('id', '>', 1)->get();
        return response()->json([
            'data' => $role
        ],200);
    }

}
