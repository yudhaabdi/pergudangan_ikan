<!-- Modal -->
<div class="modal fade" id="tambah_hutang" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambah Hutang</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="" method="GET" autocomplete="off" class="form-horizontal" id="formModalTambahHutang">
                {{ csrf_field() }}
                <div class="form-group">
                  <label>Nama</label>
                  <input type="text" class="form-control" placeholder="masukkan nama" name="nama_pembeli">
                </div>
                <div class="form-group">
                  <label>Jumlah Uang</label>
                  <input type="text" class="form-control inputmask" placeholder="masukkan jumlah uang" name="jumlah_uang"
                    autocomplete="off"
                    data-inputmask="'alias': 'numeric','digits': 2,'groupSeparator':',', 'autoGroup' : true,
                    'removeMaskOnSubmit': true, 'autoUnmask': true"
                  >
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>