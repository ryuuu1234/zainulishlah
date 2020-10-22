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
use App\Imports\TeachersImport;

use App\Teacher;
use App\User;
class TeacherController extends Controller
{
    public function data_list_params(){

        $lembaga = Auth::user()->institution_id; // sama dengan current admin institution_id
        $status = 1; // di tabel user()->status
        $data = Teacher::with(['user', 'kelas_mapel'])
        ->whereHas('user', function($q) use($status) {
            // Query the name field in user table
            $q->where('status', '=', $status);
        })
        ->orderBy(request()->sortby, request()->sortbydesc)
        ->where('institution_id', $lembaga)
        ->when(request()->q, function($search) {
                $search = $search->where('name', 'LIKE', '%' . request()->q . '%');
        })->paginate(request()->per_page); 

        return response()->json([
            'status' => 'success', 
            'data' => $data,
            'lembaga'=> $lembaga
            ]
        );
    }

    public function data_save(Request $request)
    {
        $request->validate([
            'name'  =>  'required',
            'email' =>  'required|email|unique:users',
            'nip'   =>  'required',
            'role_id'=> 'required',
            // 'institution_id' => 'required'
        ]);
        
        $lembaga = $request->user()->institution_id;
        $user = User::create([
            'name' => $request->name,
            'username' => $request->nip.$lembaga,
            'email' => $request->email.$lembaga,
            'password' => bcrypt($request->nip),
            'status' => 1,
            'institution_id' => $lembaga,
            'role_id' => intval($request->role_id),
        ]);

       
            // $teacher = new Teacher();
           if ($user) {
                $user->teacher()->create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'nip' => $request->nip,
                    'institution_id' => $lembaga,
                    'alamat' => $request->alamat,
                ]);
           }
       

        return response()->json(['message' => 'Sukses']);

    }

    public function data_by($id)
    {
        $teacher = Teacher::where('id',$id)->with('user')->first();
       
        return response()->json([
            'status' => 'success',
            'data' =>$teacher
        ], 200);
    }

    public function data_update(Request $request, $id)
    {
        $request->validate([
            'name'  =>  'required',
            'email' =>  'required|email|unique:users',
            'nip'   =>  'required',
            'role_id'=> 'required',
        ]);

        $lembaga = $request->user()->institution_id;
        Teacher::where('id', $id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'nip' => $request->nip,
            'institution_id' => $lembaga,
            'alamat' => $request->alamat,
        ]);

        $data = Teacher::where('id', $id)->first();

        $user = User::where('id', $data->user_id)->update([
            'name' => $request->name,
            'username' => $request->nip,
            'email' => $request->email.$lembaga,
            'password' => bcrypt($request->nip),
            'status' => 1,
            'institution_id' => $lembaga,
            'role_id' => $request->role_id,
        ]);

        if ($user) {
            $data->load('user');
            return response()->json(['message'=>'sukses', 'data'=>$data],200);
        } else {
            return response()->json([
                'message'       => 'Error on Updated',
                'status_code'   => 500
            ],500);
        } 
    }

    public function teacher_remove($id)
    {
        $teacher = Teacher::where('id', $id)->first();
        Storage::delete('public/'.$teacher->image);
        $delete = $teacher->delete();
        if ($delete) {
            
            return response()->json(['message'=>'sukses'],200);
        } else {
            return response()->json(['message'=>'failed'],500);
        }
    }

    public function remove_selected(Request $request)
    {   
        $teacher = Teacher::whereIn('id', $request->items);
        foreach ($teacher->get() as $key ) {
            Storage::delete('public/'.$key->image);
        }
        $delete  = $teacher->delete();
        
        if ($delete) {
            
            return response()->json(['message'=>'sukses'],200);
        } else {
            return response()->json(['message'=>'failed'],500);
        }
        
    }

    public function teacher_import(Request $request)
    {
        $file = $request->file('file');
        // $save = Excel::import(new UsersImport, $file);
        $import = new TeachersImport;
        $save = $import->import($file);
        if ($save) {
            return response()->json([
                'message' => 'success',
            ], 200);
        } else {
            return response()->json([
                'message' => 'failed',
                'data' => $save
            ], 500);
        }
    }

    public function upload_image(Request $request, Teacher $teacher)
    {

        $old_path = $teacher->image;
        Storage::delete('public/'.$old_path);
        if($request->hasFile('image')) {
            $request->validate([
                'image'=>'required|image|mimes:jpeg,png,jpg'
            ]);
            $path = $request->file('image')->store('images', 'public');
            $teacher->image = $path;

            User::where('id', $teacher->user_id)->update([
                'image' => $path
            ]);
        }
       
        if ($teacher->save()) {
            return response()->json($teacher,200);
        } else {
            return response()->json([
                'message'       => 'Error on Updated',
                'status_code'   => 500
            ],500);
        } 

    }

    public function upload_image_self(Request $request)
    {
        $teacher_id = Auth::user()->id;
        $teacher = Teacher::where('user_id', $teacher_id)->first();


        $old_path = $teacher->image;
        Storage::delete('public/'.$old_path);
        if($request->hasFile('image')) {
            $request->validate([
                'image'=>'required|image|mimes:jpeg,png,jpg'
            ]);
            $path = $request->file('image')->store('images', 'public');
            $teacher->image = $path;

            User::where('id', $teacher->user_id)->update([
                'image' => $path
            ]);
        }

        // return response()->json([
        //     'message'       => $old_path
        // ],200);
       
        if ($teacher->save()) {
            return response()->json([
                'message'       => 'Foto tersimpan',
            ],200);
        } else {
            return response()->json([
                'message'       => 'Error on Updated',
                'status_code'   => 500
            ],500);
        } 

    }

}
