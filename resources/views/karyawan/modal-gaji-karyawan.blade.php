<!-- Modal -->
<div class="modal fade" id="gaji_karyawan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Pembayaran Pegawai</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="{{ url('data-karyawan/tambah') }}" method="POST" autocomplete="off" class="form-horizontal" id="formModalTambah">
                {{ csrf_field() }}
                <div class="form-group">
                <label>Metode Pembayaran</label>
                </div>
                <div class="form-check form-check-inline">
                <input class="form-check-input inlineRadio1" type="radio" name="metode" id="" value="1" checked>
                <label class="form-check-label" for="inlineRadio1">Tunai</label>
                </div>
                <div class="form-check form-check-inline" style="margin-left: 100px">
                    <input class="form-check-input inlineRadio2" type="radio" name="metode" id="" value="2">
                    <label class="form-check-label" for="inlineRadio2">Tranfer</label>
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