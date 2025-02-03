<?php

namespace App\Exports;

use App\TheNoveContactUs;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ContactUsExport implements FromCollection, WithHeadings, WithMapping
{
    private $filter;

    public function headings(): array
    {
        return [
            'Date',
            'Project Name',
            'Name',
            'Email',
            'Phone',
            'UTM',
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
            return TheNoveContactUs::where('name','like','%'.$this->filter.'%')->orwhere('phone','like','%'.$this->filter.'%')->orderBy('id','desc')->get();
        } else {
            return TheNoveContactUs::orderBy('id','desc')->get();
        }
    }

    public function map($data): array
    {
        return [
            date('Y-m-d h:i',strtotime($data->created_at)),
            $data->project_name,
            $data->name,
            $data->email,
            ' '.$data->phone,
            $data->cookie,
            $data->ip_client,
        ];
    }

}
