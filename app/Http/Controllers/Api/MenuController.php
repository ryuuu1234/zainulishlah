<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Validation\ValidationException;

use App\Menu;
class MenuController extends Controller
{
    public function all_menu(){

        $menu = Menu::orderBy('urut', 'ASC')
        ->with('submenu')
        ->get();
        // $menu->load('submenu');
        return response()->json([
            'data' => $menu
        ],200);
    }

}
