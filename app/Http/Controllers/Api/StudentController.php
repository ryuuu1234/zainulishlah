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
use App\Imports\StudentsImport;

use App\Student;
// use App\User;
class StudentController extends Controller
{
    public function data_list_params(){

        $lembaga = Auth::user()->institution_id; // sama dengan current admin institution_id
        $status = 1; // di tabel user()->status
        // $data = Student::with(['user'])
        // ->whereHas('user', function($q) use($status) {
        //     // Query the name field in user table
        //     $q->where('status', '=', $status);
        // })
        $data = Student::orderBy(request()->sortby, request()->sortbydesc)
        ->where([
            ['institution_id', '=' ,$lembaga],
            ])
        ->when(request()->kelas != 'All', function($searchKelas) {
            $searchKelas = $searchKelas->where('kelas', '=', request()->kelas);
        })
        ->when(request()->rombel != 'All', function($searchR) {
            $searchR = $searchR->where('rombel', '=', request()->rombel);
        })
        ->when(request()->jurusan != 'All', function($searchJ) {
            $searchJ = $searchJ->where('jurusan', '=', request()->jurusan);
        })
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
            'nis'   => 'required|unique:students',
            'name'  =>  'required',
        ]);
        
        $lembaga = $request->user()->institution_id;
        $data = Student::create([
            'nis' => $request->nis,
            'nisn' => $request->nisn,
            'nik' => $request->nik,
            'institution_id' => $lembaga,
            'name' => $request->name,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'kelamin' => $request->kelamin,
            'kelas' => $request->kelas,
            'rombel' => $request->rombel,
            'jurusan' => $request->jurusan,
            'alamat' => $request->alamat,
            'provinsi' => $request->provinsi,
            'kab_kota' => $request->kab_kota,
            'kel_des' => $request->kel_des,
            'kodepos' => $request->kodepos,
            'nama_ayah' => $request->nama_ayah,
            'nama_ibu' => $request->nama_ibu,
            'anak_ke' => $request->anak_ke,
            'jml_saudara' => $request->jml_saudara,
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
        $data = Student::where('id',$id)->first();
       
        return response()->json([
            'status' => 'success',
            'data' =>$data
        ], 200);
    }

    public function data_update(Request $request, $id)
    {
        $request->validate([
            // 'nis'   => 'required|unique:students',
            'name'  =>  'required',
        ]);

        $lembaga = $request->user()->institution_id;
        $data = Student::where('id', $id)->update([
            // 'nis' => $request->nis,
            'nisn' => $request->nisn,
            'nik' => $request->nik,
            'institution_id' => $lembaga,
            'name' => $request->name,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'kelamin' => $request->kelamin,
            'kelas' => $request->kelas,
            'rombel' => $request->rombel,
            'jurusan' => $request->jurusan,
            'alamat' => $request->alamat,
            'provinsi' => $request->provinsi,
            'kab_kota' => $request->kab_kota,
            'kel_des' => $request->kel_des,
            'kodepos' => $request->kodepos,
            'nama_ayah' => $request->nama_ayah,
            'nama_ibu' => $request->nama_ibu,
            'anak_ke' => $request->anak_ke,
            'jml_saudara' => $request->jml_saudara,
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

    public function data_remove($id)
    {
        $data = Student::where('id', $id)->first();
        Storage::delete('public/'.$data->image);
        $delete = $data->delete();
        if ($delete) {
            
            return response()->json(['message'=>'sukses'],200);
        } else {
            return response()->json(['message'=>'failed'],500);
        }
    }

    public function remove_selected(Request $request)
    {   
        $data = Student::whereIn('id', $request->items);
        foreach ($data->get() as $key ) {
            Storage::delete('public/'.$key->image);
        }
        $delete  = $data->delete();
        
        if ($delete) {
            return response()->json(['message'=>'sukses'],200);
        } else {
            return response()->json(['message'=>'failed'],500);
        }
        
    }

    public function data_import(Request $request)
    {
        $file = $request->file('file');
        // $save = Excel::import(new UsersImport, $file);
        $import = new StudentsImport;
        $save = $import->import($file);
        if ($save) {
            return response()->json([
                'message' => 'success',
            ], 200);
        } else {
            return response()->json([
                'message' => 'failed',
            ], 500);
        }
    }

    public function upload_image(Request $request, Student $student)
    {

        $old_path = $student->image;
        Storage::delete('public/'.$old_path);
        if($request->hasFile('image')) {
            $request->validate([
                'image'=>'required|image|mimes:jpeg,png,jpg'
            ]);
            $path = $request->file('image')->store('images', 'public');
            $student->image = $path;

            // User::where('id', $teacher->user_id)->update([
            //     'image' => $path
            // ]);
        }
       
        if ($student->save()) {
            return response()->json($student,200);
        } else {
            return response()->json([
                'message'       => 'Error on Updated',
            ],500);
        } 

    }

}
