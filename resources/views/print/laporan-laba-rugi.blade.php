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
        <span style="font-size: 12pt;"><b>LAPORAN KEUNTUNGAN BERSIH</b></span>
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
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Nama Barang</th>
                <th>QTY</th>
                <th>Harga</th>
                <th>Total Harga</th>
                <th>Total Faktur</th>
                <th>Pembayaran</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php
                 $pendapatan = 0;
                 $pengeluaran = 0;
                 $laba_bersih = 0;
            @endphp
            @foreach ($data as $key => $item)
                <tr>
                    <td>{{$key+1}}</td>
                    @if ($item->daftarPiutang == null)
                        <td>Pabrik</td>
                    @else
                        <td>{{$item->daftarPiutang->nama_pembeli}}</td>
                    @endif
                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d - m - Y')}}</td>
                    @if ($item->pembayaran->lain_lain == 2 || $item->pembayaran->lain_lain == 1)
                        <td colspan="5" style="text-align: center;">{{$item->pembayaran->keterangan}}</td>
                    @else
                        @if (count($item->transaksi_detail) > 0)
                            <td>
                                @foreach ($item->transaksi_detail as $detail)
                                   &#8226 {{$detail->dataBarang->nama_barang}} <br> 
                                @endforeach
                            </td>
                            <td>
                                @foreach ($item->transaksi_detail as $detail)
                                   &#8226 {{$detail->jumlah_barang}} <br> 
                                @endforeach
                            </td>
                            <td>
                                @foreach ($item->transaksi_detail as $detail)
                                   &#8226 {{number_format($detail->dataBarang->harga_barang)}} <br> 
                                @endforeach
                            </td>
                            <td>
                                @foreach ($item->transaksi_detail as $detail)
                                   &#8226 {{number_format($detail->sub_total)}} <br> 
                                @endforeach
                            </td>
                            <td>{{number_format($item->total_transaksi)}}</td>
                        @else
                            @if ($item->hutang == 1)
                                <td colspan="5" style="text-align: center;">PEMBAYARAN HUTANG</td>
                            @else
                                <td colspan="5" style="text-align: center;">{{$item->pembayaran->keterangan}}</td>
                            @endif
                        @endif
                    @endif
                    <td>{{number_format($item->pembayaran->jumlah_uang)}}</td>
                    @if ($item->pembayaran->pendapatan == 1)
                        @php
                            $pendapatan = $pendapatan + $item->pembayaran->jumlah_uang;
                        @endphp
                        <td style="color:blue">Pemasukan</td>
                    @else
                        @php
                            $pengeluaran = $pengeluaran + $item->pembayaran->jumlah_uang;
                        @endphp
                        <td style="color:red">Pengeluaran</td>
                    @endif
                </tr>
            @endforeach
            @php
                $laba_bersih = $pendapatan - $pengeluaran;
            @endphp
            <tr>
                <td colspan="8" style="text-align: center;"><b>TOTAL LABA BERSIH</b></td>
                <td colspan="2">{{number_format($laba_bersih)}} <b></b></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
