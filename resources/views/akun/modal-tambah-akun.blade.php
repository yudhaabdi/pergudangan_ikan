<!-- Modal -->
<div class="modal fade" id="tambah_akun" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambah Barang</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="{{ url('pengaturan-akun/tambah-akun') }}" method="POST" autocomplete="off" class="form-horizontal" id="formModalTambahAkun">
                {{ csrf_field() }}
                <div class="form-group">
                  <label>Nama</label>
                  <input type="text" class="form-control" placeholder="masukkan nama" name="nama" required>
                </div>
                <div class="form-group">
                  <label>Email</label>
                  <input type="email" class="form-control" placeholder="masukkan email" name="email" required>
                </div>
                <div class="form-group">
                  <label>Role</label>
                  <input type="text" class="form-control" placeholder="masukkan role" name="role" required>
                </div>
                <div class="form-group">
                  <label>Password</label>
                  <input type="text" class="form-control" placeholder="masukkan password" name="password" required>
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