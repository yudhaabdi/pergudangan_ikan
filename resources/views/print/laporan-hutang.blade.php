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
        <span style="font-size: 12pt;"><b>LAPORAN PIUTANG</b></span>
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
                <th>Metode Pembayaran</th>
                <th>Nama Bank</th>
                <th>Hutang</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_hutang = 0;
            @endphp
            @foreach ($data as $key => $item)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$item->daftarPiutang->nama_pembeli}}</td>
                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d - m - Y')}}</td>
                    @if ($item->pembayaran->lain_lain == 2 || $item->pembayaran->lain_lain == 1)
                        @if ($item->pembayaran->lain_lain == 1)
                            <td colspan="5" style="text-align: center">{{$item->pembayaran->keterangan}}</td>
                            <td>{{number_format($item->pembayaran->jumlah_uang)}}</td>
                        @else
                            <td colspan="5" style="text-align: center">{{$item->pembayaran->keterangan}}</td>
                            <td>{{number_format($item->pembayaran->jumlah_uang)}}</td>
                        @endif
                        @if ($item->pembayaran->metode_pembayaran == 1)
                            <td>Tunai</td>
                        @elseif ($item->pembayaran->metode_pembayaran == 2)
                            <td>{{$item->pembayaran->nama_bank}}</td>
                        @elseif ($item->pembayaran->metode_pembayaran == 3)
                            <td>Hutang</td>
                        @endif
                        @if ($item->pembayaran->nama_bank == null)
                            <td>-</td>
                        @else
                            <td>{{$item->pembayaran->nama_bank}}</td>
                        @endif
                        <td>{{$item->kekurangan}}</td>
                    @else
                        @if (count($item->transaksi_detail) > 0)
                            <td>
                                @foreach ($item->transaksi_detail as $key => $detail)
                                    &#8226 {{$detail->dataBarang->nama_barang}} <br>
                                @endforeach
                            </td>
                            <td>
                            @foreach ($item->transaksi_detail as $key => $detail)
                                &#8226 {{$detail->jumlah_barang}} <br>
                            @endforeach
                            </td>
                            <td>
                            @foreach ($item->transaksi_detail as $key => $detail)
                                &#8226 {{number_format($detail->harga_barang)}} <br>
                            @endforeach
                            </td>
                            <td>
                            @foreach ($item->transaksi_detail as $key => $detail)
                                &#8226 {{number_format($detail->sub_total)}} <br>
                            @endforeach
                            </td>
                        @else
                            <td colspan="5" style="text-align: center;color: blue;"><b>PEMBAYARAN HUTANG</b></td>
                        @endif
                        @if ($item->hutang == null)
                            <td>{{number_format($item->total_transaksi)}}</td>
                        @endif
                        @if ($item->pembayaran->jumlah_uang == null)
                            <td>0</td>
                        @else
                            <td>{{number_format($item->pembayaran->jumlah_uang)}}</td>  
                        @endif
                        @if ($item->pembayaran->metode_pembayaran == 1)
                            <td>Tunai</td>
                        @endif
                        @if ($item->pembayaran->metode_pembayaran == 2)
                            <td>{{$item->pembayaran->nama_bank}}</td>
                        @endif
                        @if ($item->pembayaran->metode_pembayaran == 3)
                            <td>Hutang</td>
                        @endif
                        @if ($item->pembayaran->nama_bank == null)
                            <td>-</td>
                        @else
                            <td>{{$item->pembayaran->nama_bank}}</td>
                        @endif
                        @if ($item->hutang == null)
                        <td>{{number_format($item->kekurangan)}}</td>
                        @else
                        {{-- {{dd($item)}} --}}
                         <td>{{number_format($item->kekurangan)}}</td>
                        @endif
                    @endif
                </tr>
                @php
                    $hutang = 
                    $hutang = $item->kekurangan;
                @endphp
                @if ($item->hutang == null)
                    @php
                        $total_hutang = $hutang + $total_hutang;
                    @endphp
                @else
                    @php
                        $total_hutang = $item->hutang_sebelum - $item->total_transaksi;
                    @endphp
                @endif
                <tr>
                    <td colspan="11" style="text-align: center;"><b>SISA HUTANG</b></td>
                    @if ($item->daftarPiutang->total_hutang == 0)
                        <td>LUNAS</td>
                    @else
                        <td>{{number_format($total_hutang)}}</td>
                    @endif
                </tr>
                @endforeach
        </tbody>
    </table>
</body>
</html>
