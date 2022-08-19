<?php

namespace App\Http\Controllers;

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
        }

        if ($request->market) {
            $thisMarket = $request->market;
            if ($request->market != 'smaregi') {
                $rows = [];
            }
        } else {
            $thisMarket = '';
        }

        if ($request->color) {
            $thisColor = $request->color;
            $rows = array_filter($rows, function ($v) use ($thisColor) {
                return $v['color'] == $thisColor;
            });
        } else {
            $thisColor = '';
        }

        if ($request->product) {
            $thisProduct = $request->product;
            $rows = array_filter($rows, function ($v) use ($thisProduct) {
                return $v['productCode'] == $thisProduct;
            });
        } else {
            $thisProduct = '';
        }
        return view('home', ['rows' => $rows, 'product' => $thisProduct, 'color' => $thisColor, 'market' => $thisMarket]);
    }
}
