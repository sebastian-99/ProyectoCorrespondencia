<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Correspondencia UTVT</title>

  
 <!---------------------------Reportes-------------------------------------->
 <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link rel="stylesheet" href="https://cdnj.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">

    <link href="//cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" rel="stylesheet">



  <!---------------------------Reportes-------------------------------------->


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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet">

  @yield('header')
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
              <a class="nav-link text-dark"  href="#" role="button">
                  <label for="">Cerrar sesi&oacute;n</label>
                  <i class="fas fa-sign-out-alt"></i>
              </a>
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
              <img src="https://phantom-marca.unidadeditorial.es/252acdd64f48851f815c16049a789f23/resize/1320/f/jpg/assets/multimedia/imagenes/2021/04/19/16188479459744.jpg" class="img-circle img-fluid" alt="User Image" width="150px">
              <a href="#" class="d-block">MT.Carlos millan hidrajosa</a>
              <hr class="bg-secondary">
            </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          {{-- <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Ejemplo
              </p>
            </a>
          </li> --}}
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>Cat&aacute;logos<i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Gesti&oacute;n areas</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>Reportes<i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Reporte oficios</p>
                </a>
              </li>
            </ul>
          </li>
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

<<<<<<< HEAD

<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
=======
<!-- jQuery -->
{{-- <script src="{{asset('src/js/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('src/js/jquery-ui.min.js')}}"></script>
 --}}<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
>>>>>>> dev
<!-- Bootstrap 4 -->
<script src="{{asset('src/js/bootstrap.bundle.min.js')}}"></script>
<!-- Tempusdominus Bootstrap 4
<script src="{{-- asset('src/js/tempusdominus-bootstrap-4.min.js') --}}"></script>
<!-- Summernote
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->

<!-- AdminLTE App -->
<script src="{{asset('src/js/adminlte.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->

<<<<<<< HEAD
 <!---------------------------Reportes-------------------------------------->

 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>

<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script src="//cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
   
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
   



<script type="text/javascript">
  $(function () {
            $.ajaxSetup({
              headers:{ 'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')}
            });

            var table = $('.yajra-datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    rowReorder: {
            selector: 'td:nth-child(2)'
        },
                    responsive: true,
                    ajax: "",
                                    
        columns: [
            {data: 'idac', name: 'idac'},
            {data: 'fecha_creacion', name: 'fecha_creacion'},
            {data: 'asunto', name: 'asunto'},
            {data: null, render: function (data,type, row) {
              return data.nombre+'<br> '+data.app+' '+data.apm;
            }},
            {data: null, render: function (data,type, row) {
              return data.fecha_inicio+'<br> '+data.hora_inicio;
            }},
            {data: null, render: function (data,type, row) {
              return data.fecha_fin+'<br> '+data.hora_fin;
            }},
            {data: 'importancia', name: 'importancia'},
            {data: 'idar_areas', name: 'idar_areas'},
            {data: 'status', name: 'status'},
            {data: null, render: function (data,type,row){
             return data.idu_users;
            }
            },
            {
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false
            },
            
        ],

        "oLanguage": {
          "sSearch": "Buscar _INPUT_",
          "lengthMenu": "Mostrar _MENU_ registros",
                   },
        "language": {
          "lengthMenu": "Mostrar _MENU_ registros",
           "emptyTable":     "No existen registros para mostrar",
           "info":           "Mostrando de _START_ a _END_ de _TOTAL_ registros",
          "infoEmpty":      "Sin registros",
          "processing":     "Cargando...",
         "zeroRecords":    "Ninguna coincidencia encontrada",
         "paginate": {
        "first":      "Primero",
        "last":       "Ultimo",
        "next":       "Siguiente",
        "previous":   "Previa"
                  },
                },
    });
   
  });
  //---------------------------Detalles---------------------------
    $('body').on('click', '.Detalles',function(){
      var id = $(this).data('id');
      console.log(id)
      $.get("Detalles/" + id, function(data){
        
       //alert( JSON.stringify(data,['app']));
 
        $('#modelHeading').html("Detalles");
        $('#ajaxModel').modal('show');
        $('#nombre').val(JSON.stringify(data,['idu_users']));
        $('#idar').val(JSON.stringify(data,['nombre_a']));
        $('#avance').val("");
        $('#status').val(JSON.stringify(data,['status']));
        $('#acuse').val(JSON.stringify(data,['acuse']));

        $('#modelextra').html("ver detalle");
      })
    });
  //---------------------------Detalles---------------------------
</script>
 <!---------------------------Reportes-------------------------------------->
=======
@yield('scripts')

>>>>>>> dev
</body>
</html>

2333221112