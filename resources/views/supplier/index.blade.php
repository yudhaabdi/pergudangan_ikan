@extends('template.main')
@extends('supplier.modal.modal-tambah-supplier')
@extends('supplier.modal.modal-edit-supplier')
@section('title')
    Data SUPPLIER
@endsection
@section('jenis_tampilan')
    / DATA SUPPLIER
@endsection
@section('content')
    <div class="content">
        <div class="row" style="margin-bottom: 50px;">
            <div class="col-md-4">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambah_supplier">
                    Tambah Supplier
                </button>
            </div>
        </div>
        <table class="table table-striped table-bordered data_table">
            <thead>
                <tr>
                    <th style="width: 10px">NOMOR</th>
                    <th>NAMA</th>
                    <th>NO. HP</th>
                    <th>ALAMAT</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody id="data_supplier">
                @foreach($supplier as $key => $item)
                    <tr id="index_{{ $item->id }}">
                        <td>{{ $key+1}}</td>
                        <td>{{$item->nama}}</td>
                        <td>{{$item->no_hp}}</td>
                        <td>{{$item->alamat}}</td>
                        <td style="width: 200px">
                            <button class="btn btn-primary btn-edit" data-toggle="modal" data-target="#edit_supplier" data-url="{{url('/supplier/edit/'.$item->id)}}">Edit</button>
                            <button class="btn btn-danger btn-delete" data-id="{{$item->id}}" data-url="{{url('/supplier/hapus/'.$item->id)}}">Hapus</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>
@endsection
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
@section('script')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- <script src="js/jquery.min.js" type="text/javascript"></script> --}}
{{-- <script src="js/jquery.dataTables.min.js" type="text/javascript"></script> --}}
{{-- <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script> --}}
<script>
    $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus')
    })

    $('table').on('click', '.btn-edit',function(e){
        let url =  $(this).data('url');
        $('#formModalEdit').attr('action',url);
        console.log(url);
        getData(url);
    });

    function getData(url) {
        $.ajax({
            type: "GET",
            url: url,
            success: function(data){
                console.log(data);
                $('#nama').val(data.nama);
                $('#no_hp').val(data.no_hp);
                $('#alamat').html(data.alamat);
            },
        });
    }

    $('body').on('click', '.btn-delete', function () {

        let url = $(this).data('url');
        let id_supplier = $(this).data('id');

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
                        Swal.fire({
                            type: 'success',
                            icon: 'success',
                            title: `${response.message}`,
                            showConfirmButton: false,
                            timer: 3000
                        });
                        // window.location.reload(true);
                        $(`#index_${id_supplier}`).remove();
                    }
                });

                
            }
        })

    });
</script>
    
@endsection