<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Storage;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Imports\UsersImport;

class AuthController extends Controller
{
    public function login(Request $request){
        // $request->validate([
        //     'email'=>'required|email',
        //     'password'=>'required'
        // ]);
    
        // $auth = $request->except(['remember_me']);
        // if (auth()->attempt($auth, $request->remember_me)) {
    
        //     return response()->json(['status' => 'success', 'data' => auth()->user()], 200);
        // }
    
        // return response()->json(['status' => 'failed']);
    
        // auth()->attempt($request->only('email', 'password'));
        // return auth()->user()
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required'
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
    
        return $user->createToken($request->device_name)->plainTextToken;
    }

    public function export()
    {   
        $user = User::whereIn('id',request()->id)->get();
        // $filename = Carbon::now()->format('Ymdhms').'-Products.xlsx';
        // Excel::store(new UsersExport, $filename);
        // $fullPath = Storage::disk('local')->path($filename);

        return response()->json([
            'data' => $user
        ], 200);
    }

    public function import(Request $request)
    {
        $file = $request->file('file');
        // $save = Excel::import(new UsersImport, $file);
        $import = new UsersImport;
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

    public function user_login(Request $request) {
        return $request->user();
    }

    public function user_list_params(){

        $data = User::orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($search) {
                $search = $search->where('name', 'LIKE', '%' . request()->q . '%');
        })->paginate(request()->per_page); 

        return response()->json([
            'status' => 'success', 
            'data' => $data,
            ]
        );
    }

    public function user_status($id){
        $user = User::where('id',$id)->first();
        $user->status == 0? $status=1:$status=0;
        $save = User::where('id',$id)->update(['status' => $status]);
        
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

    public function user_by($id){
        $user = User::where('id',$id)->first();
       
            return response()->json([
                'status' => 'success',
                'data' =>$user
            ], 200);
    }

    public function update_data(Request $request, User $user)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required|email',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($user->save()) {
            return response()->json($user,200);
        } else {
            return response()->json([
                'message'       => 'Error on Updated',
                'status_code'   => 500
            ],500);
        } 
    }

    public function upload_image(Request $request, User $user){

        $old_path = $user->image;
        if($request->hasFile('image')) {
            $request->validate([
                'image'=>'required|image|mimes:jpeg,png,jpg'
            ]);
            $path = $request->file('image')->store('images', 'public');
            $user->image = $path;

            Storage::delete('public/'.$old_path);
        }
       
        if ($user->save()) {
            return response()->json($user,200);
        } else {
            return response()->json([
                'message'       => 'Error on Updated',
                'status_code'   => 500
            ],500);
        } 
        // return response()->json($request->all(),200);

    }

    public function user_remove($id){
        $user = User::where('id', $id)->first();
        Storage::delete('public/'.$user->image);
        $delete = $user->delete();
        if ($delete) {
            
            return response()->json(['message'=>'sukses'],200);
        } else {
            return response()->json(['message'=>'failed'],500);
        }
    }

    public function remove_selected(Request $request)
    {   
        $user = User::whereIn('id', $request->items);
        foreach ($user->get() as $key ) {
            Storage::delete('public/'.$key->image);
        }
        $delete  = $user->delete();
        
        if ($delete) {
            
            return response()->json(['message'=>'sukses'],200);
        } else {
            return response()->json(['message'=>'failed'],500);
        }
        
    }

    // public function logout(){
    //     auth()->logout();
    //     return response('');
    // }

    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return response()->json('logout', 201);
    }

    
}
