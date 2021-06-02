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
        <div class="col-sm-1">
         <a href="{{route('create_actividades')}}"><button class="btn btn-primary">Nuevo</button></a>
        </div>
      </div>
      
    </div>
    <div class="card-body">
    @if (Session::has('message'))
                <p class="alert alert-info">
                    {{Session::get('message')}}
                </p>
                @endif
                @if (Session::has('message2'))
                <p class="alert alert-danger">
                    {{Session::get('message2')}}
                </p>
                @endif
                @if (Session::has('message3'))
                <p class="alert alert-warning">
                    {{Session::get('message3')}}
                </p>
                @endif  
      <div class="table-responsive">
        <table class="table table-striped table-bordered" id="tabla">
            <thead class="text-center">
              <tr style="background-color: #1F75FE; color: #ffffff">
                <th scope="col">Turno</th>
                <th scope="col">Fecha creaci&aacute;n</th>
                <th scope="col">Asunto</th>
                <th scope="col">Creado por:</th>
                <th scope="col">Periodo atenci&oacute;n</th>
                <th scope="col">Nivel de atenci&oacute;n</th>
                <th scope="col">Area responsable</th>
                <th scope="col">Avance</th>
                <th scope="col">Atendido por</th>
                <th scope="col">Operaciones</th>
              </tr>
            </thead>
            <tbody>
            @foreach ($consult as $c)
              <tr class="text-center">
                <td>{{$c->turno}}</td>
                <td>{{$c->fecha_creacion}}</td>
                <td>{{$c->asunto}}</td>
                <td>{{$c->creador}}</td>
                <td>{{$c->periodo}}</td>
                <td>{{$c->importancia}}</td>
                <td>{{$c->nombre}}</td>
                <td>100%</td>
                <td>2 de 3</td>
                <td>
                   
                <a target='_blank' onclick=window.open(this.href,this.target,width=600,height=800); href="{{route('Detalles', encrypt($c->idac))}}"><button type="button" class="btn btn-success">Ver detalle</button></a>    
                    <a href=""><button type="button" class="btn btn-warning">Modificar</button></a>    
                    <a href=""><button type="button" class="btn btn-danger">Modificar</button></a>    
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