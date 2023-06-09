@extends('template.main')
@section('title')
    Transaksi Detail
@endsection
@section('jenis_tampilan')
    / Transaksi Detail
@endsection
@section('content')
    <div class="content">
        <div class="row">
            <h2>Data Transaksi {{ $transaksi->nama_pembeli }}</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Ikan</th>
                        <th>Jumlah_barang</th>
                        <th>Harga Barang</th>
                        <th>Sub Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksi_detail as $key => $item)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $item->dataBarang->nama_barang }} / {{$item->dataBarang->kode}} / {{$item->dataBarang->no_kontener}}</td>
                            <td>{{ $item->jumlah_barang }} Kg</td>
                            <td>Rp. {{ number_format($item->harga_barang) }}</td>
                            <td>Rp. {{ number_format($item->sub_total) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection