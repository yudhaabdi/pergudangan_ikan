<!-- Modal -->
<div class="modal fade" id="edit_karyawan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambah Barang</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="" method="POST" autocomplete="off" class="form-horizontal" id="formModalEdit">
                {{ csrf_field() }}
                <div class="form-group">
                  <label>Nama Pegawai</label>
                  <input type="text" class="form-control" placeholder="masukkan nama pegawai" name="nama" id="nama">
                </div>
                <div class="form-group">
                  <label>Alamat</label>
                  <input type="text" class="form-control" placeholder="masukkan alamat" name="alamat" id="alamat">
                </div>
                <div class="form-group">
                  <label>No. HP</label>
                  <input type="number" class="form-control" placeholder="masukkan nomor hp" name="no_hp" id="no_hp">
                </div>
                <div class="form-group">
                  <label>Gaji</label>
                  <input type="text" class="form-control inputmask" placeholder="masukkan gaji" name="gaji" id="gaji"
                    autocomplete="off"
                    data-inputmask="'alias': 'numeric','digits': 2,'groupSeparator':',', 'autoGroup' : true,
                    'removeMaskOnSubmit': true, 'autoUnmask': true"
                  >
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