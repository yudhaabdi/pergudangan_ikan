<!-- Modal -->
<div class="modal fade" id="tambah_barang" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambah Barang</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          @php
              $url = URL::full();
              $gudang = substr($url, -1);
          @endphp
            <form action="{{ url('data-barang/'.$gudang.'/tambah') }}" method="POST" autocomplete="off" class="form-horizontal" id="formModalTambah">
                {{ csrf_field() }}
                <div class="form-group">
                  <label>Pemilik Barang</label>
                  <input type="text" class="form-control" placeholder="masukkan pemilik barang" name="pemilik_barang" required>
                </div>
                <div class="form-group">
                  <label>Nama Barang</label>
                  <input type="text" class="form-control" placeholder="masukkan nama barang" name="nama_barang" required>
                </div>
                <div class="form-group">
                  <label>Size</label>
                  <input type="text" class="form-control" placeholder="masukkan size" name="size">
                </div>
                <div class="form-group">
                  <label>Kemasan (/Kg)</label>
                  <input type="text" class="form-control" placeholder="masukkan kemasan" name="kemasan">
                </div>
                <div class="form-group">
                  <label>Jumlah Barang (/Kg)</label>
                  <input type="text" class="form-control" placeholder="masukkan jumlah barang" name="jumlah_barang" required>
                </div>
                <div class="form-group">
                  <label>Harga Barang</label>
                  <input type="text" class="form-control" placeholder="masukkan harga barang" name="harga_barang" required>
                </div>
                <div class="form-group">
                  <label>Kode Barang</label>
                  <input type="text" class="form-control" placeholder="masukkan kode barang" name="kode_barang">
                </div>
                <div class="form-group">
                  <label>No. Kontener/kendaraan</label>
                  <input type="text" class="form-control" placeholder="masukkan nomor kontener/kendaraan" name="no_kontener">
                </div>
                <div class="form-group">
                  <label>Metode Pembayaran</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input inlineRadio1" type="radio" name="metode_bayar" id="" value="1" checked>
                  <label class="form-check-label" for="inlineRadio1">Tunai</label>
              </div>
              <div class="form-check form-check-inline" style="margin-left: 20px">
                  <input class="form-check-input inlineRadio2" type="radio" name="metode_bayar" id="" value="2">
                  <label class="form-check-label" for="inlineRadio2">Tranfer</label>
              </div>
              <div class="form-check form-check-inline" style="margin-left: 20px">
                  <input class="form-check-input inlineRadio3" type="radio" name="metode_bayar" id="" value="3">
                  <label class="form-check-label" for="inlineRadio2">Hutang</label>
              </div>

              <div class="form-group">
                  <input type="text" class="form-control nama_bank" name="nama_banK" id="" placeholder="Masukkan nama bank" hidden>
              </div>
              <div class="form-group">
                  <label>Pembayaran</label>
                  <input type="text" class="form-control jumlah_uang" placeholder="masukkan jumlah uang" name="jumlah_uang" id="">
              </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>