<?php
namespace App\Http\Controllers;

use App\Models\Dproduct;
use App\Models\Market;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Excel;

class DataController extends Controller {

    public function get(Request $request)
    {

        $res = Http::asForm()->withHeaders(
            [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'X-contract-id' => 'skh095g7',
                'X-access-token' => '6c25420ffbb731a5b9a7a3da746d44f5'
            ]
        )->post('https://webapi.smaregi.jp/access/', [
            'proc_name' => 'product_ref',
            'params' => json_encode([
                'fields' => [ 'productId', 'categoryId', 'productCode', 'productName', 'price', 'cost', 'color' ],

                'table_name' => 'Product'
            ])
        ]);

        $statusCode = $res->status();

        return $res;

        if ($statusCode == 200) {
            $data = $res['result'];
            return Response()->json(['success' => true, 'status' => 200, 'data' => $data]);
        } else {
            return Response()->json(['success' => false, 'status' => $statusCode, 'data' => $res]);

        }
    }

    function getColorId($color) {
        $thisColor = \App\Models\Color::all()->first(function ($c) use ($color) {
            return $c['color'] == $color;
        });
        if ($thisColor) {
            return $thisColor['id_color'];
        }
        return '-';
    }

    public function exportCsv(Request $request)
    {

        $fileName = 'data.csv';

        $validation_rules = [
            'rows' => 'string|required'
        ];

        $this->validate($request, $validation_rules);

        $rows = json_decode($request->rows, true);


        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('id_product',
            'id_color',
            'productNo',
            'color',
            'productNameENG',
            'productNameJP',
            'price',
            'cost',
            'blfCode',
            'smaregi',
            'serepouse',
            'sokko',
            'amazon',
            'rakuten',
            'id_category',
            'category',
            'id_condition',
            'condition',
            'id_groupColor',
            'colorGroup',
            'id_productState',
            'status');


            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($rows as $r) {

                $row['id_product']  = $r['productId'];
                $row['id_color'] =$this->getColorId($r['color']);
                $row['productNo']  = $r['productCode'];
                $row['color']    = $r['color'];
                $row['productNameENG']  = $r['productName'];
                $row['productNameJP']  = $r['productName'];
                $row['price']  = $r['price'];
                $row['cost']  = $r['cost'];
                $row['blfCode']  = $r['productCode'].$r['color'];
                $row['smaregi']  = $r['productCode'];
                $row['serepouse']  = '-';
                $row['sokko']  = '-';
                $row['amazon']  = '-';
                $row['rakuten']  = '-';
                $row['id_category']  = '-';
                $row['category']  = '-';
                $row['id_condition']  = '1';
                $row['condition']  = 'normal';
                $row['id_groupColor']  = '1';
                $row['colorGroup']  = 'normal';
                $row['id_productState']  = '0';
                $row['status']  = '通常販売';


                fputcsv($file, array(
                    $row['id_product'],
                    $row['id_color'],
                    $row['productNo'],
                    $row['color'],
                    $row['productNameENG'],
                    $row['productNameJP'],
                    $row['price'],
                    $row['cost'],
                    $row['blfCode'],
                    $row['smaregi'],
                    $row['serepouse'],
                    $row['sokko'],
                    $row['amazon'],
                    $row['rakuten'],
                    $row['id_category'],
                    $row['category'],
                    $row['id_condition'],
                    $row['condition'],
                    $row['id_groupColor'],
                    $row['colorGroup'],
                    $row['id_productState'],
                    $row['status'],
                ));
            }

            fclose($file);


        return \response()->file($file, $headers);

    }

    private $rows = [];

    public function import(Request $request)
    {

        $validationRules = [
            'file' => 'required|file',
            'market' => 'required|exists:markets,id'
        ];

        $this->validate($request, $validationRules);
/*
        $response = Excel::import(new \App\Http\Imports\DproductsImport(), $request->file('file')->store('temp'));

        return $response;*/

        $path = $request->file('file')->getRealPath();
        $records = array_map('str_getcsv', file($path));
        $records = array_slice($records, 0, 1000);

        if (! count($records) > 0) {
            return 'Error...';
        }

        $fields = array_map('strtolower', $records[0]);

        array_shift($records);

        $years = collect($records[0])->filter(function ($year) {
            return $year != "";
        })->values()->all();
        $records = array_slice($records, 7);

        Dproduct::where('id_market', $request->market)->delete();
        $yearsCount = count($years);
        $currentYearCount = 1;
        foreach ($records as $record) {

            $values = array_slice($record, 18);

            /*
            $yearsData = [];

            $sortValues = [];

            foreach ($values as $key => $v) {
                if ($key + 1 <= $currentYearCount * 12) {
                    $sortValues[$currentYearCount - 1][] = $v;
                } else {
                    if ($currentYearCount < $yearsCount) {
                        $currentYearCount++;
                    }
                }
            }

            foreach ($years as $i => $y) {
                //$yearsData[] = [ $y => $sortValues[$i] ];
            }
            */

            $dproduct = Dproduct::create([
                'id_market' => $request->market,
                'id_color' => $record[0],
                'group_code' => $record[1],
                'product_no' => $record[2],
                'product_name' => $record[3],
                'blf_code' => $record[4],
                'blf_code_old' => $record[5],
                'combined_code' => $record[6],
                'item_number' => $record[7],
                'for_sorting' => $record[8],
                'product_code' => $record[9],
                'serepos_product_code' => $record[10],
                'smaregi_product_code' => $record[11],
                'product_name_jp' => $record[12],
                'selling_price' => $record[13],
                'cost_price' => $record[14],
                'product_code_special' => $record[15],
                'product_name_special' => $record[16],
                'years' => json_encode($years),
                'values' => json_encode($values),

            ]);
        }


        return redirect('home');

        foreach ($records as $record) {
            if (count($fields) != count($record)) {
                //return 'csv_upload_invalid_data';
            }

            // Decode unwanted html entities
            $record =  array_map("html_entity_decode", $record);

            // Set the field name as key
            $record = array_combine($fields, $record);

            // Get the clean data
            $this->rows[] = $this->clear_encoding_str($record);
        }


    }

    private function clear_encoding_str($value)
    {
        if (is_array($value)) {
            $clean = [];
            foreach ($value as $key => $val) {
                $clean[$key] = mb_convert_encoding($val, 'UTF-8', 'UTF-8');
            }
            return $clean;
        }
        return mb_convert_encoding($value, 'UTF-8', 'UTF-8');
    }
}



