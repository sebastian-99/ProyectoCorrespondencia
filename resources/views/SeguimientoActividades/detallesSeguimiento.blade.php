@extends('layout.layout')
@section('content')
@section('header')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
@endsection
<div class="card">

    <div class="card">
    <div class="card-header">
    @foreach ($consult as $c)
    @if ($loop->first)
    <h3>Reporte de actividades de {{$c->nombre}} </h3>
    @endif
    </div>
    @endforeach
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-bordered" id="tabla">
            <thead class="text-center">
              <tr style="background-color: #1F75FE; color: #ffffff">
                <th scope="col">No. Seguimiento</th>
                <th scope="col">Fecha de avance</th>
                <th scope="col">Detalle</th>
                <th scope="col">Status</th>
                <th scope="col">% de avance</th>
                <th scope="col">Archivos</th>
              </tr>
            </thead>
            <tbody>
            @foreach ($consult as $c)
            <tr class="text-center">
                <td>{{$c->idseac}}</td>
                <td>{{$c->fecha}}</td>
                <td>{{$c->detalle}}</td>
                <td>{{$c->estado}}</td>
                <td>{{$c->porcentaje}}</td>
                <td>{{$c->ruta}}</td>
                

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