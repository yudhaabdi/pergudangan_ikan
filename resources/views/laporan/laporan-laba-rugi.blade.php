@extends('template.main')
@section('title')
    LAPORAN KEUNTUNGAN BERSIH
@endsection
@section('jenis_tampilan')
    / LAPORAN KEUNTUNGAN BERSIH
@endsection
@section('content')
      <div class="content">
        <form action="{{url('/laporan/laporan-laba-rugi/get-data')}}">
          <div class="row">
            <div class="col-md-6">
              <input type="text" name="print" hidden value="print">
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
          <table class="table table-bordered" id="tabel_laba_rugi" style="font-size: 15px">
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
                  <th>Keterangan</th>
              </tr>
          </thead>
          <tbody id="tabel_laba_rugi_list">
          </tbody>
          </table>
        </div>
    </div>
@endsection
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
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
      $('#tabel_laba_rugi_list').empty();
      $('#btn_cetak').removeAttr('hidden');
      let start = $('#start').val();
      let end = $('#end').val();
      gudang = $('#gudang').val();
      let url = "{{url('/laporan/laporan-laba-rugi/get-data')}}";

      pencarian(start, end, url);
    })
    
    function pencarian(start_date, end_date, url) {
      $.ajax({
        url: url,
        type: "GET",
        data: {
          start_date,
          end_date,
          gudang
        },
        success: function(data){
          console.log(data);
  
          var html = '<tr>';
          var pendapatan = 0;
          var pengeluaran = 0;
          var laba_bersih = 0;
          for (let i = 0; i < data.length; i++) {
            const D = new Date(data[i].created_at);
            let bulan = D.getMonth() + 1;
            let nomor = i+1;
  
            html += '<td>'+nomor+'</td>';
            if (data[i].daftar_piutang == null) {
              html += '<td>Pabrik</td>'
            }else{
              html += '<td>'+data[i].daftar_piutang.nama_pembeli+'</td>';
            }
            html += '<td>'+D.getDate()+' - '+bulan+' - '+D.getFullYear()+'</td>';
            if (data[i].pembayaran.lain_lain == 2 || data[i].pembayaran.lain_lain == 1) {
              html += '<td colspan="5" style="text-align: center;">'+data[i].pembayaran.keterangan+'</td>';
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
                html += '<td>'+parseInt(data[i].total_transaksi).toLocaleString()+'</td>'
              }
              else{
                if (data[i].hutang == 1) {
                  html += '<td colspan="5" style="text-align: center;">PEMBAYARAN HUTANG</td>';
                }else{
                  html += '<td colspan="5" style="text-align: center;">'+data[i].pembayaran.keterangan+'</td>';
                }
              }
            }
            html += '<td>'+parseInt(data[i].pembayaran.jumlah_uang).toLocaleString()+'</td>';
            if (data[i].pembayaran.pendapatan == 1) {
              pendapatan = pendapatan + data[i].pembayaran.jumlah_uang;
              html += '<td style="color:blue">Pemasukan</td>';
            } else {
              pengeluaran = pengeluaran + data[i].pembayaran.jumlah_uang;
              html += '<td style="color:red">Pengeluaran</td>';
            }
            html += '</tr>';
          }
          laba_bersih = pendapatan - pengeluaran; 
          html += '<tr><td colspan="8" style="text-align: center;"><b>TOTAL LABA BERSIH</b></td>';
          html += '<td><b>Rp. '+parseInt(laba_bersih).toLocaleString()+'</b></td></tr>'
          $('#tabel_laba_rugi_list').append(html);
        }
      })
    }
  })
</script>