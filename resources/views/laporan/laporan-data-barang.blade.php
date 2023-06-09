@extends('template.main')
@section('title')
    LAPORAN DATA BARANG
@endsection
@section('jenis_tampilan')
    / LAPORAN DATA BARANG
@endsection
@section('content')
    <div class="content">
        <form action="javascript:void(0)">
            <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Masukkan Nama Barang</label>
                    <select class="form-control select2" name="nama_barang" id="nama_barang" required>
                      <option value="">semua</option>
                      @foreach ($nama as $key => $item)
                          <option value="{{$item->id}}"> {{$item->nama_barang}} / {{$item->size}} / &#64;{{$item->kemasan}} / {{$item->kode}}</option>
                      @endforeach
                    </select>
                    <small style="color: red" hidden id="validasi">! masukkan nama barang !</small>
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
        </form>
        <button class="btn btn-primary" style="width: 100%" id="btn_cari">CARI</button>
        <div class="row">
          <table class="table table-bordered data_table" id="tabel_barang">
            <thead>
              <tr>
                  <th>NO</th>
                  <th>Nama Barang</th>
                  <th>Kode</th>
                  <th>No. Kontener</th>
                  <th>Tanggal</th>
                  <th>QTY</th>
                  <th>Harga Beli</th>
                  <th>Harga Jual</th>
                  <th>Keterangan</th>
              </tr>
          </thead>
          <tbody id="tabel_barang_list">
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
      nama = $('#nama_barang').val();
      start = $('#start').val();
      end = $('#end').val();
      let url = "{{url('/laporan/laporan-data-barang/get-data')}}";
      if (nama != '') {
        $('#validasi').attr('hidden', true);
        pencarian(nama, start, end, url);
      }else{
        $('#validasi').removeAttr('hidden');
      }

    })

    function pencarian(nama, start, end, url){
        $.ajax({
            url: url,
            type: "GET",
            data: {
                nama,
                start,
                end
            },
            success: function(data){
                console.log(data);
                var html = '<tr>';
                var total_pendapatan = 0;
                for (let i = 0; i < data.length; i++) {
                    const D = new Date(data[i].created_at);
                    let bulan = D.getMonth() + 1;
                    let nomor = i+1;
                    // '<td></td>'

                    html += '<td>'+nomor+'</td>';
                    if (data[i].daftar_piutang == null) {
                        html += '<td>'+data[i].pembayaran.data_barang.nama_barang+'</td>';
                        if (data[i].pembayaran.data_barang.kode == null) {
                            html += '<td>-</td>';
                        }else{
                            html += '<td>'+data[i].pembayaran.data_barang.kode+'</td>';
                        }
                        if (data[i].pembayaran.data_barang.no_kontener == null) {
                            html += '<td>-</td>';
                        }else{
                            html += '<td>'+data[i].pembayaran.data_barang.no_kontener+'</td>';
                        }
                        html += '<td>'+D.getDate()+' - '+bulan+' - '+D.getFullYear()+'</td>';
                        html += '<td>'+parseInt(data[i].total_transaksi / data[i].pembayaran.data_barang.harga_barang).toLocaleString()+'</td>';
                        html += '<td>'+parseInt(data[i].pembayaran.data_barang.harga_barang).toLocaleString()+'</td>';
                        html += '<td>-</td>';
                        html += '<td>Barang Masuk</td>';
                    }else{
                        for (let a = 0; a < data[i].transaksi_detail.length; a++) {
                            if (data[i].transaksi_detail[a].id_data_barang == nama) {
                                html += '<td>'+data[i].transaksi_detail[a].data_barang.nama_barang+'</td>';
                                if (data[i].transaksi_detail[a].data_barang.kode == null) {
                                    html += '<td>-</td>';
                                }else{
                                    html += '<td>'+data[i].transaksi_detail[a].data_barang.kode+'</td>';
                                }
                                if (data[i].transaksi_detail[a].data_barang.no_kontener == null) {
                                    html += '<td>-</td>';
                                }else{
                                    html += '<td>'+data[i].transaksi_detail[a].data_barang.no_kontener+'</td>';
                                }
                                html += '<td>'+D.getDate()+' - '+bulan+' - '+D.getFullYear()+'</td>';
                                html += '<td>'+parseInt(data[i].transaksi_detail[a].jumlah_barang).toLocaleString()+'</td>';
                                html += '<td>'+parseInt(data[i].transaksi_detail[a].data_barang.harga_barang).toLocaleString()+'</td>';
                                html += '<td>'+parseInt(data[i].transaksi_detail[a].harga_barang).toLocaleString()+'</td>';
                                html += '<td>Barang Keluar</td>';
                            }
                        }
                    }
                    html += '</tr>';
                }

            $('#tabel_barang_list').append(html);
            }
        })
    }
    
  })
</script>