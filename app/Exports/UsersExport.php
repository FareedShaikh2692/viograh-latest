<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use ReflectionClass;

class UsersExport implements FromCollection, WithEvents, ShouldAutoSize
{  
    public function __construct(array $data) {
        $this->data = $data;
    }

    public function collection() {
        $userExport = array();
        
        array_push($userExport,array('First Name','Last Name','Gender','Blood Group','Email','Contact Number','Birth Date', 'Profession','Places Lived',
        'Address','Place Of Birth','Favourite Movie','Favourite Song','Favourite Book','Favourite Eating Joints','Hobbies','Food','Role Model','Car',
        'Brand','Tv Shows','Actors','Sports Person','Politician','Diet','Zodiac Sign','Created Date'));

        array_push($userExport,array(''));
        foreach($this->data as $val){
            $tempExport = array();
            
            $tempExport[0] = $val['first_name'];
            $tempExport[1] = $val['last_name'];
            $tempExport[2] = $val['gender'];
            $tempExport[3] = $val['blood_group'];
            $tempExport[4] =$val['email'];
            $tempExport[5] = $val['phone_number'];
            $tempExport[6] = $val['birth_date'];
            $tempExport[7] = $val['profession'];
            $tempExport[8] = $val['places_lived'];
            $tempExport[9] = $val['address'];
            $tempExport[10] = $val['place_of_birth'];
            $tempExport[11] = $val['favourite_movie'];
            $tempExport[12] = $val['favourite_song'];
            $tempExport[13] = $val['favourite_book'];
            $tempExport[14] = $val['favourite_eating_joints'];
            $tempExport[15] = $val['hobbies'];
            $tempExport[16] = $val['food'];
            $tempExport[17] = $val['role_model'];
            $tempExport[18] = $val['car'];
            $tempExport[19] = $val['brand'];
            $tempExport[20] = $val['tv_shows'];
            $tempExport[21] = $val['actors'];
            $tempExport[22] = $val['sports_person'];
            $tempExport[23] = $val['politician'];
            $tempExport[24] = $val['diet'];
            $tempExport[25] = $val['zodiac_sign'];
            $tempExport[26] = $val['created_at'];
            
            array_push($userExport,$tempExport);
        }        
        return collect($userExport);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                //SET AUTO SIZE OF CELLS 
                $event->sheet->getColumnDimension('A')->setAutoSize(true);
                $event->sheet->getColumnDimension('B')->setAutoSize(true);
                $event->sheet->getColumnDimension('C')->setAutoSize(true);
                $event->sheet->getColumnDimension('D')->setAutoSize(true);
                $event->sheet->getColumnDimension('E')->setAutoSize(true);
                $event->sheet->getColumnDimension('F')->setAutoSize(true);
                $event->sheet->getColumnDimension('G')->setAutoSize(true);
                $event->sheet->getColumnDimension('H')->setAutoSize(true);
                $event->sheet->getColumnDimension('I')->setAutoSize(true);
                $event->sheet->getColumnDimension('J')->setAutoSize(true);
                $event->sheet->getColumnDimension('K')->setAutoSize(true);
                $event->sheet->getColumnDimension('L')->setAutoSize(true);
                $event->sheet->getColumnDimension('M')->setAutoSize(true);
                $event->sheet->getColumnDimension('N')->setAutoSize(true);
                $event->sheet->getColumnDimension('O')->setAutoSize(true);
                $event->sheet->getColumnDimension('P')->setAutoSize(true);
                $event->sheet->getColumnDimension('Q')->setAutoSize(true);
                $event->sheet->getColumnDimension('R')->setAutoSize(true);
                $event->sheet->getColumnDimension('S')->setAutoSize(true);
                $event->sheet->getColumnDimension('T')->setAutoSize(true);
                $event->sheet->getColumnDimension('U')->setAutoSize(true);
                $event->sheet->getColumnDimension('V')->setAutoSize(true);
                $event->sheet->getColumnDimension('W')->setAutoSize(true);
                $event->sheet->getColumnDimension('X')->setAutoSize(true);
                $event->sheet->getColumnDimension('Y')->setAutoSize(true);
                $event->sheet->getColumnDimension('Z')->setAutoSize(true);                
                $event->sheet->getColumnDimension('AA')->setAutoSize(true);                

               //BOLD HEADINGS
                $event->sheet->getStyle('A1:AA1')->applyFromArray([
                    'font' => [
                        'bold' => true,                                                
                    ]
                ]);
                //TEXT WRAP(NOT WORKING)
                //$count = (count($this->data) + 1);                
                //$event->sheet->getStyle('I1:I'.$count)->getAlignment()->setWrapText(true); 
            }
        ];
    }
}
   
    

