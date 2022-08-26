<?php

namespace App\Http\Controllers;

use App\Models\Market;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        /*
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

        $rows = [];


        if ($statusCode == 200) {
            $data = $res['result'];
            $rows = $data;
        }*/

        $market = Market::where('name', '=', $request->market)->first();

        if (!$market) {
            $market = Market::all()->first();
            $request->market = $market->name;
        }
            $rows = $market->dproducts;
            if ($request->color) {
                $thisColor = $request->color;
                $rows = $rows->where('id_color', $thisColor);
                /* array_filter($rows, function ($v) use ($thisColor) {
                    return $v['id_color'] == $thisColor;
                });*/
            } else {
                $thisColor = '';
            }

            if ($request->product) {
                $thisProduct = $request->product;
                $rows = $rows->where('product_no', $thisProduct);
                /*
                 * array_filter($rows, function ($v) use ($thisProduct) {
                    return $v['product_code'] == $thisProduct;
                });
                 */

            } else {
                $thisProduct = '';
            }

            /*

            $data = json_encode(json_decode($rows->first()->data));

            $keys = json_decode($data, true);

            $values = [];

            foreach ($keys as $v) {
                $values[] = $v['data'];
            }

            */

            $rows->each(function ($r) {
                /*
                $data = json_encode(json_decode($r->data));

                $keys = json_decode($data, true);

                $values = [];

                foreach ($keys as $v) {
                    array_push($values, ...$v['data']);
                }*/

                $r['values'] = json_decode($r['values']);
            });

            //error_log(json_encode($keys));


            if ($rows->first()) {
                $years = $rows->first()->years;
            } else {
                $years = json_encode([]);
            }

            return view('home', ['rows' => $rows, 'product' => $thisProduct, 'color' => $thisColor, 'market' => $request->market, 'years' => $years]);

    }
}
