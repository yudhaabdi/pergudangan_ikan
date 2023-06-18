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
        <span style="font-size: 12pt;"><b>LAPORAN PENDAPATAN</b></span>
    </center>
    {{-- {{dd($start_date, $end_date, $start)}} --}}
    @if ($start == null)
        <span style="text-align: left; font-size: 10px">Tanggal : -</span>
    @else
    <span style="text-align: left; font-size: 10px">Tanggal : {{\Carbon\Carbon::parse($start_date)->format('d - m - Y')}} sampai {{\Carbon\Carbon::parse($end_date)->format('d - m - Y')}}</span>
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
                <th>No</th>
                <th>Nama Pembeli</th>
                <th>Tanggal</th>
                <th>Nama Barang</th>
                <th>QTY</th>
                <th>Harga</th>
                <th>Total Harga</th>
                <th>Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_pendapatan = 0;
            @endphp
            @foreach ($data as $key => $item)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$item->daftarPiutang->nama_pembeli}}</td>
                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d - m - Y')}}</td>
                    @if (count($item->transaksi->transaksi_detail) > 0)
                        @foreach ($item->transaksi->transaksi_detail as $key => $transaksi)
                            <td>&#8226 {{$transaksi->dataBarang->nama_barang}} <br></td>
                            <td>&#8226 {{$transaksi->jumlah_barang}} <br></td>
                            <td>&#8226 {{$transaksi->harga_barang}} <br></td>
                        @endforeach
                    @else
                        <td>{{$item->keterangan}}</td>
                        <td>-</td>
                        <td>{{format_number($item->transaksi->total_transaksi)}}</td>
                    @endif
                    <td>{{number_format($item->transaksi->total_transaksi)}}</td>
                    <td>{{number_format($item->jumlah_uang)}}</td>
                    @php
                        $total_pendapatan += $item->jumlah_uang;
                    @endphp
                </tr>
            @endforeach
            <tr>
                <td colspan="7" style="text-align: center;"><b>Total Pendapatan</b></td>
                <td>{{number_format($total_pendapatan)}}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
