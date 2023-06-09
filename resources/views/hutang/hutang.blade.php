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
                <h2>DAFTAR PIUTANG PEGAWAI / PEMBELI</h2>
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-outline-dark" id="btn_tambah" data-url="{{ url('/hutang/tambah') }}" data-toggle="modal" data-target="#tambah_hutang">Input Hutang</button>
            </div>
            <table class="table table-bordered data_table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Total Hutang</th>
                        <th>Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($piutang as $key => $item)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $item->nama_pembeli }}</td>
                            <td>{{ number_format($item->total_hutang) }}</td>
                            <td>
                                <button type="button" class="btn btn-primary btn_hutang" data-url="{{ url('/hutang').'/'.$item->id }}" data-toggle="modal" data-target="#bayar_hutang">Bayar</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@extends('modal.modal-bayar-hutang')
@extends('modal.modal-tambah-hutang')
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