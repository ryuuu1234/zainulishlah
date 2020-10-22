<?php

namespace App\Imports;

use App\Student;
// use App\Student;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithStartRow;
// use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Validator;


use Illuminate\Support\Facades\Auth;

class StudentsImport implements 
    ToCollection, 
    // WithHeadingRow,
    WithStartRow, 
    SkipsOnError, 
    // WithValidation,
    WithBatchInserts
{   
    use Importable, SkipsErrors;
   
    public function collection(Collection $rows)
    {
         Validator::make($rows->toArray(), [
             '*.0' => 'required',
             '*.1' => 'required',
             '*.3' => 'required',
             '*.7' => 'required',
             '*.8' => 'required',
         ])->validate();
        $lembaga = Auth::user()->institution_id;    
        foreach ($rows as $row) {

            Student::create([
                'nis' => $row[0],
                'nisn' => $row[1],
                'nik' => $row[2],
                'institution_id' => $lembaga,
                'name' => $row[3],
                'tempat_lahir' => $row[4],
                'tanggal_lahir' => $row[5],
                'kelamin' => $row[6],
                'kelas' => $row[7],
                'rombel' => $row[8],
                'jurusan' => $row[9],
                'status' => 1,
                'alamat' => $row[10],
                'provinsi' => $row[11],
                'kab_kota' => $row[12],
                'kel_des' => $row[13],
                'kodepos' => $row[14],
                'nama_ayah' => $row[15],
                'nama_ibu' => $row[16],
                'anak_ke' => $row[17],
                'jml_saudara' => $row[18],
            ]);

           
            
        }
    }

    public function startRow(): int
    {
        return 2;
    }

    public function batchSize(): int
    {
        return 1000;
    }
}
