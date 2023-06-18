@extends('template.main')
@section('title')
    LAPORAN PENGELUARAN
@endsection
@section('jenis_tampilan')
    / LAPORAN PENGELUARAN
@endsection
@section('content')
    <div class="content">
      <form action="{{url('/laporan/laporan-pengeluaran/get-data')}}">
        <div class="row">
          <div class="col-md-6">
            <input type="text" name="print" hidden value="print">
            <div class="form-group">
              <label>Masukkan Nama</label>
              <select class="form-control select2" name="nama_pembeli" id="nama_pembeli">
                <option value="all">semua</option>
                <option value="pabrik">Pabrik</option>
                <option value="penyusutan">Penyusutan</option>
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
          <table class="table table-bordered" id="tabel_pengeluaran">
            <thead>
              <tr>
                  <th>NO</th>
                  <th>Nama</th>
                  <th>Tanggal</th>
                  <th>Nama Barang</th>
                  <th>QTY</th>
                  <th>Harga</th>
                  <th>Total Harga</th>
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

      let url = "{{url('/laporan/laporan-pengeluaran/get-data')}}";
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
          var total_pengeluaran = 0;
          var qty = 0;
          for (let i = 0; i < data.length; i++) {
            const D = new Date(data[i].created_at);
            let bulan = D.getMonth() + 1;
            let nomor = i+1;
  
            html += '<td>'+nomor+'</td>';
            if(data[i].id_data_barang != null && data[i].transaksi.penyusutan == 1){
              html += '<td>Penyusutan</td>';
            }else if (data[i].id_piutang == null) {
              html += '<td>Pengeluaran Pabrik</td>';
            }
            else{
              html += '<td>'+data[i].daftar_piutang.nama_pembeli+'</td>';
            }
            html += '<td>'+D.getDate()+' - '+bulan+' - '+D.getFullYear()+'</td>';
            if (data[i].id_data_barang == null) {
              html += '<td>'+data[i].keterangan+'</td>';
            }else{
              html += '<td>'+data[i].data_barang.nama_barang+'</td>';
            }
            if (data[i].id_data_barang != null && data[i].transaksi.penyusutan == null) {
              qty = data[i].transaksi.qty;
              html += '<td>'+parseInt(qty).toLocaleString()+'</td>';
              html += '<td>Rp. '+parseInt(data[i].data_barang.harga_barang).toLocaleString()+'</td>';
            }if(data[i].id_data_barang != null && data[i].transaksi.penyusutan == 1){
              qty = data[i].jumlah_uang / data[i].data_barang.harga_barang;
              html += '<td>'+parseInt(qty).toLocaleString()+'</td>';
              html += '<td>Rp. '+parseInt(data[i].data_barang.harga_barang).toLocaleString()+'</td>';
            }
            if(data[i].lain_lain != null && data[i].id_data_barang == null){
              html += '<td></td><td></td>';
            }
            html += '<td>Rp. '+parseInt(data[i].transaksi.total_transaksi).toLocaleString()+'</td>'
            html += '</tr>';
            total_pengeluaran = total_pengeluaran + data[i].transaksi.total_transaksi;
          }
          html += '<tr><td colspan="6" style="text-align: center;">Total Pengeluaran</td>';
          html += '<td> Rp. '+parseInt(total_pengeluaran).toLocaleString()+'</td></tr>'
          $('#tabel_pengeluaran_list').append(html);
        }
      })

    })
  })
</script>