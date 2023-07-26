<!-- Modal -->
<div class="modal fade" id="bayar_hutang" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Bayar Hutang</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="" method="POST" autocomplete="off" class="form-horizontal" id="formModalHutang">
                {{ csrf_field() }}
                <div class="form-group">
                  <label>Metode Pembayaran</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="metode" id="inlineRadio1" value="1" checked>
                    <label class="form-check-label" for="inlineRadio1">Tunai</label>
                </div>
                <div class="form-check form-check-inline" style="margin-left: 100px">
                    <input class="form-check-input" type="radio" name="metode" id="inlineRadio2" value="2">
                    <label class="form-check-label" for="inlineRadio2">Tranfer</label>
                </div>

                <div class="form-group">
                  <label class="nama_bank" hidden>Masukkan Nama Bank</label>
                  <input type="text" class="form-control nama_bank" name="nama_bank" placeholder="Masukkan nama bank" hidden>
                </div>
                <div class="form-group">
                  <label>Masukkan Jumlah Uang</label>
                  <input type="text" class="form-control inputmask" placeholder="masukkan jumlah uang" name="bayar"
                    autocomplete="off"
                    data-inputmask="'alias': 'numeric','digits': 2,'groupSeparator':',', 'autoGroup' : true,
                    'removeMaskOnSubmit': true, 'autoUnmask': true"
                  >
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                  <button type="submit" class="btn btn-primary">Bayar</button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>

  <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
  <script>
      $(document).ready(function(){
          $("#inlineRadio1").click(function(){
              $(".nama_bank").attr("hidden",true);
              $(".nama_bank").attr("required",false);
              $(".nama_bank").val('');
          });
          $("#inlineRadio2").click(function(){
              $(".nama_bank").removeAttr('hidden');
              $(".nama_bank").attr("required",true);
          });
      });
  </script>