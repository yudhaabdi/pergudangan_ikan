@extends('template.main')
@section('title')
    LAPORAN
@endsection
@section('jenis_tampilan')
    / LAPORAN
@endsection
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <a href="{{ url('/laporan/laporan-pemasukan') }}">
                    <div class="card card-stats">
                      <div class="card-body" style="padding-block: 50px">
                        <div class="row">
                            <div style="font-family: 'Courier New', Courier, monospace; font-size: 20px; text-align: center">
                              <p class="card-title">LAPORAN PEMASUKAN<p>
                            </div>
                        </div>
                      </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <a href="{{ url('/laporan/laporan-pengeluaran') }}">
                    <div class="card card-stats">
                      <div class="" style="padding-block: 50px">
                        <div class="row">
                          <div style="font-family: 'Courier New', Courier, monospace; font-size: 20px; text-align: center">
                              <p class="card-title">LAPORAN PENGELUARAN<p>
                            </div>
                        </div>
                      </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <a href="{{ url('/laporan/laporan-hutang') }}">
                    <div class="card card-stats">
                      <div class="card-body" style="padding-block: 50px">
                        <div class="row">
                          <div style="font-family: 'Courier New', Courier, monospace; font-size: 20px; text-align: center">
                              <p class="card-title">LAPORAN PIUTANG<p>
                            </div>
                        </div>
                      </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <a href="{{url('/laporan/laporan-laba-rugi')}}">
                    <div class="card card-stats">
                      <div class="card-body" style="padding-block: 50px">
                        <div class="row">
                          <div style="font-family: 'Courier New', Courier, monospace; font-size: 20px; text-align: center">
                              <p class="card-title">LAPORAN LABA RUGI<p>
                            </div>
                        </div>
                      </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <a href="{{url('/laporan/laporan-data-barang')}}">
                  <div class="card card-stats">
                    <div class="card-body" style="padding-block: 50px">
                      <div class="row">
                        <div style="font-family: 'Courier New', Courier, monospace; font-size: 20px; text-align: center">
                            <p class="card-title">LAPORAN PENGELUARAN BARANG<p>
                          </div>
                      </div>
                    </div>
                  </div>
                </a>
            </div>
          </div>
          </div>
    </div>
@endsection