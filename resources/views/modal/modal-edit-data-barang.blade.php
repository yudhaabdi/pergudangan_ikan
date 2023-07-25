<!-- Modal -->
<div class="modal fade" id="edit_barang" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="nama_modal">Edit Barang</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="" method="POST" autocomplete="off" class="form-horizontal" id="formModalEdit">
                {{ csrf_field() }}
                <input type="text" name="id_barang" id="id_barang" hidden>
                <div class="form-group" id="pemilik">
                  <label>Pemilik Barang</label>
                  <input type="text" required class="form-control" placeholder="masukkan pemilik barang" name="pemilik_barang" id="pemilik_barang">
                </div>
                <div class="form-group" id="form_nama_barang">
                  <label>Nama Barang</label>
                  <input type="text" class="form-control" placeholder="masukkan nama barang" name="nama_barang" id="nama_barang">
                </div>
                <div class="form-group" id="form_size">
                  <label>Size</label>
                  <input type="text" class="form-control" placeholder="masukkan size" name="size" id="size">
                </div>
                <div class="form-group" id="form_kemasan">
                  <label>Kemasan (/Kg)</label>
                  <input type="text" class="form-control" placeholder="masukkan kemasan" name="kemasan" id="kemasan">
                </div>
                <div class="form-group">
                  <label>Jumlah Barang (/Kg)</label>
                  <input type="text" class="form-control" placeholder="masukkan jumlah barang" name="jumlah_barang" id="jumlah_barang">
                </div>
                <div class="form-group" id="form_harga_barang">
                  <label>Harga Barang</label>
                  <input type="text" class="form-control" placeholder="masukkan harga barang" name="harga_barang" id="harga_barang">
                </div>
                <div class="form-group" id="form_kode_barang">
                  <label>Kode Barang</label>
                  <input type="text" class="form-control" placeholder="masukkan kode barang" name="kode_barang" id="kode_barang">
                </div>
                <div class="form-group" id="form_no_kontener">
                  <label>No. Kontener/kendaraan</label>
                  <input type="text" class="form-control" placeholder="masukkan nomor kontener/kendaraan" name="no_kontener" id="no_kontener">
                </div>
                <div id="barang_lama">
                  <div class="form-group">
                    <label>Metode Pembayaran</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input inlineRadio1" type="radio" name="metode_bayar" id="tunai" value="1">
                    <label class="form-check-label" for="inlineRadio1">Tunai</label>
                  </div>
                  <div class="form-check form-check-inline" style="margin-left: 20px">
                      <input class="form-check-input inlineRadio2" type="radio" name="metode_bayar" id="tranfer" value="2">
                      <label class="form-check-label" for="inlineRadio2">Tranfer</label>
                  </div>
                  <div class="form-check form-check-inline" style="margin-left: 20px">
                    <input class="form-check-input inlineRadio3" type="radio" name="metode_bayar" id="hutang" value="3">
                    <label class="form-check-label" for="inlineRadio2">Hutang</label>
                  </div>
  
                  <div class="form-group">
                    <label class="nama_bank" hidden>Masukkan Nama Bank</label>
                    <input type="text" class="form-control nama_bank" name="nama_bank" id="nama_bank" placeholder="Masukkan nama bank" hidden>
                  </div>
                  <div class="form-group">
                    <label>Pembayaran</label>
                    <input type="text" class="form-control jumlah_uang" placeholder="masukkan jumlah uang" name="jumlah_uang" id="jumlah_uang">
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                  <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>