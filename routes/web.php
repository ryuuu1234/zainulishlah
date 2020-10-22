<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

use App\User;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/reset_users/zaish', function () {
    dd(User::where('id', '>', 1)->delete());
});

// Auth::routes();
// Ini untuk download export
Route::get('/users/export', function(){
    return Storage::download('users.xlsx');
});

Route::get('/students/export', function(){
    return Storage::download('students.xlsx');
});

// Ini untuk download template 
Route::get('/teachers/download', function(){
    return Storage::download('template_guru.xlsx');
});
Route::get('/students/download', function(){
    return Storage::download('template_siswa.xlsx');
});

// Lihat Storage
Route::get('/preview_storrage', function () {
    echo asset('storage/file.txt');
});



// lihat kolom di table 
Route::get('/preview_column', function () {
    // Schema
    $table ="students";
    $request_name = "student";
    $columns = Schema::getColumnListing($table); // users table
    // return response()->json($columns);

    // ini untuk exsport
    foreach ($columns as $key) 
    {	
        $column_name = $key;
        echo '$'.$request_name.'->'.$column_name.',<br>';  // ini untuk students export
    }

    echo '<br />';
    echo '=========================================== iNI untuk Array headingnya';
    echo '<br />';

    foreach ($columns as $key) 
    {	
        $column_name = $key;
        echo "'".$column_name."',<br>" ;
    }
        
});

// percobaan
Route::get('/percobaan', function () {
    $kelas = 'App\Kelas'::where('institution_id', 2)->get();
    $rombel = 'App\Rombel'::where('institution_id', 2)->get();
    $jurusan = 'App\Jurusan'::where('institution_id', 1)->get();
    if ($jurusan->isEmpty()) {
        echo 'kosong';
    } else {
        echo 'Ada';
    }
    die;
    foreach ($kelas as $key ) {
        foreach($rombel as $row) {
            foreach ($jurusan as $mboh) {
                echo $key->name.'-'.$row->name.'-'.$mboh->name. '<br>';
            }

        }
    }
});



