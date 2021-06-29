@extends('layout.layout')
@section('content')
@section('header')
<script src='{{asset('src/js/zinggrid.min.js')}}'></script>
<script src='{{asset('src/js/zinggrid-es.js')}}'></script>
<script>
  if (es) ZingGrid.registerLanguage(es, 'custom');
</script>
@endsection

<div class="card">
  <div class="card-header">
    <div class="row">
      <div class="col-sm-11">
        @foreach ($consult as $c)
        @if ($loop->first)
        <h3>Reporte de actividades de: ``{{$c->nombre}}´´ Área: ``{{$c->nombre_ar}}´´ </h3>
        @endif
        @endforeach
      </div>
      <div class="col-sm-10">
        <a href="{{ route('Detalles', ['id'=>$id_actividad]) }}"><button class="btn btn-warning">Regresar a responsables</button></a>
      </div>
    </div>
  </div>
  <zing-grid lang="custom" caption='Actividades' sort search pager page-size='10' page-size-options='10,20,50' layout='row' viewport-stop theme='android' id='zing-grid' filter data="{{$json}}">
    <zg-colgroup>
      <zg-column index='idseac' header='No. Seguimeinto' width="" type='text'></zg-column>
      <zg-column index='fecha' header='Fecha de avance' width="" type='text'></zg-column>
      <zg-column index='detalle' header='Detalles' width="600" type='text'></zg-column>
      <zg-column index='estado' header='Estado' width="" type='text'></zg-column>
      <zg-column index='porcentaje' filter="disabled" header='Porcentaje' width="" type='text'></zg-column>
      <zg-column align="center" filter="disabled" index='operaciones' header='Operaciones' width="" type='text'></zg-column>
    </zg-colgroup>
  </zing-grid>

  <div class="modal fade" id="ajaxModel" value="1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header" style="background-color:#198754; color: #ffffff">
          @foreach ($consult as $c)
          @if ($loop->first)
          <h4>
            <div>Actividad: {{$c->asunto}} </div>
            <div> Usuario: {{$c->nombre}} </div>
            <div> Area: {{$c->nombre_ar}} </div>
            @endif
          </h4>
          @endforeach
        </div>
        <div class="modal-body">
          <form id="DetallesArchivos" name="DetallesArchivos" class="form-horzontal">
            <div class="card-body">
              <div class="table-responsive">
                <h4 class="modal-title" id="modelHeading" style="background-color: #607d8b; color: #ffffff">Archivos</h4>
                <table class="table table-striped table-bordered" id="tablaModal">
                  <thead class="text-center">
                    <tr>
                      <th scope="col">Nombre </th>
                      <th scope="col">Detalles</th>
                      <th scope="col">Archivo</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="text-center">
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>


    <script type="text/javascript">
      $('body').on('click', '.DetallesArchivos', function() {
        var id = $(this).data('id');

        var i = 0;
        $.get("../DetallesArchivos/" + id, function(data) {
          $('#tablaModal>tbody>tr').remove();
          for (i = 0; i <= data.length - 1; i++) {

            $('#ajaxModel').modal('show');
            var nombre = "<td><input id='nombre" + i + "' name='nombre" + i + "'  style='width:400px' disabled></td>"
            var detalle = "<td><input id='detalle" + i + "' name='detalle" + i + "'  style='width:400px' disabled></td>"

            var ruta_a = "<td><a download id='ruta" + i + "' name='ruta" + i + "'class='btn btn-danger' ><i class='fa fa-file'></i></a></td>"
            var archivo = "{{asset(('archivos/Seguimientos'))}}/" + data[i].ruta;
            $('#tablaModal>tbody').append("<tr>" + nombre + detalle + ruta_a + "</tr>");
            $('#nombre' + i).val(data[i].nombre);
            $('#detalle' + i).val(data[i].detalle_a);

            $('#ruta' + i).attr('download', data[i].nombre);
            $('#ruta' + i).attr('href', archivo);
          }
        })

      });

      $("#ajaxModel").on('hidden.bs.modal', function() {

        $('#tablaModal>tbody>tr').remove();

      });
    </script>
    @endsection