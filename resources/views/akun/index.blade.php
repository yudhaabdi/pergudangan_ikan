@extends('template.main')
@extends('akun.modal-tambah-akun')
{{-- @extends('modal.modal-tambah-data-barang-lama') --}}
@extends('akun.modal-edit-akun')
@section('title')
    Pengaturan Akun
@endsection
@section('jenis_tampilan')
    / PENGATURAN AKUN
@endsection
@section('content')
    <div class="content">
        <div class="row" style="margin-bottom: 50px;">
            <div class="col-md-4">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambah_akun">
                    Tambah Akun
                </button>
            </div>
        </div>
        <table class="table table-striped table-bordered data_table">
            <thead>
                <tr>
                    <th>NOMOR</th>
                    <th>NAMA</th>
                    <th>EMAIL</th>
                    <th>ROLE</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($user as $key => $item)
                    <tr id="index_{{ $item->id }}">
                        <td>{{ $key+1}}</td>
                        <td>{{ $item->name}}</td>
                        <td>{{ $item->email}}</td>
                        <td>{{ $item->role}}</td>
                        <td style="width: 250px;">
                            <button style="font-size: 10px;" type="button" class="btn btn-primary btn-edit" data-url="{{ url('/pengaturan-akun/edit/'.$item->id) }}" data-toggle="modal" data-target="#edit_user">UBAH</button>
                            <button style="font-size: 10px;" href="javascript:void(0)" data-url="{{url('/pengaturan-akun/hapus/'.$item->id) }}" data-id="{{ $item->id }}" type="button" class="btn btn-danger btn-delete">HAPUS</button>
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
<script>
    $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus')
    })

    $('table').on('click', '.btn-edit',function(e){
        let url =  $(this).data('url');
        $('#formModalEditAkun').attr('action',url);
        getData(url);
    });

    function getData(url) {
        $.ajax({
            type: "GET",
            url: url,
            success: function(data){
                console.log(data);
                $('#nama').val(data.name);
                $('#email').val(data.email);
                $('#role').val(data.role);
            },
        });
    }

    $('body').on('click', '.btn-delete', function () {

        let url = $(this).data('url');
        let id_akun = $(this).data('id');

        Swal.fire({
            title: 'Apakah Kamu Yakin?',
            text: "ingin menghapus akun ini!",
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
                        $(`#index_${id_akun}`).remove();
                    }
                });

                
            }
        })

    });

    $('body').on('click', '.btn-penyusutan', function () {

        let url = $(this).data('url');
        let id_barang = $(this).data('id');
        let nama = $(this).data('nama');

        Swal.fire({
            title: 'Apakah Kamu Yakin?',
            text: nama+" Mengalami Penyusutan!",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'TIDAK',
            confirmButtonText: 'YA, UBAH!'
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
                        $(`#index_${id_barang}`).remove();
                    }
                });

                
            }
        })

    });
</script>
<script>
    $(document).ready(function(){
        $(".inlineRadio1").click(function(){
            $(".nama_bank").attr("hidden",true);
            $(".nama_bank").attr("required",false);
            $(".nama_bank").val('');
            $(".jumlah_uang").attr("hidden",false);
        });
        $(".inlineRadio2").click(function(){
            $(".nama_bank").removeAttr('hidden');
            $(".nama_bank").attr("required",true);
            $(".jumlah_uang").attr("hidden",false);
        });
        $(".inlineRadio3").click(function(){
            $(".nama_bank").attr("hidden",true);
            $(".jumlah_uang").attr("hidden",true);
            $(".nama_bank").attr("required",false);
            $(".nama_bank").val('');
            $(".jumlah_uang").val('');
        });
    });
  </script>
    
@endsection