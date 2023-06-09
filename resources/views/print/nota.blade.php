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
    <center style='width:100px; border-collapse: collapse;' border = '0'>
        <span style="font-size: 12pt;"><b>PERGUDANGAN IKAN ERKA</b></span>
        <span style="font-size: 6pt;">No. HP :081 335 171 566</span><br>
        <span style="font-size: 6pt; margin-left: 10px;">:081 359 818 889</span>
        <hr>
    </center>
    <center style='width:100px; border-collapse: collapse;' border = '0'>
        <span style="font-size: 9pt;">{{ $transaksi_detail[0]->transaksi->daftarPiutang->nama_pembeli }}</span><br>
        <span style="font-size: 9pt;">{{ date ('d - m - Y', strtotime($transaksi_detail[0]->transaksi->created_at)) }}</span>
        <hr>
    </center>
    <style>
        table {
            width: 150px;
            border-collapse: collapse;
        }
        tr {
            font-size: 8pt;
        }
    </style>
    <table style=" border-collapse: collapse; border-spacing: 1px; text-align: left">
        @foreach($transaksi_detail as $key => $item)
            <tr>
                <td colspan="2" style="text-align: center">{{ $item->dataBarang->nama_barang }}</td>
            </tr>
            <tr>
                <td>{{ $item->jumlah_barang }}</td>
                <td>{{ number_format($item->harga_barang) }}</td>
                <td>{{ number_format($item->sub_total) }}</td>
            </tr>
        @endforeach
    </table>
    <div style='width:100px; border-collapse: collapse;' border = '0'">
        <hr>
        <span style="font-size: 8pt;">TOTAL : {{ number_format($transaksi_detail[0]->transaksi->total_transaksi) }}</span>
    </div>
    <br>
    <br>
    
</body>
</html>
