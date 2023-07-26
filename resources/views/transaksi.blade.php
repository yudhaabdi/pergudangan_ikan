@extends('template.main')
@section('title')
    Transaksi
@endsection
@section('jenis_tampilan')
    / Transaksi
@endsection
<style>
    .garis_vertikal{
        border-left: 1px gray solid;
        width:0px;
    }
</style>
@section('content')
{{-- {{ dd($cart) }} --}}

    <div class="content">
        <div class="row g-5">
            <div class="col-4">               
                <form action="{{ url('/transaksi/shopping-chart') }}" method="get">
                    <div class="row">
                        <div class="form-group">
                            <label>Nama Barang</label>
                            <select class="form-control select2" name="nama_barang" id="nama_barang">
                                <option value=""></option>
                                @foreach($data_barang as $key => $item)
                                <option value="{{ $item->id }}">{{ $item->nama_barang }} / {{$item->size}} / &#64;{{$item->kemasan}} / {{$item->kode}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Jumlah Barang (\Kg)</label>
                            <input type="text" step="any" 
                                autocomplete="off"
                                class="form-control inputmask" 
                                placeholder="masukkan jumlah barang" 
                                name="jumlah_barang" 
                                id="jumlah_barang"
                                data-inputmask="'alias': 'numeric','digits': 2,'groupSeparator':',', 'autoGroup' : true,
                                'removeMaskOnSubmit': true, 'autoUnmask': true"
                            >
                        </div>
                        <div class="form-group">
                            <label>Harga Barang</label>
                            <input type="text" class="form-control inputmask" placeholder="masukkan harga barang" 
                            autocomplete="off"
                            name="harga_barang" id="harga_barang"
                            data-inputmask="'alias': 'numeric','digits': 2,'groupSeparator':',', 'autoGroup' : true,
                            'removeMaskOnSubmit': true, 'autoUnmask': true"
                            >
                        </div>
                    </div>
                    <div class="row">
                        <button class="btn btn-primary" type="submit">Masukkan Keranjang</button>
                    </div>
                </form>
            </div>
            <div class="col-md-8 garis_vertikal">
                <h3>RINCIAN PEMBELIAN</h3>
                {{-- <form action="{{ url('/transaksi/bayar') }}" autocomplete="off" method="post"> --}}
                <form action="javascript:void(0)" id="form_bayar" autocomplete="off" method="post">
                    <div class="form-group">
                        <label>Nama Pembeli</label>
                        <input type="text" class="form-control" placeholder="masukkan nama pembeli" name="nama_pembeli" id="nama_pembeli" required>
                    </div>  
                    {{ csrf_field() }}
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>Nama Barang</th>
                                <th>Jumlah Barang</th>
                                <th>Harga Barang</th>
                                <th>Sub Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (empty($cart) || count($cart) == 0)
                                <tr>
                                    <td colspan="4" style="text-align: center">Total Belanja</td>
                                    <td colspan="2"></td>
                                </tr>
                            @else
                                @php $no = 1; $grandtotal = 0;@endphp
    
                                @foreach($cart as $key => $item)
                                    @php $sub_total = $item['jumlah_barang'] * $item['harga_barang']; @endphp
                                    <tr class="tabel_1">
                                        <td>{{ $no++ }}</td>   
                                        <td>{{ $item['nama_barang'] }}</td>                             
                                        <td>{{ $item['jumlah_barang'] }} Kg</td>                             
                                        <td>Rp. {{ number_format($item['harga_barang']) }}</td>                             
                                        <td>Rp. {{ number_format($sub_total) }}</td>                            
                                        <td><a href="{{ url('/transaksi/shopping-chart/delete/'.$key.'') }}" class="btn btn-danger">Hapus</a></td>                             
                                    </tr>
                                    <input type="text" name="id_barang[]" value="{{ $item['id'] }}" hidden>
                                    <input type="text" name="jumlah_barang[]" value="{{ $item['jumlah_barang'] }}" hidden>
                                    <input type="text" name="harga_barang[]" value="{{ $item['harga_barang'] }}" hidden>
                                    <input type="text" name="sub_total[]" value="{{ $sub_total }}" hidden>
                                    @php $grandtotal += $sub_total; @endphp
                                @endforeach
                                <tr class="tabel_2">
                                    <td colspan="4" style="text-align: center">total belanja </td>
                                    <td colspan="2">Rp. {{ number_format($grandtotal) }}</td>
                                    <input type="text" name="total_belanja" value="{{ $grandtotal }}" hidden>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="form-group">
                        <label>Metode Pembayaran</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="metode_bayar" id="inlineRadio1" value="1" checked>
                        <label class="form-check-label" for="inlineRadio1">Tunai</label>
                    </div>
                    <div class="form-check form-check-inline" style="margin-left: 100px">
                        <input class="form-check-input" type="radio" name="metode_bayar" id="inlineRadio2" value="2">
                        <label class="form-check-label" for="inlineRadio2">Tranfer</label>
                    </div>
                    <div class="form-check form-check-inline" style="margin-left: 100px">
                        <input class="form-check-input" type="radio" name="metode_bayar" id="inlineRadio3" value="3">
                        <label class="form-check-label" for="inlineRadio2">Hutang</label>
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control" name="nama_banK" id="nama_bank" placeholder="Masukkan nama bank" hidden>
                    </div>
                    <div class="form-group">
                        <label>Pembayaran</label>
                        <input type="text" class="form-control inputmask" placeholder="masukkan jumlah uang" name="jumlah_uang" id="jumlah_uang"
                            name="harga_barang" id="harga_barang"
                            autocomplete="off"
                            data-inputmask="'alias': 'numeric','digits': 2,'groupSeparator':',', 'autoGroup' : true,
                            'removeMaskOnSubmit': true, 'autoUnmask': true"
                        >
                    </div>
                    @if (empty($cart) || count($cart) == 0)
                        <button type="submit" class="btn btn-primary" style="width: 100%" disabled>Bayar</button>
                    @else
                        <button type="" id="bayar" class="btn btn-primary" data-url="{{ url('/transaksi/bayar') }}" style="width: 100%">Bayar</button>
                    @endif
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function(){
        $("#inlineRadio1").click(function(){
            $("#nama_bank").attr("hidden",true);
            $("#nama_bank").attr("required",false);
            $("#nama_bank").val('');
            $("#jumlah_uang").attr("hidden",false);
        });
        $("#inlineRadio2").click(function(){
            $("#nama_bank").removeAttr('hidden');
            $("#nama_bank").attr("required",true);
            $("#jumlah_uang").attr("hidden",false);
        });
        $("#inlineRadio3").click(function(){
            $("#nama_bank").attr("hidden",true);
            $("#jumlah_uang").attr("hidden",true);
            $("#nama_bank").attr("required",false);
            $("#nama_bank").val('');
            $("#jumlah_uang").val('');
        });

        $('body').on('click', '#bayar', function () {

            let url = $(this).data('url');
            let id_barang = $(this).data('id');
            let data_form = $('#form_bayar').serialize();

            Swal.fire({
                title: 'Apakah Kamu Yakin?',
                text: "ingin melakukan transaksi ini!",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'TIDAK',
                confirmButtonText: 'YA, BAYAR!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type:"post",
                        data: data_form,
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
                            $('.tabel_1').empty();
                            $('.tabel_2').empty();
                            $('#nama_bank').val('');
                            $('#jumlah_uang').val('');
                            $('#nama_pembeli').val('');
                        }
                    });

                    
                }
            })

        });
    });
</script>
@endsection
