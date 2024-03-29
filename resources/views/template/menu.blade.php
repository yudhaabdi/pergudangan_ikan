<style>
  .bg-success {
  --bs-bg-opacity: 1;
  background-color: rgba(var(--bs-success-rgb), var(--bs-bg-opacity)) !important;
}
</style>
<ul class="nav">
          <li>
            <a href="{{url('/')}}">
              <i class="nc-icon nc-bank"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li>
            <a href="{{url('/transaksi')}}">
              <i class="nc-icon nc-cart-simple"></i>
              <p>TRANSAKSI</p>
            </a>
          </li>
          @if (Auth::User()->role == 'admin')
            <li>
              <a href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                <i class="nc-icon nc-basket"></i>
                <p>DATA BARANG</p>
              </a>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ url('/data-barang/1') }}">Gudang 1</a>
                <a class="dropdown-item" href="{{ url('/data-barang/2') }}">Gudang 2</a>
              </div>
            </li>
          @else
            @if (Auth::User()->role == 'kasir 1')
              <li>
                <a href="{{ url('/data-barang/1') }}">
                  <i class="nc-icon nc-basket"></i>
                  <p>DATA BARANG</p>
                </a>
              </li>
            @else
              <li>
                <a href="{{ url('/data-barang/2') }}">
                  <i class="nc-icon nc-basket"></i>
                  <p>DATA BARANG</p>
                </a>
              </li>
            @endif
          @endif
          {{-- <li>
            <a href="{{ url('/data-barang') }}">
              <i class="nc-icon nc-basket"></i>
              <p>DATA BARANG</p>
            </a>
          </li> --}}
          <li>
            <a href="{{ url('/transaksi-detail') }}">
              <i class="nc-icon nc-cart-simple"></i>
              <p>TRANSAKSI DETAIL</p>
            </a>
          </li>
          @if (Auth::User()->role == 'admin')
            <li>
              <a href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                <i class="nc-icon nc-money-coins"></i>
                <p>DATA PIUTANG</p>
              </a>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ url('/hutang') }}">PIUTANG PEGAWAI / PEMBELI</a>
                <a class="dropdown-item" href="{{ url('/hutang/perusahaan') }}">PIUTANG PERUSAHAAN</a>
              </div>
            </li>
          @endif
          <li>
            <a href="{{ url('/pendapatan-lain') }}">
              <i class="nc-icon nc-money-coins"></i>
              <p>Pendapatan Lain - Lain</p>
            </a>
          </li>
          <li>
            <a href="{{ url('/pengeluaran-lain') }}">
              <i class="nc-icon nc-money-coins"></i>
              <p>Pengeluaran Lain - Lain</p>
            </a>
          </li>
          <li>
            <a href="{{ url('/laporan') }}">
              <i class="nc-icon nc-single-copy-04"></i>
              <p>LAPORAN</p>
            </a>
          </li>
          @if (Auth::User()->role == 'admin')
          <li>
            <a href="{{ url('/data-karyawan') }}">
              <i class="nc-icon nc-single-02"></i>
              <p>Data Karyawan</p>
            </a>
          </li>
            <li>
              <a href="{{ url('/pengaturan-akun') }}">
                <i class="nc-icon nc-single-02"></i>
                <p>Pengaturan Akun</p>
              </a>
            </li>
          @endif
          {{-- <li>
            <a href="./notifications.html">
              <i class="nc-icon nc-bell-55"></i>
              <p>PENGATURAN AKUN</p>
            </a>
          </li> --}}
</ul>