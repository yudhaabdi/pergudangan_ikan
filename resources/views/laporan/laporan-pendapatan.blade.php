@extends('template.main')
@section('title')
    LAPORAN PENDAPATAN
@endsection
@section('jenis_tampilan')
    / LAPORAN PENDAPATAN
@endsection
@section('content')
    <div class="content">
      <form action="{{url('/laporan/laporan-pemasukan/get-data')}}">
        <div class="row">
          <div class="col-md-4">
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
          <table class="table table-bordered data_table" id="tabel_pendapatan">
            <thead>
              <tr>
                  <th>NO</th>
                  <th>Nama Pembeli</th>
                  <th>Tanggal</th>
                  <th>Nama Barang</th>
                  <th>QTY</th>
                  <th>Harga</th>
                  <th>Total Harga</th>
                  <th>Pembayaran</th>
              </tr>
          </thead>
          <tbody id="tabel_pendapatan_list">
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
      $('#tabel_pendapatan_list').empty();
      $('#btn_cetak').removeAttr('hidden');
      nama_pembeli = $('#nama_pembeli').val();
      start_date = $('#start').val();
      end_date = $('#end').val();
      gudang = $('#gudang').val();

      let url = "{{url('/laporan/laporan-pemasukan/get-data')}}";
      $.ajax({
        url: url,
        type: "GET",
        data: {
            nama_pembeli,
            start_date,
            end_date,
            gudang
        },
        success: function(data){
          console.log(data);
          var html = '<tr>';
          var total_pendapatan = 0;
          for (let i = 0; i < data.length; i++) {
            const D = new Date(data[i].created_at);
            let bulan = D.getMonth() + 1;
            let nomor = i+1;

            html += '<td>'+nomor+'</td>';
            html += '<td>'+data[i].daftar_piutang.nama_pembeli+'</td>';
            html += '<td>'+D.getDate()+' - '+bulan+' - '+D.getFullYear()+'</td>';
            
            if (data[i].transaksi.transaksi_detail.length > 0) {
              html += '<td>';
              for (let a = 0; a < data[i].transaksi.transaksi_detail.length; a++) {
                let nama_barang = data[i].transaksi.transaksi_detail[a].data_barang.nama_barang;
                html +='&#8226 '+ nama_barang + '<br/>';
              }
              html += '</td>';
              html += '<td>';
              for (let a = 0; a < data[i].transaksi.transaksi_detail.length; a++) {
                let jumlah = data[i].transaksi.transaksi_detail[a].jumlah_barang;
                html +='&#8226 '+jumlah+'<br/>';
              }
              html += '</td>';
              html += '<td>';
              for (let a = 0; a < data[i].transaksi.transaksi_detail.length; a++) {
                let harga = data[i].transaksi.transaksi_detail[a].harga_barang;
                html +='&#8226 Rp. '+parseInt(harga).toLocaleString()+'<br/>';
              }
              html += '</td>';
              // html += '<td> Rp. '+parseInt(data[i].transaksi.total_transaksi).toLocaleString()+'</td>';
              // html += '<td> Rp. '+parseInt(data[i].jumlah_uang).toLocaleString()+'</td>';
            }else{
              html += '<td>'+data[i].keterangan+'</td>';
              html += '<td>-</td>';
              html += '<td> Rp. '+parseInt(data[i].transaksi.total_transaksi).toLocaleString()+'</td>';
            }
            html += '<td> Rp. '+parseInt(data[i].transaksi.total_transaksi).toLocaleString()+'</td>';
            html += '<td> Rp. '+parseInt(data[i].jumlah_uang).toLocaleString()+'</td>';
            html += '</tr>';
            total_pendapatan = total_pendapatan + data[i].jumlah_uang;
          }
          html += '<tr><td colspan="7" style="text-align: center;">Total Pendapatan</td>';
          html += '<td> Rp. '+parseInt(total_pendapatan).toLocaleString()+'</td></tr>'

          $('#tabel_pendapatan_list').append(html);
        }
      })

    })
  })
</script>