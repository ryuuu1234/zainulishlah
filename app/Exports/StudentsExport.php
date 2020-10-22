<?php

namespace App\Exports;

use App\Student;
use Illuminate\Database\Eloquent\Collection;
// use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
// use Maatwebsite\Excel\Concerns\FromArray;
// use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class StudentsExport implements 
    // FromCollection, 
    ShouldAutoSize, 
    WithMapping, 
    WithHeadings,
    WithEvents,
    FromQuery
{
    use Exportable;


    protected $id;

    function __construct($id) 
    {
            $this->id = $id;
    }

    public function query()
    {
        // return Student::query()->with('role')->has('role');
        return Student::query()->whereIn('id', $this->id);
    }

    public function map($student): array
    {
        return [
            // $student->id,
            $student->nis,
            $student->nisn,
            $student->nik,
            // $student->institution_id,
            $student->name,
            $student->tempat_lahir,
            $student->tanggal_lahir,
            $student->kelamin,
            $student->kelas,
            $student->rombel,
            $student->jurusan,
            // $student->status,
            // $student->image,
            $student->alamat,
            $student->provinsi,
            $student->kab_kota,
            $student->kel_des,
            $student->kodepos,
            $student->nama_ayah,
            $student->nama_ibu,
            $student->anak_ke,
            $student->jml_saudara,
            // $student->created_at,
            // $student->updated_at,
        ];
    }

    public function headings(): array
    {
        return [
            // 'id',
            'nis',
            'nisn',
            'nik',
            // 'institution_id',
            'name',
            'tempat_lahir',
            'tanggal_lahir',
            'kelamin',
            'kelas',
            'rombel',
            'jurusan',
            // 'status',
            // 'image',
            'alamat',
            'provinsi',
            'kab_kota',
            'kel_des',
            'kodepos',
            'nama_ayah',
            'nama_ibu',
            'anak_ke',
            'jml_saudara',
            // 'created_at',
            // 'updated_at',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getStyle('A1:S1')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                    // 'borders' => [
                    //     'outline' => [
                    //         'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    //         'color' => ['argb' => 'FFFF0000'],
                    //     ],
                    // ]
                ]);
            }
        ];
    }

     // INI JIKA PAKE FromArray
    // public function array(): array
    // {
    //     return [
    //         ['Harry', 'harry@gmail.com']
    //     ];
    // }
}
