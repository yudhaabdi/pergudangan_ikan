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
        <span style="font-size: 12pt;"><b>LAPORAN PENGELUARAN</b></span>
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
                <th>NO</th>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Nama Barang</th>
                <th>QTY</th>
                <th>Harga</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_pengeluaran = 0;
            @endphp
            @foreach ($data as $key => $item)
                <tr>
                    <td>{{$key+1}}</td>
                    @if ($item->dataBarang != null && $item->transaksi->penyusutan == 1)
                        <td>Penyusutan</td>
                    @elseif ($item->id_piutang == null)
                        <td>Pengeluaran Pabrik</td>
                    @else
                        <td>{{$item->daftarPiutang->nama_pembeli}}</td>
                    @endif
                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d - m - Y')}}</td>
                    @if ($item->id_data_barang == null)
                        <td>{{$item->keterangan}}</td>
                    @else
                        <td>{{$item->dataBarang->nama_barang}}</td>
                    @endif
                    @if ($item->id_data_barang != null && $item->transaksi->penyusutan == null)
                        <td>{{number_format($item->transaksi->qty)}}</td>
                        <td>{{number_format($item->dataBarang->harga_barang)}}</td>
                    @endif
                    @if ($item->transaksi->penyusutan == 1 && $item->id_data_barang != null)
                        @php
                            $qty = $item->jumlah_uang / $item->databarang->harga_barang;
                        @endphp
                        <td>{{number_format($qty)}}</td>
                        <td>{{number_format($item->dataBarang->harga_barang)}}</td>
                    @endif
                    @if ($item->lain_lain != null && $item->id_data_barang == null)
                        <td></td><td></td>
                    @endif
                    <td>{{number_format($item->transaksi->total_transaksi)}}</td>
                </tr>
                    @php
                        $total_pengeluaran += $item->transaksi->total_transaksi;
                    @endphp
                @endforeach
                <tr>
                    <td colspan="6" style="text-align: center;"><b>Total Pengeluaran</b></td>
                    <td>{{number_format($total_pengeluaran)}}</td>
                </tr>
        </tbody>
    </table>
</body>
</html>
