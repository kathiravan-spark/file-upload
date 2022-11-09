<?php

namespace App\Imports;

use App\Models\UserDetail;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportUser implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model, WithHeadingRow
    */
    public function model(array $row)
    {
        return new UserDetail([
            'user_id'=> $row['0'],
            'name' =>  $row['1'],
            'email' => $row['2'],
        ]);
    }
}
