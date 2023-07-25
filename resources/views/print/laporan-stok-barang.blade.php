<!DOCTYPE html>
<html lang="en">
<head>
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <style>
        hr { 
            display: block;
            margin-top: 0.5em;
            margin-bottom: 0.5em;
            margin-left: auto;
            margin-right: auto;
            border-style: inset;
            border-width: 1px;
        } 
    </style>
    <center border-collapse: collapse;' border = '0'>
        <span style="font-size: 12pt;"><b>CV. BERKAH RESTU IBU</b></span> <br>
        <span style="font-size: 12pt;"><b>LAPORAN STOK BARANG</b></span>
    </center>
    {{-- {{dd($start_date, $end_date, $start)}} --}}
    @if ($start == null)
        <span style="text-align: left; font-size: 15px">Tanggal : -</span>
    @else
    <span style="text-align: left; font-size: 15px">Tanggal : {{\Carbon\Carbon::parse($start_date)->format('d - m - Y')}} sampai {{\Carbon\Carbon::parse($end_date)->format('d - m - Y')}}</span>
    @endif
    <hr>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        tr {
            font-size: 10pt;
        }
    </style>
    <table class="table table-bordered" style="text-align: left">
        <thead>
            <tr>
                <th>NO</th>
                <th>Tanggal</th>
                <th>Nama</th>
                <th>Nama Barang</th>
                <th>Kode</th>
                <th>QTY</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
                <th>keterangan</th>
              </tr>
        </thead>
        <tbody>
            @foreach ($data['transaksi'] as $key => $transaksi)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{ \Carbon\Carbon::parse($transaksi->created_at)->format('d - m - Y')}}</td>
                    @if ($transaksi->nama_pemilik != null)
                        <td>{{$transaksi->nama_pemilik}}</td>
                    @else
                        <td>{{$transaksi->nama_pembeli}}</td>
                    @endif
                    <td>{{$transaksi->nama_barang}}</td>
                    <td>{{$transaksi->kode}}</td>
                    @if ($transaksi->nama_pemilik != null)
                        @php
                            $stok = ($transaksi->total_transaksi + $transaksi->kekurangan) / $transaksi->harga_beli;
                        @endphp
                        <td>{{$stok}}</td>
                    @else
                        <td>{{$transaksi->jumlah_barang}}</td>
                    @endif
                    @if ($transaksi->nama_pemilik != null)
                        <td>{{$transaksi->harga_beli}}</td>
                        <td>-</td>
                    @else
                        <td>{{$transaksi->harga_beli}}</td>
                        <td>{{$transaksi->harga_jual}}</td>
                    @endif
                    @if ($transaksi->nama_pemilik != null)
                        <td style="color:blue">Barang Masuk</td>
                    @else
                        <td style="color:red">Barang Keluar</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
    <h6>Total Stok Saat Ini</h6>
    <table style="width: 50%" class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Size</th>
            <th>Kode</th>
            <th>Stok</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data['data_barang'] as $key => $item)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$item->nama_barang}}</td>
                <td>{{$item->size}}</td>
                <td>{{$item->kode}}</td>
                <td>{{$item->stok_barang}}</td>
            </tr>
        @endforeach
    </tbody>
    </table>
</body>
</html>
