<?php

namespace App\Exports;

use App\HomeContactUs;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ContactUsHomeExport implements FromCollection, WithHeadings, WithMapping
{
    private $filter;

    public function headings(): array
    {
        return [
            'Date',
            'Name',
            'Email',
            'Phone',
            'Message',
            'IP',
        ];
    }
    
    public function __construct($filter) 
    {
        $this->filter = $filter;
    }
    
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        if(!empty($this->filter)) {
            return HomeContactUs::where('name','like','%'.$this->filter.'%')->orwhere('phone','like','%'.$this->filter.'%')->orderBy('id','desc')->get();
        } else {
            return HomeContactUs::orderBy('id','desc')->get();
        }
    }

    public function map($data): array
    {
        return [
            date('Y-m-d h:i',strtotime($data->created_at)),
            $data->name,
            $data->email,
            ' '.$data->phone,
            $data->message,
            $data->ip_client,
        ];
    }

}
