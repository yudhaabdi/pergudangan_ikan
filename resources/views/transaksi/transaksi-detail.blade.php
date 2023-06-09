@extends('template.main')
@section('title')
    Transaksi Detail
@endsection
@section('jenis_tampilan')
    / Data Transaksi
@endsection
@section('content')
    <div class="content">
        <div class="row">
            <h2>Data Transaksi</h2>
            <table class="table table-bordered data_table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pembeli</th>
                        <th>Total Belanja</th>
                        <th>Bayar</th>
                        <th>Kekurangan</th>
                        <th>Pembayaran</th>
                        <th>Tanggal Belanja</th>
                        <th width="100px"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksi as $key => $item)
                        <tr @if (!empty($item->kekurangan)) class="table-danger" @endif>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $item->daftarPiutang->nama_pembeli }}</td>
                            <td>Rp. {{ number_format($item->total_transaksi) }}</td>
                            <td>Rp. {{ number_format($item->pembayaran->jumlah_uang) }}</td>
                            <td>Rp. {{ number_format($item->kekurangan) }}</td>
                            @if ($item->pembayaran->metode_pembayaran == 1)
                                <td>Tunai</td>
                            @elseif($item->pembayaran->metode_pembayaran == 3)
                                <td>Hutang</td>
                            @else
                                <td>Tranfer {{ $item->pembayaran->nama_bank }}</td>
                            @endif
                            <td>{{ date ('d - m - Y', strtotime($item->created_at)) }}</td>
                            <td>
                                <a href="{{ url('/transaksi-detail/'.$item->id) }}" class="btn btn-primary" style="font-size: 10px">Lihat detail</a>
                                <a href="{{ url('/transaksi-detail-return/'.$item->id) }}" class="btn btn-secondary" style="font-size: 10px">Return</a>
                                <button href="javascript:void(0)" data-url="{{url('/transaksi-detail-return/hapus/'.$item->id)}}" data-id="{{ $item->id }}" type="button" class="btn btn-danger btn-delete" style="font-size: 10px">HAPUS</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
@section('script')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="js/jquery.min.js" type="text/javascript"></script>
<script>
    $('body').on('click', '.btn-delete', function () {

    let url = $(this).data('url');
    let id_barang = $(this).data('id');
    let token   = $("meta[name='csrf-token']").attr("content");
    var _method = "DELETE";

    Swal.fire({
        title: 'Apakah Kamu Yakin?',
        text: "ingin menghapus data ini!",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'TIDAK',
        confirmButtonText: 'YA, HAPUS!'
    }).then((result) => {
        if (result.value) {

            $.ajax({

                type:"get",
                url,
                success:function(response){ 

                    //show success message
                    Swal.fire({
                        type: 'success',
                        icon: 'success',
                        title: `${response.message}`,
                        showConfirmButton: false,
                        timer: 3000
                    });

                    window.location.reload(true);
                }
            });

            
        }
    })

    });
</script>
@endsection