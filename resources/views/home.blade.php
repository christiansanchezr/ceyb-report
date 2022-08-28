@extends('layouts.dashboard')
<?PHP
$colors = \App\Models\Color::all();

function getColorId($color) {
    $thisColor = \App\Models\Color::all()->first(function ($c) use ($color) {
        return $c['color'] == $color;
    });
    if ($thisColor) {
        return $thisColor['id_color'];
    }
    return '-';
}

$markets = \App\Models\Market::all();

?>
@section('content')
    <script>

        function filterCodeColor() {
            const productCode = document.getElementById('input-product-code').value;
            const color = document.getElementById('input-color').value;

            window.location.href = `/home?market={{$market}}&${color != '' ? 'color=' + color  : ''}${productCode != '' && color != '' ? '&' : ''}${productCode != '' ? 'product=' + productCode : ''}`
        }

        function filterByMarket() {
            const market = document.getElementById('input-market').value;

            if (market != '') {
                window.location.href = `/home?${market != '' ? 'market=' +  market  : ''}`;
            }

        }
    </script>
    <script>
        function exportCSV(data) {
            const rows = JSON.stringify(data);
            fetch('/api/data/export', {
                method: 'POST',
                body: JSON.stringify({ rows: rows }),
                headers: {
                    'Content-Type': 'application/json'
                }
            }).then(res => res.blob())
                .catch(err => console.log(err))
                .then(blob => {
                    var url = window.URL.createObjectURL(blob);
                    var a = document.createElement('a');
                    a.href = url;
                    a.download = "data-export_{{\Carbon\Carbon::now()->toDateString()}}.csv";
                    document.body.appendChild(a); // we need to append the element to the dom -> otherwise it will not work in firefox
                    a.click();
                    a.remove();
                })
        }

        function fileChange(event) {
            var file = event.target.files[0];
            console.log(file);
            var reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('csv-button').textContent = 'Change File (CSV)'
                document.querySelector('#csv-submit').removeAttribute('disabled');
            };
            reader.readAsText(file);
        }

    </script>

            <div class="container-fuild pt-4 px-4">
                <div class="bg-light rounded p-4">
                    <div class="row d-flex align-items-center justify-content-start mb-4">
                        <div class="col-md-6 d-flex justify-content-center flex-column">
                            <div class="text-left">
                                <h6 class="text-left">Market</h6>
                                <div class="d-flex">
                                    <select value="{{ $market }}" id="input-market" class="form-control">
                                        <option value="">Select a market</option>
                                        @foreach($markets as $m)
                                            <option {{ $market == $m->name ? 'selected' : '' }} value="{{ $m->name }}">{{ $m->name }}</option>
                                        @endforeach
                                    </select>
                                    <button onclick="filterByMarket()" class="btn btn-secondary" style="margin-left: 8px;"><i class="fa fa-search"></i></button>
                                </div>

                            </div>
                            <div>
                            <h6 class="mt-3 text-left">Search Product</h6>
                            <div class="d-flex align-items-end">
                                <div>
                                    <label>Product No</label>
                                    <input id="input-product-code" value="{{ $product }}" type="text" class="form-control"/>
                                </div>
                                <div style="margin-left: 8px;">
                                    <label>Color</label>
                                    <select id="input-color" class="form-control" style="width: 210px">
                                        <option value="">All</option>
                                        @foreach($colors as $c)
                                            <option {{ $color == $c->color ? 'selected' : '' }} value="{{ $c->color }}">{{ $c->color }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button onclick="filterCodeColor()" class="btn btn-secondary" style="margin-left: 8px;"><i class="fa fa-search"></i></button>
                            </div>

                            </div>

                        </div>
                        <div class="col-md-6 d-flex justify-content-end align-items-end flex-column text-right">
                            @if(auth()->user()->hasRole('Typist') || auth()->user()->hasRole('Admin'))
                                <h6 class="text-left">Upload Data</h6>
                                <div>
                                    <form action="{{ route('data-import') }}" id="import-form" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div>
                                            <input required type="file" name="file" id="file" onchange="fileChange(event)" accept=".csv" class="form-control" style="position: absolute; opacity: 0; width: 0;" >
                                            <button class="btn btn-secondary" onclick="document.getElementById('file').click();" id="csv-button">Select inventory File (CSV)</button>
                                        </div>
                                        <div class="mt-2">
                                            <label>Select a Market</label>
                                            <select value="{{ $market }}" required name="market" id="market" class="form-control" style="width: 188px;">
                                                <option value="">Select a market</option>
                                                @foreach($markets as $m)
                                                    <option value="{{ $m->id }}">{{ $m->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button class="btn btn-primary d-block mt-2" disabled="true" id="csv-submit" type="submit">Import Data</button>
                                    </form>
                                </div>
                            @endif
                        </div>


                    </div>
                </div>
            </div>

            <!-- Recent Sales Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light text-center rounded p-4">
                   <!--<div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">Search Results</h6>
                        <a class="btn btn-secondary" href="#" onclick="exportCSV({{ json_encode($rows) }})">Download data</a>
                    </div>-->
                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead id="table-header">
                                <tr class="text-dark" id="table-header-values">
                                    <th scope="col">Color</th>
                                    <th scope="col">グループコード</th>
                                    <th scope="col">Product No</th>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">BLF（コード）</th>
                                    <th scope="col">BLF（コード）旧</th>
                                    <th scope="col">合算コード</th>
                                    <th scope="col">(並替え用) 商品番号</th>
                                    <th scope="col">(並替え用)</th>
                                    <th scope="col">商品コード</th>
                                    <th scope="col">セレポス商品コード </th>
                                    <th scope="col">商品コード（スマレジ）</th>
                                    <th scope="col">商品名</th>
                                    <th scope="col">販売価格</th>
                                    <th scope="col">原価</th>
                                    <th scope="col">商品コード（特攻）</th>
                                    <th scope="col">商品名（特攻）</th>
                                    <th scope="col">Amazon(子)ASIN</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rows as $row)
                                    <tr>
                                        <td>{{ $row['id_color'] }}</td>
                                        <td>{{ $row['group_code'] }}</td>
                                        <td>{{ $row['product_no'] }}</td>
                                        <td>{{ $row['product_name'] }}</td>
                                        <td>{{ $row['blf_code'] }}</td>
                                        <td>{{ $row['blf_code_old'] }}</td>
                                        <td>{{ $row['combined_code'] }}</td>
                                        <td>{{ $row['item_number'] }}</td>
                                        <td>{{ $row['for_sorting'] }}</td>
                                        <td>{{ $row['product_code'] }}</td>
                                        <td>{{ $row['serepos_product_code'] }}</td>
                                        <td>{{ $row['smaregi_product_code'] }}</td>
                                        <td>{{ $row['product_name_jp'] }}</td>
                                        <td>{{ $row['selling_price'] }}</td>
                                        <td>{{ $row['cost_price'] }}</td>
                                        <td>{{ $row['product_code_special'] }}</td>
                                        <td>{{ $row['product_name_special'] }}</td>
                                        <td></td>
                                        @foreach($row['values'] as $v)
                                            <td>{{ $v }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
<script>
    const years = JSON.parse('{!! $years !!}');
    let ths = '';
    let secondThs = '';

    years.forEach(y => {
        let months = Array.from(Array(12).keys());
        ths += `<th colspan="${months.length}">${y}</th>`
        months.forEach((k, i) => {
            secondThs += `<th scope="col">${i+1}月</th>`
        });
    })

    const currentHTML = document.getElementById('table-header').innerHTML;
    document.getElementById('table-header').innerHTML = `<tr>
            <th colspan="18"></th>
            ${ths}
        </tr>${currentHTML}`
    document.getElementById('table-header-values').innerHTML += secondThs;

    /*
    let ths = '';
    let secondThs = '';
    firstRowData.forEach((r) => {
        let months = r['data'];
        ths += `<th colspan="${months.length}">${r['year']}年</th>`
        months.forEach((k, i) => {
           secondThs += `<th scope="col">${i+1}月</th>`
        });
    })

    const currentHTML = document.getElementById('table-header').innerHTML;
    document.getElementById('table-header').innerHTML = `<tr>
            <th colspan="18"></th>
            ${ths}
        </tr>${currentHTML}`
    document.getElementById('table-header-values').innerHTML += secondThs;

    */

</script>
@endsection
