<?php

namespace App\Http\Imports;

use App\Models\Dproduct;
use Maatwebsite\Excel\Concerns\ToModel;

class DproductsImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return Dproduct
     */
    public function model(array $row): Dproduct
    {
        return new Dproduct([
            'id_color' => $row[0],
            'group_code' => $row[1],
            'product_no' => $row[2],
            'product_name' => $row[3],
            'blf_code' => $row[4],
            'blf_code_old' => $row[5],
            'combined_code' => $row[6],
            'item_number' => $row[7],
            'for_sorting' => $row[8],
            'product_code' => $row[9],
            'serepos_product_code' => $row[10],
            'smaregi_product_code' => $row[11],
            'product_name_jp' => $row[12],
            'selling_price' => $row[13],
            'cost_price' => $row[14],
            'product_code_special' => $row[15],
            'product_name_special' => $row[16],
        ]);
    }
}
