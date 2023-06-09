@extends('template.main')
@section('title')
    Pendapatan Lain - Lain
@endsection
@section('jenis_tampilan')
    / Pendapatan Lain - Lain
@endsection
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-4">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambah_barang">
                    Tambah
                </button>
            </div>
            <h2>Data Pendapatan Lain - Lain</h2>
            <table class="table table-bordered data_table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Jumlah uang</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pembayaran as $key => $item)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $item->transaksi->daftarPiutang->nama_pembeli }}</td>
                        <td>Rp. {{ number_format($item->jumlah_uang) }}</td>
                        <td>{{ $item->keterangan }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@extends('modal.modal-tambah-pendapatan')
{{-- @extends('modal.modal-edit-pengeluaran') --}}

<script>
    
</script>
@section('script')
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<script>
    $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus')
    })
    
    $(document).ready(function(){
        $("#inlineRadio1").click(function(){
            $(".nama_bank").attr("hidden",true);
            $(".nama_bank").attr("required",false);
            $(".nama_bank").val('');
        });
        $("#inlineRadio2").click(function(){
            $(".nama_bank").removeAttr('hidden');
            $(".nama_bank").attr("required",true);
            $("#jumlah_uang").attr("hidden",false);
        });
        $("#inlineRadio3").click(function(){
            $(".nama_bank").attr("hidden",true);
            $(".nama_bank").attr("required",false);
            $(".nama_bank").val('');
        });
    });
</script>
@endsection