<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Correspondencia UTVT</title>




  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  {{-- <link rel="stylesheet" href="{{asset('src/css/all.min.css')}}"> --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="{{asset('src/css/tempusdominus-bootstrap-4.min.css')}}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{asset('src/css/icheck-bootstrap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('src/css/adminlte.min.css')}}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{asset('src/css/OverlayScrollbars.min.css')}}">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{asset('src/css/custom.css')}}">
  <!-- Helpers JS -->
  <script src="{{ asset('src/js/helpers.js') }}"></script>
  {{-- Modal --}}
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet">

  @yield('header')
</head>
<body class="hold-transition skin-yellow sidebar-mini">
<div class="wrapper">
  <head class="main-header">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light" style="background-color: #1c9842">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
          </ul>
          <h3 class="text-light">Universidad Tecnol&oacute;gica del Valle de Toluca</h3>
          <!-- logout -->
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type ="submit" class="btn btn-primary"><i class="fas fa-sign-out-alt"></i> Cerrar sesi&oacute;n</button>
              </a>
            </form>
            </li>
          </ul>
        </nav>
      </head>
  <img src="{{asset('images/BarraColores.png')}}" width="100%" height="10px">







  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link text-center">
      <img src="{{asset('images/logoUTVT.png')}}" width="70%">
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="mt-3 pb-3 mb-3">
        <div class="text-center">
            <div >
              <img src="{{asset('storage/imagenes_perfil') .'/' . Auth()->user()->imagen }}" class="img-circle img-fluid" alt="User Image" width="150px">
              <a href="{{url('/panel')}}" class="d-block">{{Auth()->user()->titulo . ' ' . Auth()->user()->nombre . ' '  .Auth()->user()->app . ' ' . Auth()->user()->apm}}</a>
              <hr class="bg-secondary">
            </div>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
            <a href="{{ url('panel') }}" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Inicio
              </p>
            </a>
          </li>
        @if(Auth()->user()->idtu_tipos_usuarios == 1)
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="fas fa-users-cog"></i>
              <p>Administraci&oacute;n<i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('tipos-actividades')}}" class="nav-link">
                  <i class="nav-icon fas fa-edit"></i>
                  <p>Tipos de actividades</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ url('areas')}}" class="nav-link">
                  <i class="nav-icon fas fa-edit"></i>
                  <p>Áreas</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ url('users')}}" class="nav-link">
                  <i class="fas fa-user"></i>
                  <p>Usuarios</p>
                </a>
            </li>
            </ul>
          </li>
        @endif
        @if(Auth()->user()->idtu_tipos_usuarios != 1)
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>Actividades<i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('actividades')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Asignar actividad</p>
                </a>
              </li>
              @if(Auth()->user()->idtu_tipos_usuarios == 3)
              <li class="nav-item">
                <a href="{{ url('reporte_actividades')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ver actividades</p>
                </a>
              </li>
              @endif
              <li class="nav-item">
                <a href="{{ url('actividades_asignadas', ['id'=>encrypt(Auth()->user()->idu)])}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ver actividades asignadas</p>
                </a>
              </li>
            </ul>
          </li>
          
          @endif
          @if(Auth()->user()->idtu_tipos_usuarios == 1)
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>Actividades<i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('reporte_actividades')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ver actividades</p>
                </a>
              </li>
            </ul>
          </li>
          @endif
          @if(Auth()->user()->idtu_tipos_usuarios == 1 || Auth()->user()->idtu_tipos_usuarios == 3)
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="fas fa-chart-pie"></i>
              <p>Gr&aacute;ficas</p>
              <i class="fas fa-angle-left right"></i>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('admin/actividades/dashboard')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Gr&aacute;ficas seguimientos</p>
                </a>
              </li>
            </ul>
          </li>
          @endif
        </ul>
        <div class="text-center">
          <img src="{{asset('images/M-Edomex.png')}}" alt="" width="60%" class="mt-4">
        </div>

      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          @yield('content')
        </div>
      </section>

    </div>

    <footer class="main-footer">
      <strong>Universidad Tecnol&oacute;gica del Valle de Toluca - Correspondencia</strong>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->


  <!-- Bootstrap 4 -->
  <script src="{{asset('src/js/bootstrap.bundle.min.js')}}"></script>
  <!-- Tempusdominus Bootstrap 4-->
{{-- <script src="asset('src/js/tempusdominus-bootstrap-4.min.js')"></script> --}}
<!-- Summernote-->
{{-- <script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="{{asset('src/js/jquery.overlayScrollbars.min.js')}}"></script> --}}
<!-- AdminLTE App -->
<script src="{{asset('src/js/adminlte.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->

 <!-- Modal -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>



  @yield('scripts')

</body>

</html>
