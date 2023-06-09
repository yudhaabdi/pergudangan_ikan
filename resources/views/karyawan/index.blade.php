@extends('template.main')

@section('title')
    Data Pegawai
@endsection
@section('jenis_tampilan')
    / DATA PEGAWAI
@endsection
@section('content')
    <div class="content">
        <div class="row" style="margin-bottom: 50px;">
            <div class="col-md-4">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambah_karyawan">
                    Tambah Pegawai
                </button>
            </div>
            <div class="col-md-4"></div>
            <div class="col-md-4" style="text-align: right">
                <button href="javascript:void(0)" data-url="{{url('/data-karyawan/gaji-semua') }}" id="btn_gaji" type="button" class="btn btn-secondary">
                    Penggajian
                </button>
            </div>
        </div>
        <table class="table table-striped table-bordered data_table">
            <thead>
                <tr>
                    <th style="width: 10px">NO</th>
                    <th>NAMA PEGAWAI</th>
                    <th>ALAMAT</th>
                    <th>NO. HP</th>
                    <th>GAJI</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($karyawan as $key => $item)
                    <tr id="index_{{ $item->id }}">
                        <td>{{ $key+1}}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->alamat }}</td>
                        <td>{{ $item->no_hp }}</td>
                        <td>{{ number_format($item->gaji) }}</td>
                        <td style="width: 240px">
                            <button type="button" class="btn btn-primary btn-edit" data-url="{{ url('/data-karyawan/edit/'.$item->id) }}" data-toggle="modal" data-target="#edit_karyawan">UBAH</button>
                            <button href="javascript:void(0)" data-url="{{url('/data-karyawan/hapus/'.$item->id) }}" data-id="{{ $item->id }}" type="button" class="btn btn-danger btn-delete">HAPUS</button>
                            <button href="javascript:void(0)" data-url="{{url('/data-karyawan/gaji/'.$item->id) }}" data-id="{{ $item->id }}" type="button" class="btn btn-secondary btn-gaji">GAJI</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>
@endsection
@extends('karyawan.modal-tambah-data-karyawan')
@extends('karyawan.modal-gaji-karyawan')
@extends('karyawan.modal-edit-data-karyawan')
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
@section('script')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="js/jquery.min.js" type="text/javascript"></script>
<script src="js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script>
    $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus')
    })

    $('table').on('click', '.btn-edit',function(e){
        let url =  $(this).data('url');
        $('#formModalEdit').attr('action',url);
        getData(url);
    });

    function getData(url) {
        $.ajax({
            type: "GET",
            url: url,
            success: function(data){
                console.log(data);
                $('#nama').val(data.nama);
                $('#alamat').val(data.alamat);
                $('#no_hp').val(data.no_hp);
                $('#gaji').val(data.gaji);
            },
        });
    }

    $('body').on('click', '.btn-delete', function () {

        let url = $(this).data('url');
        let id_karyawan = $(this).data('id');
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

                        // window.location.reload(true);

                        $(`#index_${id_karyawan}`).remove();
                    }
                });

                
            }
        })

    });

    $('body').on('click', '#btn_gaji', function () {

        let url = $(this).data('url');

        Swal.fire({
            title: 'Apakah Kamu Yakin?',
            text: "ingin membayar semua pagawai!",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'TIDAK',
            confirmButtonText: 'YA, BAYAR!'
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
                    }
                });
            }
        })

    });

    $('body').on('click', '.btn-gaji', function () {

        let url = $(this).data('url');

        Swal.fire({
            title: 'Apakah Kamu Yakin?',
            text: "ingin membayar karyawan!",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'TIDAK',
            confirmButtonText: 'YA, BAYAR!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type:"get",
                    url,
                    success:function(response){ 
                        console.log(response.info == false);
                        if (response.info == false) {
                            Swal.fire({
                                type: 'success',
                                icon: 'success',
                                title: `${response.message}`,
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }else{
                            Swal.fire({
                                type: "success",
                                icon: 'info',
                                title: `${response.message}`,
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }
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
        });
        $(".inlineRadio2").click(function(){
            $(".nama_bank").removeAttr('hidden');
            $(".nama_bank").attr("required",true);
        });
    });
  </script>
    
@endsection