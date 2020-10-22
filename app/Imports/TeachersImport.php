<?php

namespace App\Imports;

use App\User;
use App\Teacher;
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


use Illuminate\Support\Facades\Auth;

class TeachersImport implements 
    ToCollection, 
    // WithHeadingRow,
    WithStartRow, 
    SkipsOnError, 
    // WithValidation,
    WithBatchInserts
{   
    use Importable, SkipsErrors;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    // public function model(array $row)
    // {
    //     return new Teacher([
    //         'name' => $row['name'],
    //         'email' => $row['email'],
    //         'nip' => $row['nip'],
    //         'alamat' => $row['alamat'],
            
    //     ]);
    // }

    // public function rules():array
    // {
    //     return [
    //         // '*.email'=> ['email', 'unique:users,email']
    //     ];
    // }

    public function collection(Collection $rows)
    {
        //  Validator::make($rows->toArray(), [
        //      '*.0' => 'required',
        //  ])->validate();
        $lembaga = Auth::user()->institution_id;    
        foreach ($rows as $row) {

            $user = User::create([
                'username' => $row[0].$lembaga,
                'password' => bcrypt($row[0]), 
                'name' => $row[1],
                'email' => $row[2].$lembaga,
                'institution_id' => $lembaga,
                'role_id' => $row[4],
                'status' => 1
            ]);

            // if ($user) {
                $user->teacher()->create([
                    'nip' => $row[0],
                    'name' => $row[1],
                    'email' => $row[2],
                    'institution_id' => $lembaga,
                    'alamat' => $row[3],
                ]);
            // }
            
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
