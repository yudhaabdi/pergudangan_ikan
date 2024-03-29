@extends('template.main')
@section('title')
    LAPORAN PENGELUARAN BARANG
@endsection
@section('jenis_tampilan')
    / LAPORAN PENGELUARAN BARANG
@endsection
@section('content')
    <div class="content">
        <form action="{{url('/laporan/laporan-data-barang/get-data')}}">
            <div class="row">
                <div class="col-md-4">
                  <input type="text" name="print" hidden value="print">
                  <div class="form-group">
                    <label>Masukkan Nama Barang</label>
                    <input type="text" class="form-control" name="nama_barang" id="nama_barang" autocomplete="off">
                    <small style="color: red" hidden id="validasi">! masukkan nama barang !</small>
                  </div>
                </div> 
                <div class="col-md-4">
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
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Gudang</label>
                    <select class="form-control select2" name="gudang" id="gudang" @if(Auth::User()->role != 'admin') disabled @endif>
                      @if (Auth::User()->role == 'admin')
                        <option value="1">Gudang 1</option>
                        <option value="2">Gudang 2</option>
                      @else
                          @if (Auth::User()->role == 'kasir 1')
                            <option value="1" selected>Gudang 1</option>
                          @else
                            <option value="2" selected>Gudang 2</option>
                          @endif
                      @endif
                    </select>
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
                  <th>Nama Pembeli</th>
                  <th>Nama Barang</th>
                  <th>Kode</th>
                  <th>Kemasan</th>
                  <th>QTY</th>
                  <th>Harga Jual</th>
                  <th style="width: 140px">Tanggal Pembelian</th>
              </tr>
          </thead>
          <tbody id="tabel_barang_list">
          </tbody>
          </table>
          <h6>Total Barang</h6>
          <table style="width: 50%">
            <thead>
              <tr>
                <th>Nama Barang</th>
                <th>Size</th>
                <th>Kode</th>
                <th>Total Barang</th>
              </tr>
            </thead>
            <tbody id="jumlah_Barang_keluar">

            </tbody>
          </table>
        </div>
    </div>
@endsection
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
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
      $('#jumlah_Barang_keluar').empty();
      $('#btn_cetak').removeAttr('hidden');
      nama_barang = $('#nama_barang').val();
      start_date = $('#start').val();
      end_date = $('#end').val();
      gudang = $('#gudang').val();
      let url = "{{url('/laporan/laporan-data-barang/get-data')}}";
      pencarian(nama, start, end, url, gudang);
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
                var html = '<tr>';

                for (let i = 0; i < data.transaksi.length; i++) {
                  const D = new Date(data.transaksi[i].created_at);
                  let bulan = D.getMonth() + 1;
                  let nomor = i+1;
                  
                  html += '<td>'+nomor+'</td>';
                  html += '<td>'+data.transaksi[i].daftar_piutang.nama_pembeli+'</td>';
                  html += '<td>';
                  for (let a = 0; a < data.transaksi_detail.length; a++) {
                    if (data.transaksi[i].id == data.transaksi_detail[a].id_transaksi) {
                      html += '&#8226 '+ data.transaksi_detail[a].data_barang.nama_barang+'<br>';
                    }
                  }
                  html += '</td>';
                  html += '<td>';
                  for (let a = 0; a < data.transaksi_detail.length; a++) {
                    if (data.transaksi[i].id == data.transaksi_detail[a].id_transaksi) {
                      if (data.transaksi_detail[a].data_barang.kode != null) {
                        html += '&#8226 '+ data.transaksi_detail[a].data_barang.kode+'<br>';
                      }else{
                        html += '&#8226 <br>';
                      }
                    }
                  }
                  html += '</td>';
                  html += '<td>';
                  for (let a = 0; a < data.transaksi_detail.length; a++) {
                    if (data.transaksi[i].id == data.transaksi_detail[a].id_transaksi) {
                      if (data.transaksi_detail[a].data_barang.kemasan != null) {
                        html += '&#8226 '+ data.transaksi_detail[a].data_barang.kemasan+'<br>';
                      }else{
                        html += '&#8226 <br>';
                      }
                    }
                  }
                  html += '</td>';
                  html += '<td>';
                  for (let a = 0; a < data.transaksi_detail.length; a++) {
                    if (data.transaksi[i].id == data.transaksi_detail[a].id_transaksi) {
                      html += '&#8226 '+ data.transaksi_detail[a].jumlah_barang+'<br>';
                    }
                  }
                  html += '</td>';
                  html += '<td>';
                  for (let a = 0; a < data.transaksi_detail.length; a++) {
                    if (data.transaksi[i].id == data.transaksi_detail[a].id_transaksi) {
                      html += '&#8226 '+ data.transaksi_detail[a].harga_barang+'<br>';
                    }
                  }
                  html += '</td>';
                  html += '<td>'+D.getDate()+' - '+bulan+' - '+D.getFullYear()+'</td>';
                  
                  html += '</tr>';
                }
                
                var html2 = '<tr>';
                for (let x = 0; x < data.total_item.length; x++) {
                  html2 += '<td>'+data.total_item[x].data_barang.nama_barang+'</td>';
                  html2 += '<td>'+data.total_item[x].data_barang.size+'</td>';
                  if (data.total_item[x].data_barang.kode != null) {
                    html2 += '<td>'+data.total_item[x].data_barang.kode+'</td>';
                  }else{html2 += '<td>-</td>'}
                  html2 += '<td>'+data.total_item[x].total_barang+'</td>';
                  html2 += '</tr>';
                }

            $('#tabel_barang_list').append(html);
            $('#jumlah_Barang_keluar').append(html2);
            }
        })
    }
    
  })
</script>