@extends('template.main')
@section('title')
    LAPORAN STOK BARANG
@endsection
@section('jenis_tampilan')
    / LAPORAN STOK BARANG
@endsection
@section('content')
    <div class="content">
        <form action="{{url('/laporan/laporan-stok-barang/get-data')}}">
            <div class="row">
                <div class="col-md-6">
                  <input type="text" name="print" hidden value="print">
                  <div class="form-group">
                    <label>Masukkan Nama Barang</label>
                    <select class="form-control select2" name="nama_barang" id="nama_barang">
                      <option value="all">Semua</option>
                      @foreach ($nama as $item)
                          <option value="{{$item->id}}">{{$item->nama_barang}} / {{$item->size}} / {{$item->kode}}</option>
                      @endforeach
                    </select>
                  </div>
                </div> 
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Masukkan tanggal</label>
                    <div class="row">
                      <div class="col-md-6">
                        <input class="form-control" type="date" name="start_date" id="start">
                      </div>
                      <div class="col-md-6">
                        <input class="form-control" type="date" name="end_date" id="end">
                      </div>
                    </div>
                  </div>
                </div>  
            </div>
            <button type="submit" class="btn btn-success" formtarget="_blank" style="width: 100%" id="btn_cetak" hidden>CETAK</button>
        </form>
        <button class="btn btn-primary" style="width: 100%" id="btn_cari">CARI</button>
        <div class="row">
          <table class="table table-bordered" id="tabel_barang">
            <thead>
              <tr>
                  <th>NO</th>
                  <th>Tanggal</th>
                  <th>Nama</th>
                  <th>Nama Barang</th>
                  <th>Kode</th>
                  <th>QTY</th>
                  <th>Harga Beli</th>
                  <th>Harga Jual</th>
                  <th>keterangan</th>
              </tr>
          </thead>
          <tbody id="tabel_barang_list">
          </tbody>
          </table>
        </div>
        <div class="row">
          <label style="font-weight: bold;">Total Stok Saat Ini</label>
          <table class="table table-bordered" id="tabel_stok_barang" style="width: 500px">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Size</th>
                <th>Kode</th>
                <th>Stok</th>
              </tr>
            </thead>
            <tbody id="tabel_stok"></tbody>
          </table>
        </div>
    </div>
@endsection
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script> --}}
{{-- <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script> --}}
<script>
  $(document).ready(function(){

    var nama = null;
    var start = null;
    var end = null;

    $('#start').change(function(){
      if ($('#end').val() == '') {
        $('#end').val($(this).val());
      }
    })

    $('#end').change(function(){
      if ($('#start').val() == '') {
        $('#start').val($(this).val());
      }
    })

    $('#btn_cari').click(function(){
      $('#tabel_barang_list').empty();
      $('#tabel_stok').empty();
      $('#btn_cetak').removeAttr('hidden');
      nama_barang = $('#nama_barang').val();
      start_date = $('#start').val();
      end_date = $('#end').val();
      gudang = $('#gudang').val();
      let url = "{{url('/laporan/laporan-stok-barang/get-data')}}";
      pencarian(nama, start, end, url);
    })

    function pencarian(nama, start, end, url){
        $.ajax({
            url: url,
            type: "GET",
            data: {
              nama_barang,
              start_date,
              end_date,
              gudang
            },
            success: function(data){
              console.log(data);
                var html = '<tr>';

                for (let i = 0; i < data.transaksi.length; i++) {
                  const D = new Date(data.transaksi[i].created_at);
                  let bulan = D.getMonth() + 1;
                  let nomor = i+1;
                  
                  html += '<td>'+nomor+'</td>';
                  html += '<td>'+D.getDate()+' - '+bulan+' - '+D.getFullYear()+'</td>';
                  if (data.transaksi[i].nama_pemilik != null) {
                    html += '<td>'+data.transaksi[i].nama_pemilik+'</td>';
                  }else{
                    html += '<td>'+data.transaksi[i].nama_pembeli+'</td>';
                  }
                  html += '<td>'+data.transaksi[i].nama_barang+'</td>';
                  html += '<td>'+data.transaksi[i].kode+'</td>';
                  if (data.transaksi[i].nama_pemilik != null) {
                    var stok = (data.transaksi[i].total_transaksi + data.transaksi[i].kekurangan) / data.transaksi[i].harga_beli;
                    html += '<td>'+stok+'</td>';
                  }else{
                    html += '<td>'+data.transaksi[i].jumlah_barang+'</td>';
                  }
                  if (data.transaksi[i].nama_pemilik != null) {
                    html += '<td>'+data.transaksi[i].harga_beli+'</td>';
                    html += '<td>-</td>';
                  }else{
                    html += '<td>'+data.transaksi[i].harga_beli+'</td>';
                    html += '<td>'+data.transaksi[i].harga_jual+'</td>';
                  }
                  if (data.transaksi[i].nama_pemilik != null) {
                    html += '<td style="color:blue">Barang Masuk</td>';
                  }else{
                    html += '<td style="color:red">Barang Keluar</td>';
                  }
                  html += '</tr>'
                }

                html2 = '<tr>';
                for (let z = 0; z < data.data_barang.length; z++) {
                  let nomor2 = z+1;
                  html2 += '<td>'+nomor2+'</td>';
                  html2 += '<td>'+data.data_barang[z].nama_barang+'</td>';
                  html2 += '<td>'+data.data_barang[z].size+'</td>';
                  html2 += '<td>'+data.data_barang[z].kode+'</td>';
                  html2 += '<td>'+data.data_barang[z].stok_barang+'</td>';
                  html2 += '</tr>';
                }

            $('#tabel_barang_list').append(html);
            $('#tabel_stok').append(html2);
            }
        })
    }
    
  })
</script>