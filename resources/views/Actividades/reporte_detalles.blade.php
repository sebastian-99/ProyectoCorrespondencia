@extends('layout.layout')
@section('content')
@section('header')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
@endsection
<div class="card">
<div class="card-header">
      <div class="row">
        <div class="col-sm-11">
          <h3>Reporte de actividades / oficios</h3>
        </div>
        <div class="col-sm-2">
        <a href="#" onclick="javascritp:window.self.close();"><button class="btn btn-danger">Cerrar</button></a>
        </div>
      </div>
      
    </div>
    <div class="card-body">
                <div class="table-responsive">
        <table class="table table-striped table-bordered" id="tabla">
            <thead class="text-center">
              <tr style="background-color: #1F75FE; color: #ffffff">
                <th scope="col">Nombre atendio</th>
                <th scope="col">Cargo</th>
                <th scope="col">Avance</th>
                <th scope="col">Status Atenci&aacute;n</th>
                <th scope="col">Acuse recibido</th>
                <th scope="col">Operaciones</th>
                
              </tr>
            </thead>
            <tbody>
            @foreach ($query as $c)
              <tr class="text-center">
               
              <td>{{$c->nombre_us}}</td>
                <td>{{$c->nombre_ar}}</td>
                <td>{{$c->porcentaje}}</td>
                <td>{{$c->estado}}</td>
                <td>{{$c->acuse}}</td>
                <td>
                   
                <a href="{{route('detallesSeguimiento', encrypt($c->idreac))}}"><button type="button" class="btn btn-success">Ver detalle</button></a>    
                </td>

              </tr>
              @endforeach
            </tbody>
          </table>
      </div>
    </div>
  </div>

  <script>
    $("#tabla").DataTable();
   

</script>
  @endsection