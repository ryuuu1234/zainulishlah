<?php

namespace App\Exports;

use App\User;
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

class UsersExport implements 
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
        // return User::query()->with('role')->has('role');
        return User::query()->whereIn('id', $this->id);
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            // $user->role->name,
            $user->created_at
        ];
    }

    public function headings(): array
    {
        return [
            '#',
            'name',
            // 'role',
            'created_at'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getStyle('A1:D1')->applyFromArray([
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
