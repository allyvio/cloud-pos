<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Laporan</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="{{ asset('/dist/css/adminlte.min.css')}}" rel="stylesheet">
</head>
<body>

    <div class="container-fluid mt-5">
        <h4 class="text-center">Laporan</h4>
        <p>{{date('d M Y', strtotime($tgl_awal))}} - {{date('d M Y', strtotime($tgl_akhir))}}</p>
        <div class="justify-content-center">
            <table class="table table-bordered mt-5" >
                <thead>
                    <tr>
                        <th style="width:6px;">No.</th>
                        <th style="width:23px;">Toko</th>
                        <th style="width:30px;">Nama Produk</th>
                        <th style="width:7px;">Jumlah</th>
                        <th style="width:8px;">Total</th>
                        <th style="width:12px;">Tanggal</th>
                    </tr>
                </thead>
                <tbody>

                    {{-- @dd($data) --}}

                    @foreach ($data as $index => $item)
                    <tr>
                        <td class="align-center">{{ $index+1}}</td>
                        <td>{{ \DB::table('shops')->where('id', $item->shop_id)->value('name') }}</td>
                        <td>{{ \DB::table('products')->where('id', $item->product_id)->value('product_name') .' - '. \DB::table('products')->where('id', $item->product_id)->value('warna') }}</td>
                        <td class="align-center">{{ $item->qty }}</td>
                        <td>{{ number_format($item->total) }}</td>
                        <td>{{ $item->tanggal }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <p>Total Uang : {{number_format($total)}}</p>

        </div>


    </div>


</body>
</html>
