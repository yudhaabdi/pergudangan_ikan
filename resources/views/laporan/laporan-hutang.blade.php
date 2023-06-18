@extends('template.main')
@section('title')
    LAPORAN PIUTANG
@endsection
@section('jenis_tampilan')
    / LAPORAN PIUTANG
@endsection
@section('content')
    <div class="content">
      <form action="{{url('/laporan/laporan-hutang/get-data')}}">
        <div class="row">
          <div class="col-md-6">
            <input type="text" name="print" hidden value="print">
            <div class="form-group">
              <label>Masukkan Nama</label>
              <select class="form-control select2" name="nama_pembeli" id="nama_pembeli">
                <option value="all">semua</option>
                @foreach ($nama as $key => $item)
                    <option value="{{$item->id}}"> {{$item->nama_pembeli}} </option>
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
          <table class="table table-bordered" id="tabel_pengeluaran" style="font-size: 15px">
            <thead>
              <tr>
                  <th>NO</th>
                  <th>Nama</th>
                  <th>Tanggal</th>
                  <th>Nama Barang</th>
                  <th>QTY</th>
                  <th>Harga</th>
                  <th>Total Harga</th>
                  <th>Total Faktur</th>
                  <th>Pembayaran</th>
                  <th>Hutang</th>
              </tr>
          </thead>
          <tbody id="tabel_pengeluaran_list">
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
      $('#tabel_pengeluaran_list').empty();
      $('#btn_cetak').removeAttr('hidden');
      nama_pembeli = $('#nama_pembeli').val();
      start_date = $('#start').val();
      end_date = $('#end').val();

      let url = "{{url('/laporan/laporan-hutang/get-data')}}";
      $.ajax({
        url: url,
        type: "GET",
        data: {
          nama_pembeli,
          start_date,
          end_date
        },
        success: function(data){

          var html = '<tr>';
          var qty = 0;
          var total_hutang = 0;
          for (let i = 0; i < data.length; i++) {
            const D = new Date(data[i].created_at);
            let bulan = D.getMonth() + 1;
            let nomor = i+1;
  
            html += '<td>'+nomor+'</td>';
            html += '<td>'+data[i].daftar_piutang.nama_pembeli+'</td>';
            html += '<td>'+D.getDate()+' - '+bulan+' - '+D.getFullYear()+'</td>';
            if (data[i].pembayaran.lain_lain == 2 || data[i].pembayaran.lain_lain == 1) {
              if (data[i].pembayaran.lain_lain == 1) {
                html += '<td colspan="5" style="text-align: center;">'+data[i].pembayaran.keterangan+'</td>';
                html += '<td>'+parseInt(data[i].pembayaran.jumlah_uang).toLocaleString()+'</td>'
              }else{
                html += '<td colspan="6" style="text-align: center;">'+data[i].pembayaran.keterangan+'</td>';
              }
              html += '<td>'+data[i].kekurangan+'</td>';
            }else{
              if (data[i].transaksi_detail.length > 0) {
                html += '<td>';
                for (let a = 0; a < data[i].transaksi_detail.length; a++) {
                  let nama_barang = data[i].transaksi_detail[a].data_barang.nama_barang;
                  html +='&#8226 '+ nama_barang + '<br/>';
                }
                html += '</td>';
                html += '<td>';
                for (let a = 0; a < data[i].transaksi_detail.length; a++) {
                  let jumlah = data[i].transaksi_detail[a].jumlah_barang;
                  html +='&#8226 '+jumlah+'<br/>';
                }
                html += '</td>';
                html += '<td>';
                for (let a = 0; a < data[i].transaksi_detail.length; a++) {
                  let harga = data[i].transaksi_detail[a].harga_barang;
                  html +='&#8226 '+parseInt(harga).toLocaleString()+'<br/>';
                }
                html += '</td>';
                html += '<td>';
                for (let a = 0; a < data[i].transaksi_detail.length; a++) {
                  let sub_total = data[i].transaksi_detail[a].sub_total;
                  html +='&#8226 '+parseInt(sub_total).toLocaleString()+'<br/>';
                }
                html += '</td>';
              }
              else{
                html += '<td colspan="6" style="text-align: center;color: blue;"><b>PEMBAYARAN HUTANG</b></td>';
              }
              if (data[i].hutang == null) {
                html += '<td>'+parseInt(data[i].total_transaksi).toLocaleString()+'</td>';
              }
              if (data[i].pembayaran.jumlah_uang == null) {
                html += '<td>0</td>';
              }else{
                html += '<td>'+parseInt(data[i].pembayaran.jumlah_uang).toLocaleString()+'</td>';
              }
              if (data[i].hutang == null) {
                html += '<td>'+parseInt(data[i].kekurangan).toLocaleString()+'</td>';
              }
            }
            html += '</tr>';
            total_hutang = data[i].kekurangan;
            html += '<tr><td colspan="9" style="text-align: center;"><b>SISA HUTANG</b></td>';
            if (total_hutang == 0) {
              html += '<td>LUNAS</td></tr>'
            }else{
              html += '<td>'+parseInt(total_hutang).toLocaleString()+'</td></tr>'
            }
          }
          $('#tabel_pengeluaran_list').append(html);
        }
      })

    })
  })
</script>