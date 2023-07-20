<!-- Modal -->
<div class="modal fade" id="modal_barang_lama" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
            <form action="{{ url('data-barang/'.$gudang.'/tambah-barang-lama') }}" method="POST" autocomplete="off" class="form-horizontal" id="formModalTambahBarangLama">
                {{ csrf_field() }}
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
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>