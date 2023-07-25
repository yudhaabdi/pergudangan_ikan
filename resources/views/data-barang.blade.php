@extends('template.main')
@extends('modal.modal-tambah-data-barang')
@extends('modal.modal-tambah-data-barang-lama')
@extends('modal.modal-edit-data-barang')
@section('title')
    Data Barang
@endsection
@section('jenis_tampilan')
    / DATA BARANG
@endsection
@section('content')
    <div class="content">
        <div class="row" style="margin-bottom: 50px;">
            <div class="col-md-4">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambah_barang">
                    Pembelian Barang
                </button>
            </div>
            <div class="col-md-4"></div>
            <div class="col-md-4" style="text-align: right">
                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#modal_barang_lama">
                Barang Lama
                </button>
            </div>
        </div>
        <table class="table table-striped table-bordered data_table">
            <thead>
                <tr>
                    <th>NOMOR</th>
                    <th>NAMA IKAN</th>
                    <th>SIZE</th>
                    <th>KEMASAN</th>
                    <th>KODE BARANG</th>
                    <th>NO. KONTENER</th>
                    <th>JUMLAH STOK (\Kg)</th>
                    <th>HARGA BELI</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data_barang as $key => $barang)
                    <tr id="index_{{ $barang->id }}">
                        <td>{{ $key+1}}</td>
                        <td>{{ $barang->nama_barang}}</td>
                        <td>{{ $barang->size}}</td>
                        <td>{{ $barang->kemasan}}</td>
                        <td>{{ $barang->kode}}</td>
                        <td>{{ $barang->no_kontener}}</td>
                        <td>{{ $barang->stok_barang}} Kg</td>
                        <td>Rp. {{ number_format($barang -> harga_barang)}}</td>
                        <td style="width: 250px;">
                            <button style="font-size: 10px;" type="button" class="btn btn-primary btn-edit" data-url="{{ url('/data-barang/edit/'.$barang->id) }}" data-toggle="modal" data-target="#edit_barang">UBAH</button>
                            <button style="font-size: 10px;" href="javascript:void(0)" data-url="{{url('/data-barang/hapus/'.$barang->id) }}" data-id="{{ $barang->id }}" type="button" class="btn btn-danger btn-delete">HAPUS</button> <br>
                            <button style="font-size: 10px; margin-top: 10px;" href="javascript:void(0)" data-nama="{{$barang->nama_barang}}" data-url="{{url('/data-barang/penyusutan/'.$barang->id) }}" data-id="{{ $barang->id }}" type="button" class="btn btn-warning btn-penyusutan">PENYUSUTAN</button>
                            <button style="font-size: 10px; margin-top: 10px;"data-url="{{url('/data-barang/tambah-barang/'.$barang->id) }}" data-id="{{ $barang->id }}" type="button" class="btn btn-secondary btn-tambah-stok" data-toggle="modal" data-target="#edit_barang">TAMBAH STOK</button>
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
{{-- <script src="js/jquery.min.js" type="text/javascript"></script>
<script src="js/jquery.dataTables.min.js" type="text/javascript"></script> --}}
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script>
    $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus')
    })

    $('table').on('click', '.btn-edit',function(e){
        $("#formModalEdit").trigger("reset");
        let url =  $(this).data('url');
        $('#nama_modal').html('EDIT BARANG');
        $('#formModalEdit').attr('action',url);
        getData(url);
    });

    $('table').on('click', '.btn-tambah-stok',function(e){
        $("#formModalEdit").trigger("reset");
        let url =  $(this).data('url');
        let id_barang =  $(this).data('id');
        $('#nama_modal').html('TAMBAH STOK');
        $('#formModalEdit').attr('action',url);
        console.log(id_barang);
        getDataTambah(url, id_barang);
    });

    function getDataTambah(url, id_barang) {
        $.ajax({
            type: "GET",
            url: url,
            success: function(data){
                console.log(data);
                
                $('#id_barang').val(id_barang);
                $('#nama_barang').val(data.data_barang.nama_barang);
                $('#form_nama_barang').attr("hidden",true);
                $('#size').val(data.data_barang.size);
                $('#form_size').attr("hidden",true);
                $('#kemasan').val(data.data_barang.kemasan);
                $('#form_kemasan').attr("hidden",true);
                $('#harga_barang').val(data.data_barang.harga_barang);
                $('#form_harga_barang').attr("hidden",true);
                $('#kode_barang').val(data.data_barang.kode);
                $('#form_kode_barang').attr("hidden",true);
                $('#no_kontener').val(data.data_barang.no_kontener);
                $('#form_no_kontener').attr("hidden",true);
                $("#barang_lama").attr("hidden",false);
                $('#tunai').attr("checked",true);
            },
        });
    }

    function getData(url) {
        $.ajax({
            type: "GET",
            url: url,
            success: function(data){
                console.log(data);
                if (data.data_barang.lama == 1) {
                    $("#pemilik").attr("hidden",true);
                    $("#barang_lama").attr("hidden",true);
                }else{
                    $("#pemilik").attr("hidden",false);
                    $("#barang_lama").attr("hidden",false);
                }
                $('#pemilik_barang').val(data.data_barang.nama_pemilik);
                $('#nama_barang').val(data.data_barang.nama_barang);
                $('#size').val(data.data_barang.size);
                $('#kemasan').val(data.data_barang.kemasan);
                $('#jumlah_barang').val(data.data_barang.stok_barang);
                $('#harga_barang').val(data.data_barang.harga_barang);
                $('#kode_barang').val(data.data_barang.kode);
                $('#no_kontener').val(data.data_barang.no_kontener);
                if (data.data_barang.metode_pembayaran == 1) {
                    $('#tunai').attr("checked",true);
                    $("#jumlah_uang").attr("hidden",false);
                    $('#jumlah_uang').val(data.data_barang.jumlah_uang);
                    $('#nama_bank').attr("hidden",true);
                    $('#nama_bank').val();
                }else if(data.data_barang.metode_pembayaran == 2){
                    $('#tranfer').attr("checked",true);
                    $("#jumlah_uang").attr("hidden",false);
                    $('#jumlah_uang').val(data.data_barang.jumlah_uang);
                    $('#nama_bank').attr("hidden",false);
                    $('#nama_bank').val(data.data_barang.nama_bank);
                }
                else{
                    $('#hutang').attr("checked",true);
                    $("#jumlah_uang").attr("hidden",true);
                    $('#nama_bank').attr("hidden",true);
                    $('#nama_bank').val();
                    $('#jumlah_uang').val();
                }
            },
        });
    }

    $('body').on('click', '.btn-delete', function () {

        let url = $(this).data('url');
        let id_barang = $(this).data('id');

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
                        $(`#index_${id_barang}`).remove();
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