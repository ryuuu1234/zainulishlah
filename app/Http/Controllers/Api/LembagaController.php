<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\UsersExport;
// use App\Imports\LembagaImport;

use App\Lembaga;
// use App\User;
class LembagaController extends Controller
{   
   

    public function data_list_params(){

        // $data = Lembaga::with(['user'])
        // ->whereHas('user', function($q) use($status) {
        //     // Query the name field in user table
        //     $q->where('status', '=', $status);
        // })
        $data = Lembaga::orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($search) {
                $search = $search->where('name', 'LIKE', '%' . request()->q . '%');
        })->paginate(request()->per_page); 

        return response()->json([
            'status' => 'success', 
            'data' => $data
            ]
        );
    }

    public function data_save(Request $request)
    {
        $request->validate([
            'name'   => 'required',
            'description'  =>  'required',
        ]);
        
        $data = Lembaga::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

       
            // $teacher = new Teacher();
           if ($data) {
                return response()->json(['message' => 'Sukses', 'data' => $data], 200);
           } else {
            return response()->json(['message' => 'Gagal'], 400);
           }

    }

    public function data_by($id)
    {
        $data = Lembaga::where('id',$id)->first();
       
        return response()->json([
            'status' => 'success',
            'data' =>$data
        ], 200);
    }

    public function data_update(Request $request, $id)
    {
        $request->validate([
            'name'   => 'required',
            'description'  =>  'required',
        ]);

        $data = Lembaga::where('id', $id)->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // $data hasilnya  = 1

        if ($data) {
            // $data->load('user');
            return response()->json(['message'=>'sukses'],200);
        } else {
            return response()->json([
                'message'       => 'Error on Updated'
            ],500);
        } 
    }

    // public function data_remove($id)
    // {
    //     $data = Lembaga::where('id', $id)->first();
    //     Storage::delete('public/'.$data->image);
    //     $delete = $data->delete();
    //     if ($delete) {
            
    //         return response()->json(['message'=>'sukses'],200);
    //     } else {
    //         return response()->json(['message'=>'failed'],500);
    //     }
    // }

    // public function remove_selected(Request $request)
    // {   
    //     $data = Lembaga::whereIn('id', $request->items);
    //     foreach ($data->get() as $key ) {
    //         Storage::delete('public/'.$key->image);
    //     }
    //     $delete  = $data->delete();
        
    //     if ($delete) {
    //         return response()->json(['message'=>'sukses'],200);
    //     } else {
    //         return response()->json(['message'=>'failed'],500);
    //     }
        
    // }

    // public function data_import(Request $request)
    // {
    //     $file = $request->file('file');
    //     // $save = Excel::import(new UsersImport, $file);
    //     $import = new LembagasImport;
    //     $save = $import->import($file);
    //     if ($save) {
    //         return response()->json([
    //             'message' => 'success',
    //         ], 200);
    //     } else {
    //         return response()->json([
    //             'message' => 'failed',
    //         ], 500);
    //     }
    // }

    // public function upload_image(Request $request, Lembaga $lembaga)
    // {

    //     $old_path = $lembaga->image;
    //     Storage::delete('public/'.$old_path);
    //     if($request->hasFile('image')) {
    //         $request->validate([
    //             'image'=>'required|image|mimes:jpeg,png,jpg'
    //         ]);
    //         $path = $request->file('image')->store('images', 'public');
    //         $lembaga->image = $path;
    //     }
       
    //     if ($lembaga->save()) {
    //         return response()->json($lembaga,200);
    //     } else {
    //         return response()->json([
    //             'message'       => 'Error on Updated',
    //         ],500);
    //     } 

    // }

}
