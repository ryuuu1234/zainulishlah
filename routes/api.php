<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => 'auth:sanctum'], function () {
// Route::middleware('auth:sanctum', 'throttle:5000,1')->group( function () {

    // Authcontroller
    Route::post('/logout', 'Api\AuthController@logout');
    Route::get('/user', 'Api\AuthController@user_login');
    Route::get('/user/profile', 'Api\AuthController@profile');
    Route::get('/user_list_params', 'Api\AuthController@user_list_params');
    Route::put('/user_status/{id}/edit', 'Api\AuthController@user_status');
    Route::get('/user_by/{id}/edit', 'Api\AuthController@user_by');
    Route::delete('/user_remove/{id}', 'Api\AuthController@user_remove');
    Route::post('/remove_selected', 'Api\AuthController@remove_selected');
    Route::put('/upload_image/{user}', 'Api\AuthController@upload_image');
    Route::put('/user-update/{user}', 'Api\AuthController@update_data');
    Route::get('/users-export', 'UsersExportController@export');
    Route::post('/users-import', 'Api\AuthController@import');

    //teacherController
    Route::get('/teacher_list_params', 'Api\TeacherController@data_list_params');
    Route::post('/teacher_save', 'Api\TeacherController@data_save');
    Route::get('/teacher_by/{id}/edit', 'Api\TeacherController@data_by');
    Route::put('/teacher_update/{id}', 'Api\TeacherController@data_update');
    Route::delete('/teacher_remove/{id}', 'Api\TeacherController@teacher_remove');
    Route::post('/teacher_remove_selected', 'Api\TeacherController@remove_selected');
    Route::put('/teacher_status/{id}/edit', 'Api\TeacherController@teacher_status');
    Route::put('/teacher_upload_image/{teacher}', 'Api\TeacherController@upload_image');
    Route::put('/teacher_upload_image_self', 'Api\TeacherController@upload_image_self');
    Route::post('/teacher_import', 'Api\TeacherController@teacher_import');

    // StudentController
    Route::get('/student_list_params', 'Api\StudentController@data_list_params');
    Route::post('/student_import', 'Api\StudentController@data_import');
    Route::post('/student_save', 'Api\StudentController@data_save');
    Route::get('/student_by/{id}/edit', 'Api\StudentController@data_by');
    Route::put('/student_update/{id}', 'Api\StudentController@data_update');
    Route::put('/student_upload_image/{student}', 'Api\StudentController@upload_image');
    Route::delete('/student_remove/{id}', 'Api\StudentController@data_remove');
    Route::post('/student_remove_selected', 'Api\StudentController@remove_selected');
    Route::get('/student_export', 'StudentExportController@export');

    // roleControllers
    Route::get('/role_dropdown', 'Api\RoleController@role_dropdown');

    // KelasController
    Route::get('/kelas_dropdown', 'Api\KelasController@data_dropdown');

    // RombelController
    Route::get('/rombel_dropdown', 'Api\RombelController@data_dropdown');
    Route::post('/rombel_save', 'Api\RombelController@data_save');
    Route::delete('/rombel_remove/{id}', 'Api\RombelController@data_remove');

    // Kelas_RombelController
    Route::get('/kelas_rombel_dropdown', 'Api\KelasRombelController@data_dropdown');
    Route::get('/kelas_rombel_sinkron', 'Api\KelasRombelController@data_sinkron');
    // Route::post('/kelas_rombel_save', 'Api\KelasRombelController@data_save');

     // Kelas_MapelController
     Route::get('/kelas_mapel_dropdown', 'Api\KelasMapelController@data_dropdown');
     Route::get('/kelas_mapel_with_status', 'Api\KelasMapelController@data_all_status');
     Route::get('/kelas_mapel_all', 'Api\KelasMapelController@data_all');
     Route::get('/kelas_mapel_all_with_params', 'Api\KelasMapelController@data_all_with_params');
     Route::get('/kelas_mapel_by/{id}', 'Api\KelasMapelController@data_by');
     Route::get('/kelas_mapel_sinkron', 'Api\KelasMapelController@data_sinkron');
     Route::post('/kelas_mapel_upd_status', 'Api\KelasMapelController@data_status_update');
     Route::get('/kelas_mapel_remove_teacher/{id}', 'Api\KelasMapelController@data_remove_teacher');

    // JurusanController
    Route::get('/jurusan_dropdown', 'Api\JurusanController@data_dropdown');

     // MapelController
     Route::get('/mapel_dropdown', 'Api\MapelController@data_dropdown');
     Route::post('/mapel_save', 'Api\MapelController@data_save');
     Route::delete('/mapel_remove/{id}', 'Api\MapelController@data_remove');


    // MenuCOntrollers
    Route::get('/menu_all', 'Api\MenuController@all_menu');

    // TapelControllers
    Route::get('/tapel_by_status', 'Api\TapelController@get_list_aktif');
    Route::post('/tapel_save', 'Api\TapelController@data_save');
    Route::get('/tapel_by/{id}/edit', 'Api\TapelController@data_by');
    Route::put('/tapel_update/{id}', 'Api\TapelController@data_update');

    // SemesterController
    Route::get('/semester_all', 'Api\SemesterController@get_dropdown');
    Route::get('/semester_by_status', 'Api\SemesterController@get_list_aktif');
    Route::put('/semester_update/{id}', 'Api\SemesterController@data_update');


    // KalenderPendidikan
    Route::get('/calendar_all', 'Api\CalendarController@data_all');
    Route::get('/calendar_all_with_params', 'Api\CalendarController@data_all_with_params');
    Route::post('/calendar_save', 'Api\CalendarController@data_save');
    Route::delete('/calendar_remove/{id}', 'Api\CalendarController@data_remove');

     // Absen
     Route::post('/absen_save', 'Api\AbsenController@data_save');
     Route::get('/absen_cek', 'Api\AbsenController@data_cek');

    // get jenis hari
    Route::get('/jenis_tgl_dropdown', 'Api\Jenis_tglController@data_dropdown');


    // ScheduleController
    Route::post('/schedule_save', 'Api\ScheduleController@data_save');
    Route::get('/schedule_list', 'Api\ScheduleController@data_list');
    Route::get('/schedule_guru', 'Api\ScheduleController@data_list_guru');
    Route::delete('/schedule_remove/{id}', 'Api\ScheduleController@data_remove');
    Route::delete('/schedule_reset', 'Api\ScheduleController@data_reset');
    // Route::get('/schedule_params', 'Api\ScheduleController@data_list_params');

    // SlideController
    Route::get('/slide_list_params', 'Api\SlideController@data_list_params');
    // Route::post('/slide_import', 'Api\SlideController@data_import');
    Route::post('/slide_save', 'Api\SlideController@data_save');
    Route::get('/slide_by/{id}/edit', 'Api\SlideController@data_by');
    Route::put('/slide_update/{id}', 'Api\SlideController@data_update');
    Route::put('/slide_upload_image/{slide}', 'Api\SlideController@upload_image');
    Route::delete('/slide_remove/{id}', 'Api\SlideController@data_remove');
    Route::post('/slide_remove_selected', 'Api\SlideController@remove_selected');
    // Route::get('/slide_export', 'SlideExportController@export');


     // LembagaController
     Route::get('/lembaga_list_params', 'Api\LembagaController@data_list_params');
     // Route::post('/lembaga_import', 'Api\LembagaController@data_import');
     Route::post('/lembaga_save', 'Api\LembagaController@data_save');
     Route::get('/lembaga_by/{id}/edit', 'Api\LembagaController@data_by');
     Route::put('/lembaga_update/{id}', 'Api\LembagaController@data_update');
     Route::put('/lembaga_upload_image/{lembaga}', 'Api\LembagaController@upload_image');
     Route::delete('/lembaga_remove/{id}', 'Api\LembagaController@data_remove');
     Route::post('/lembaga_remove_selected', 'Api\LembagaController@remove_selected');


     // BeritaController
    Route::get('/berita_list_params', 'Api\BeritaController@data_list_params');
    // Route::post('/berita_import', 'Api\BeritaController@data_import');
    Route::post('/berita_save', 'Api\BeritaController@data_save');
    Route::get('/berita_by/{id}/edit', 'Api\BeritaController@data_by');
    Route::put('/berita_update/{id}', 'Api\BeritaController@data_update');
    Route::put('/berita_upload_image/{berita}', 'Api\BeritaController@upload_image');
    Route::delete('/berita_remove/{id}', 'Api\BeritaController@data_remove');
    Route::post('/berita_remove_selected', 'Api\BeritaController@remove_selected');


    // get clock
    Route::get('/clock_dropdown', 'Api\ClockController@data_dropdown');

     // get dashboard
     Route::get('/dashboard_data', function () {

        $lembaga = Auth::user()->institution_id;
        $user = 'App\User'::where('institution_id', $lembaga)->count();
        $teacher = 'App\Teacher'::where('institution_id', $lembaga)->count();
        $student = 'App\Student'::where('institution_id', $lembaga)->count();
        $kelas = 'App\Kelas_rombel'::where('institution_id', $lembaga)->count();
        return response()->json([
            'user' => $user,
            'guru' => $teacher,
            'siswa' => $student,
            'kelas' => $kelas,
        ], 200);
    });


    // get data kelamin
    Route::get('/kelamin_dropdown', function () {

        $data = [
            ['id' => 1, 'name' => 'L'],
            ['id' => 2, 'name' => 'P'],
        ];
        return response()->json([
            'data' => $data
        ], 200);
    });

    // get data hari
    Route::get('/hari_dropdown', function () {

        $data = [
            ['id' => 1, 'name' => 'Senin'],
            ['id' => 2, 'name' => 'Selasa'],
            ['id' => 3, 'name' => 'Rabu'],
            ['id' => 4, 'name' => 'Kamis'],
            ['id' => 5, 'name' => 'Jumat'],
            ['id' => 6, 'name' => 'Sabtu'],
            ['id' => 7, 'name' => 'Minggu'],
        ];
        return response()->json([
            'data' => $data
        ], 200);
    });

   
});

// Route::post('/login', function (Request $request) {

//     $request->validate([
//         'email'=>'required|email',
//         'password'=>'required'
//     ]);

//     $auth = $request->except(['remember_me']);
//     if (auth()->attempt($auth, $request->remember_me)) {

//         return response()->json(['status' => 'success', 'data' => auth()->guru()], 200);
//     }

//     return response()->json(['status' => 'failed']);

//     // auth()->attempt($request->only('email', 'password'));
//     // return auth()->user();    
// });
// Route::post('logout', function () {

//     auth()->logout();
//     return response('');    
// });
Route::post('/login', 'Api\AuthController@login');
// Route::post('/logout', 'Api\AuthController@logout')->middleware('auth:sanctum');
Route::post('/register', 'RegisterController@store');


Route::get('/slide_list_front', 'SliderController@data_list_front');

// LEMBAGA FRONTEND
Route::get('/lembaga_list_front', 'LembagaController@data_list_front');
Route::get('/lembaga_front_by/{id}/edit', 'LembagaController@data_by');

// BERITA FRONTEND
Route::get('/berita_terbaru', 'BeritaController@data_berita_terbaru');
Route::get('/berita_all', 'BeritaController@data_berita');
Route::get('/berita_front_by/{slug}/edit', 'BeritaController@data_by');