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
        <span style="font-size: 12pt;"><b>LAPORAN PENGELUARAN BARANG</b></span>
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
                  <th>Nama Pembeli</th>
                  <th>Nama Barang</th>
                  <th>Kode</th>
                  <th>Kemasan</th>
                  <th>QTY</th>
                  <th>Harga Jual</th>
                  <th style="width: 100px">Tanggal Pembelian</th>
              </tr>
        </thead>
        <tbody>
            {{-- {{dd($data['transaksi'])}} --}}
            @foreach ($data['transaksi'] as $key => $transaksi)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$transaksi->daftarPiutang->nama_pembeli}}</td>
                    <td>
                        @foreach ($data['transaksi_detail'] as $detail)
                            @if ($transaksi->id == $detail->id_transaksi)
                                &#8226 {{$detail->dataBarang->nama_barang}} <br>
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach ($data['transaksi_detail'] as $detail)
                            @if ($transaksi->id == $detail->id_transaksi)
                                @if ($detail->dataBarang->kode != null)
                                    &#8226 {{$detail->dataBarang->kode}} <br>
                                @else
                                    &#8226 <br>  
                                @endif
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach ($data['transaksi_detail'] as $detail)
                            @if ($transaksi->id == $detail->id_transaksi)
                                @if ($detail->dataBarang->kemasan != null)
                                    &#8226 {{$detail->dataBarang->kemasan}} <br>
                                @else
                                    &#8226 <br>  
                                @endif
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach ($data['transaksi_detail'] as $detail)
                            @if ($transaksi->id == $detail->id_transaksi)
                                &#8226 {{$detail->jumlah_barang}} <br>
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach ($data['transaksi_detail'] as $detail)
                            @if ($transaksi->id == $detail->id_transaksi)
                                &#8226 {{$detail->harga_barang}} <br>
                            @endif
                        @endforeach
                    </td>
                    <td>{{ \Carbon\Carbon::parse($transaksi->created_at)->format('d - m - Y')}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <h6>Total Barang</h6>
          <table style="width: 50%">
            <thead>
              <tr>
                <th>Nama Barang</th>
                <th>Kode</th>
                <th>Total Barang</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($data['total_item'] as $item)
                    <tr>
                        <td>{{$item->dataBarang->nama_barang}}</td>
                        @if ($item->dataBarang->kode != null)
                            <td>{{$item->dataBarang->kode}}</td>
                        @else
                            <td>-</td>
                        @endif
                        <td>{{$item->total_barang}}</td>
                    </tr>
                @endforeach
            </tbody>
          </table>
</body>
</html>
