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

?>
@section('content')
    <script>
        function filterCodeColor() {
            const productCode = document.getElementById('input-product-code').value;
            const color = document.getElementById('input-color').value;

            window.location.href = `/home?${color != '' ? 'color=' + color  : ''}${productCode != '' && color != '' ? '&' : ''}${productCode != '' ? 'product=' + productCode : ''}`
        }

        function filterByMarket() {
            const market = document.getElementById('input-market').value;

            window.location.href = `/home?${market != '' ? 'market=' +  market  : ''}`;
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

    </script>

            <div class="container-fuild pt-4 px-4">
                <div class="bg-light rounded p-4">
                    <div class="row d-flex align-items-center justify-content-start mb-4">
                        <div class="col-md-6 d-flex justify-content-center flex-column">
                            <div class="text-left">
                                <h6 class="text-left">Search by market</h6>
                                <label>Market</label>
                                <div class="d-flex">
                                    <select value="{{ $market }}" id="input-market" class="form-control">
                                        <option value="">All</option>
                                        <option {{ $market == 'smaregi' ? 'selected' : '' }} value="smaregi">smaregi</option>
                                        <option {{ $market == 'sereposu' ? 'selected' : '' }} value="sereposu">sereposu</option>
                                        <option {{ $market == 'tokko' ? 'selected' : '' }} value="tokko">tokko</option>
                                        <option {{ $market == 'amazon' ? 'selected' : '' }} value="amazon">amazon</option>
                                        <option {{ $market == 'rakuten' ? 'selected' : '' }} value="rakuten">rakuten</option>
                                    </select>
                                    <button onclick="filterByMarket()" class="btn btn-secondary" style="margin-left: 8px;"><i class="fa fa-search"></i></button>
                                </div>

                            </div>
                            <div>
                            <h6 class="mt-3 text-left">Search by code</h6>
                            <div class="d-flex align-items-end">
                                <div>
                                    <label>Product Code</label>
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
                        <div class="col-md-6 d-flex justify-content-end align-items-end text-right">
                            <form style="position: absolute; opacity: 0;" action="{{ route('data-import') }}" id="import-form" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="file" name="file" id="file" onchange="document.getElementById('import-form').submit();" accept=".csv" class="form-control">
                            </form>
                            <button class="btn" onclick="document.getElementById('id').click();">Import inventory (CSV)</button>

                        </div>

                    </div>
                </div>
            </div>

            <!-- Recent Sales Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">Search Results</h6>
                        <a class="btn btn-secondary" href="#" onclick="exportCSV({{ json_encode($rows) }})">Download data</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead>
                                <tr class="text-dark">
                                    <th scope="col">id_product</th>
                                    <th scope="col">id_color</th>
                                    <th scope="col">product Code</th>
                                    <th scope="col">color</th>
                                    <th scope="col">productNameENG</th>
                                    <th scope="col">productNameJP</th>
                                    <th scope="col">price</th>
                                    <th scope="col">cost</th>
                                    <th scope="col">blfCode</th>
                                    <th scope="col">smaregi</th>
                                    <th scope="col">serepouse</th>
                                    <th scope="col">sokko</th>
                                    <th scope="col">amazon</th>
                                    <th scope="col">rakuten</th>
                                    <th scope="col">id_category</th>
                                    <th scope="col">category</th>
                                    <th scope="col">id_condition</th>
                                    <th scope="col">condition</th>
                                    <th scope="col">id_groupColor</th>
                                    <th scope="col">colorGroup</th>
                                    <th scope="col">id_productState</th>
                                    <th scope="col">status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rows as $row)
                                    <tr>
                                        <td>{{ $row['productId'] }}</td>
                                        <td>{{ getColorId($row['color']) }}</td>
                                        <td>{{ $row['productCode'] }}</td>
                                        <td>{{ $row['color'] }}</td>
                                        <td>{{ $row['productName'] }}</td>
                                        <td>{{ $row['productName'] }}</td>
                                        <td>{{ $row['price'] }}</td>
                                        <td>{{ $row['cost'] }}</td>
                                        <td>{{ $row['productCode'].$row['color'] }}</td>
                                        <td>{{ $row['productCode'] }}</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>{{ $row['price'] }}</td>
                                        <td>-</td>
                                        <td>1</td>
                                        <td>normal</td>
                                        <td>1</td>
                                        <td>normal</td>
                                        <td>0</td>
                                        <td>通常販売</td>
                                    </tr>
                                @endforeach


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

@endsection
