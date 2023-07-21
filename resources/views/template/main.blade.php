<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="{{asset('assets/img/apple-icon.png')}}">
  <link rel="icon" type="image/png" href="{{asset('assets/img/favicon.png')}}">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    @yield('title')
  </title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
  <!-- CSS Files -->
  <link href="{{asset ('assets/css/bootstrap.min.css')}}" rel="stylesheet"/>
  {{-- <link href="../assets/css/bootstrap.min.css" rel="stylesheet" /> --}}
  <link href="{{asset ('assets/css/paper-dashboard.css?v=2.0.1')}}" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="{{asset ('assets/demo/demo.css')}}" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
  {{-- select2 --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
  {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"> --}}
  {{-- data table --}}
  {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"> --}}
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css"></head>

<body class="">
  <div class="wrapper ">
    <div class="sidebar" data-color="red">
      <div class="logo">
        <a href="{{ url('/') }}" class="simple-text logo-mini">
          <div class="logo-image-small">
            <img src="{{asset('assets/img/logo-small.png')}}">
          </div>
          <!-- <p>CT</p> -->
        </a>
        <a href="{{ url('/') }}" class="simple-text logo-normal">
          CV. BERKAH RESTU IBU
          <!-- <div class="logo-image-big">
            <img src="../assets/img/logo-big.png">
          </div> -->
        </a>
      </div>
      <div class="sidebar-wrapper">
        @include('template.menu')
      </div>
    </div>
    <div class="main-panel" style="height: max-content;">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <div class="navbar-toggle">
              <button type="button" class="navbar-toggler">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
              </button>
            </div>
            <h1 class="navbar-brand">PERGUDANGAN IKAN
              @yield('jenis_tampilan')
          </div>
        </div>
        @if (Auth::User()->role == 'admin')
          <select name="gudang" class="form-control" style="width: 100px; margin-right: 10px;" id="gudang" data-url="{{ url('/setting') }}">
            <option value="1" @if (session('gudang') == 1) selected @endif>Gudang 1</option>
            <option value="2" @if (session('gudang') == 2) selected @endif>Gudang 2</option>
          </select>
        @endif
        <a href="{{route('logoutaksi')}}" class="btn btn-light">Keluar</a>
      </nav>
      <!-- End Navbar -->
      @yield('content')
      
      {{-- <footer class="footer footer-black  footer-white ">
        <div class="container-fluid">
          <div class="row">
            <nav class="footer-nav">
              <ul>
                <li><a href="https://www.creative-tim.com" target="_blank">Creative Tim</a></li>
                <li><a href="https://www.creative-tim.com/blog" target="_blank">Blog</a></li>
                <li><a href="https://www.creative-tim.com/license" target="_blank">Licenses</a></li>
              </ul>
            </nav>
            <div class="credits ml-auto">
              <span class="copyright">
                © <script>
                  document.write(new Date().getFullYear())
                </script>, made with <i class="fa fa-heart heart"></i> by Creative Tim
              </span>
            </div>
          </div>
        </div>
      </footer> --}}
    </div>
  </div>
  @yield('script')
  <!--   Core JS Files   -->
  <script src="{{asset('assets/js/core/jquery.min.js')}}"></script>
  <script src="{{asset('assets/js/core/popper.min.js')}}"></script>
  <script src="{{asset('assets/js/core/bootstrap.min.js')}}"></script>
  <script src="{{asset('assets/js/plugins/perfect-scrollbar.jquery.min.js')}}"></script>
  <!-- Chart JS -->
  <script src="{{asset('assets/js/plugins/chartjs.min.js')}}"></script>
  <!--  Notifications Plugin    -->
  <script src="{{asset('assets/js/plugins/bootstrap-notify.js')}}"></script>
  <!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{asset('assets/js/paper-dashboard.min.js?v=2.0.1')}}" type="text/javascript"></script><!-- Paper Dashboard DEMO methods, don't include it in your project! -->
  <script src="{{asset('assets/demo/demo.js')}}"></script>
  {{-- select2 --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
  {{-- data table --}}
  {{-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> --}}
  <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.2/js/dataTables.bootstrap4.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  {{-- date range --}}
  {{-- <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css"/> --}}

  <script>
    $(document).ready(function () {
      $('.data_table').DataTable();
    });
  </script>

  <script>
    $(document).ready(function() {
      demo.initChartsPages();
    });
  </script>
  <script>
    $(document).ready(function(){
          $('.select2').select2({
            placeholder : 'pilih salah satu',
                tags: true
          });
       });

    $('body').on('change', '#gudang',function(e){
      let url =  $(this).data('url');
      var gudang = $('#gudang').val();
      console.log(gudang);

      getSetting(url, gudang)
    });

    function getSetting(url, gudang){
      $.ajax({
        type: "GET",
            url: url,
            data: {
              gudang
            },
            success: function(data){
              Swal.fire({
                  type: 'success',
                  icon: 'success',
                  title: `${data.message}`,
                  showConfirmButton: false,
                  timer: 3000
              });
              setTimeout(function() { 
                window.location.reload(true);
              }, 3000);
            }
      })
    }
</script>
</body>

</html>