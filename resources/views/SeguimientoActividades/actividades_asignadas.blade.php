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
        <h3>Actividades asignadas al {{Auth()->user()->titulo}} {{Auth()->user()->nombre}} {{Auth()->user()->app}} {{Auth()->user()->apm}} </h3>
      </div>
    </div>
  </div>
  <div class="card-body">
    <zing-grid lang="custom" caption='Reporte de oficios' sort search pager page-size='3' page-size-options='1,2,3,4,5,10' layout='row' viewport-stop theme='android' id='zing-grid' filter data="{{$json}}">
      <zg-colgroup>
        <zg-column index='turno' header='Turno' width="100" type='text'></zg-column>
        <zg-column index='fecha_creacion' header='Fecha de creacion' width="100" type='text'></zg-column>
        <zg-column index='asunto' header='Asunto' width="200" type='text'></zg-column>
        <zg-column index='creador' header='Creador' width="200" type='text'></zg-column>
        <zg-column index='periodo' header='Periodo' width="150" type='text'></zg-column>
        <zg-column index='importancia' header='Importancia' width="100" type='text'></zg-column>
        <zg-column index='area' header='Àrea' width="100" type='text'></zg-column>
        <zg-column index='recibo' header='Atendido por' width="100" type='text'></zg-column>
        <zg-column index='porcentaje' header='Avance' width="100" type='text'></zg-column>

        <zg-column align="center" filter="disabled" index='operaciones' header='Operaciones' width="100" type='text'></zg-column>
      </zg-colgroup>
    </zing-grid>
  </div>
</div>

{{-- Inicia Modal --}}
<div class="modal fade" id="ajaxModel" value="1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Actividad para: {{Auth()->user()->titulo}} {{Auth()->user()->nombre}} {{Auth()->user()->app}} {{Auth()->user()->apm}} </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="container">
        <div class="accordion" id="accordionExample">
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                Ver Detalles
              </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <div class="row">
                  <div class="col-sm-12 mb-3" id="asunto_a"></div>
                </div>
                <div class="row">
                  <div class="col-sm-12 mb-3" id="descripcion_a"></div>
                </div>
                <div class="row">
                  <div class="col-sm-4 mb-3" id="importancia_a"></div>
                  <div class="col-sm-4 mb-3" id="comunicado_a"></div>
                  <div class="col-sm-4 mb-3" id="turno_a"></div>
                </div>
                <div class="row">
                  <div class="col-sm-6 mb-3" id="creador_a"></div>
                  <div class="col-sm-6 mb-3" id="area_a"></div>
                </div>
                <div class="row">
                  <div class="col-sm-6 mb-3" id="f_creacion_a"></div>
                  <div class="col-sm-6 mb-3" id="periodo_atencion_a"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div id="sec1" hidden>
          <label>Ingresa tu contraseña para confirmacion</label>
          <input type="password" class="form-control">
        </div>
        <div id="sec2" hidden>
          <label>Describe la situacion del porque rechazas la actividad</label>
          <Textarea cols="64" rows="5"></Textarea>
        </div>
      </div>
      <br><br>
      <div class="form-group text-center">
        <button type="button" class="btn btn-success" id="aceptar">Aceptar actividad</button>
        <button type="button" class="btn btn-secondary" id="rechazar">Rechazar actividad</button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>

  {{-- Fin de modal --}}

  <script>
    $('#aceptar').on('click', function() {
      $('#sec1').removeAttr('hidden');
      $('#rechazar').prop('disabled', true);
    });

    $('#aceptar').dblclick(function() {
      $('#sec1').Attr('hidden');
      $('#rechazar').prop('disabled', false);
    });

    $('#rechazar').on('click', function() {
      $('#sec2').removeAttr('hidden');
      $('#aceptar').prop('disabled', true);
    });



    /*$('#rechazar').dblclick(function () {
      $('#sec2').Attr('hidden');
      $('#aceptar').prop('disabled', false);
    });*/

    /*$('#rechazar').mouseover(function(){

    });*/
  </script>
  <script type="text/javascript">
    $('body').on('click', '.DetallesAsignacion', function() {
      var id = $(this).data('id');

      $.get("../DetallesAsignacion/" + id, function(data) {
       
        

        $('#modelHeading').html("Detalles Archivos");
        $('#ajaxModel').modal('show');
        var asunto = "<input id='asunto' name='asunto' class='form-control form-control-sm' disabled>"
        var descripcion = "<textarea id='descripcion' name='descripcion'  class='form-control form-control-sm' disabled></textarea>"
        var importancia = "<input id='importancia' name='importancia'  class='form-control form-control-sm' disabled>"
        var comunicado = "<input id='comunicado' name='comunicado'  class='form-control form-control-sm' disabled>"
        var turno = "<input id='turno' name='turno'  class='form-control form-control-sm' disabled>"
        var creador = "<input id='creador' name='creador'  class='form-control form-control-sm' disabled>"
        var area = "<input id='area' name='area'  class='form-control form-control-sm' disabled>"
        var creacion = "<input id='creacion' name='creacion'  class='form-control form-control-sm' disabled>"
        var periodo = "<input id='periodo' name='periodo'  class='form-control form-control-sm' disabled>"

        $('#asunto_a').append("<strong>Asunto </strong>" + asunto);
        $('#descripcion_a').append("<strong>Descripcion </strong>" + descripcion);
        $('#importancia_a').append("<strong>Importancia </strong>" + importancia);
        $('#comunicado_a').append("<strong>Comunicado </strong>" + comunicado);
        $('#turno_a').append("<strong>Turno </strong>" + turno);
        $('#creador_a').append("<strong>Creador </strong>" + creador);
        $('#area_a').append("<strong>Area responsable </strong>" + area);
        $('#f_creacion_a').append("<strong>Fecha de creacion </strong>" + creacion);
        $('#periodo_atencion_a').append("<strong>Periodo de atencion </strong>" + periodo);

        $('#asunto').val(data[0].asunto);
        $('#descripcion').val(data[0].descripcion);
        $('#importancia').val(data[0].importancia);
        $('#comunicado').val(data[0].comunicado);
        $('#turno').val(data[0].turno);
        $('#creador').val(data[0].creador);
        $('#area').val(data[0].turno);
        $('#creacion').val(data[0].fecha_creacion);
        $('#periodo').val(data[0].fecha_inicio);

      })

    });

    $("#ajaxModel").on('hidden.bs.modal', function() {

      $('#asunto_a').empty();
      $('#descripcion_a').empty();
      $('#importancia_a').empty();
      $('#comunicado_a').empty();
      $('#turno_a').empty();
      $('#creador_a').empty();
      $('#area_a').empty();
      $('#f_creacion_a').empty();
      $('#periodo_atencion_a').empty();

    });
  </script>

  @endsection