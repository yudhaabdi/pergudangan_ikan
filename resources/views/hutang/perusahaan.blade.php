@extends('template.main')
@section('title')
    Bayar Hutang
@endsection
@section('jenis_tampilan')
    / Bayar Hutang
@endsection
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-9">
                <h2>DAFTAR PIUTANG PERUSAHAAN</h2>
            </div>
            {{-- <div class="col-md-3">
                <button type="button" class="btn btn-outline-dark" id="btn_tambah" data-url="{{ url('/hutang/tambah') }}" data-toggle="modal" data-target="#tambah_hutang">Input Hutang</button>
            </div> --}}
            <table class="table table-bordered data_table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pemilik Barang</th>
                        <th>Nama Barang</th>
                        <th>Kode Barang</th>
                        <th>No. Kontener</th>
                        <th>Tanggal</th>
                        <th>Hutang</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hutang as $key => $item)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $item->nama_pemilik }}</td>
                            <td>{{ $item->dataBarang->nama_barang }}</td>
                            @if ($item->dataBarang->kode == null)
                                <td>-</td>
                            @else
                                <td>{{ $item->dataBarang->kode }}</td>
                            @endif
                            <td>{{ $item->dataBarang->no_kontener }}</td>
                            <td>{{ date ('d - m - Y', strtotime($item->created_at)) }}</td>
                            <td>{{ number_format($item->hutang) }}</td>
                            <td>
                                <button type="button" class="btn btn-primary btn_hutang" data-url="{{ url('/hutang/perusahaan').'/'.$item->id }}" data-toggle="modal" data-target="#bayar_hutang">Bayar</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@extends('hutang.modal-bayar-hutang-perusahaan')
@section('script')
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<script>
    $(document).on('click', '.btn_hutang', function(e) {
        let url =  $(this).data('url');
        $('#formModalHutang').attr('action',url);
    });

    $(document).on('click', '#btn_tambah', function(e) {
        let url =  $(this).data('url');
        $('#formModalTambahHutang').attr('action',url);
    });
</script>
@endsection