<!-- Modal -->
<div class="modal fade" id="edit_supplier" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Supplier</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="{{ url('supplier/tambah-supplier') }}" method="POST" autocomplete="off" class="form-horizontal" id="formModalEdit">
                {{ csrf_field() }}
                <div class="form-group">
                  <label>Nama Supplier</label>
                  <input type="text" class="form-control" placeholder="masukkan nama supplier" id="nama" name="nama" required>
                </div>
                <div class="form-group">
                  <label>No. Hp</label>
                  <input type="text" class="form-control" placeholder="masukkan no. hp" id="no_hp" name="no_hp" required>
                </div>
                <div class="form-group">
                  <label>Alamat</label>
                </div>
                <textarea name="alamat" cols="30" rows="2" style="width: 100%" id="alamat" required></textarea>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>