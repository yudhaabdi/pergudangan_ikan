<!-- Modal -->
<div class="modal fade" id="tambah_barang" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambah Pendapatan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="{{ url('pendapatan-lain/tambah') }}" method="POST" autocomplete="off" class="form-horizontal" id="formModalTambah">
                {{ csrf_field() }}
                <div class="form-group">
                  <label>Nama Pembali</label>
                  <input type="text" class="form-control" placeholder="masukkan nama pembeli" name="nama_pembeli">
                </div>
                <div class="form-group">
                  <label>Keterangan</label>
                  <input type="text" class="form-control" placeholder="" name="keterangan">
                </div>
                <div class="form-group">
                  <label>Metode Pembayaran</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input inlineRadio1" type="radio" name="metode" id="inlineRadio1" value="1" checked>
                  <label class="form-check-label" for="inlineRadio1">Tunai</label>
                </div>
                <div class="form-check form-check-inline" style="margin-left: 20px">
                    <input class="form-check-input inlineRadio2" type="radio" name="metode" id="inlineRadio2" value="2">
                    <label class="form-check-label" for="inlineRadio2">Tranfer</label>
                </div>
                {{-- <div class="form-check form-check-inline" style="margin-left: 20px">
                    <input class="form-check-input inlineRadio3" type="radio" name="metode" id="inlineRadio3" value="3">
                    <label class="form-check-label" for="inlineRadio2">Hutang</label>
                </div> --}}
                <div class="form-group">
                  <input type="text" class="form-control nama_bank" name="nama_bank" placeholder="Masukkan nama bank" hidden>
                </div>
                <div class="form-group">
                    <label>Jumlah Uang</label>
                    <input type="text" class="form-control" placeholder="masukkan jumlah uang" name="jumlah_uang" id="jumlah_uang">
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
  