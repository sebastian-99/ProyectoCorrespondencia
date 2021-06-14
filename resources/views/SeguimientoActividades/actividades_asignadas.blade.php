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
<<<<<<< HEAD
    <zing-grid lang="custom" caption='Reporte de oficios' sort search pager page-size='10' page-size-options='5,10,20,30,50,100' layout='row' viewport-stop theme='android' id='zing-grid' filter data="{{$json}}">
=======
    <zing-grid lang="custom" caption='Reporte de oficios' sort search pager page-size='10' page-size-options='10,15,20,25,30' layout='row' viewport-stop theme='android' id='zing-grid' filter data="{{$json}}">
>>>>>>> fe58c26eaefd9767ea0844bafdc7b62376f6b528
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
<div class="modal fade" id="crearModal" value="1" tabindex="-1" role="dialog" aria-labelledby="crearModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Actividad para: {{Auth()->user()->titulo}} {{Auth()->user()->nombre}} {{Auth()->user()->app}} {{Auth()->user()->apm}} </h5>
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
          <div>
            <div id="sec1" hidden>
              <label>Ingresa tu contraseña para confirmacion</label>
              <input type="password" id="pass" name="pass" class="form-control">
              <label hidden>Fecha de acuse de recibido</label>
              <input type="text" id="fechaacuse" name="fecha_acuse" hidden>
            </div>
          </div>
          <div >
            <div id="sec2" hidden>
              <label>Describe la situacion del porque rechazas la actividad</label>
              <Textarea name="rechazo" cols="64" rows="5"></Textarea>
            </div>
          </div>
        </div>
        <br><br>
        <div class="form-group text-center">
          <button type="button" class="btn btn-success" id="aceptar">Aceptar actividad</button>
          <button type="button" class="btn btn-secondary" id="rechazar">Rechazar actividad</button>
          <button type="button" class="btn btn-danger" id="cancelar" hidden="">Cancelar</button>
        </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="guardar" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">Guardar</button>
      </div>
    </div>
  </div>
</div>

{{-- Fin de modal --}}

<<<<<<< HEAD
  <script>

  let val = 0;

  $('#aceptar').on('click', function () {
    $('#sec1').removeAttr('hidden');
    $('#cancelar').removeAttr('hidden');
    $('#rechazar').hide();
    $('#aceptar').hide();
    val = 1;
  });

  $('#rechazar').on('click', function() {
    $('#sec2').removeAttr('hidden');
    $('#cancelar').removeAttr('hidden');
    $('#aceptar').hide();
    $('#rechazar').hide();
    val = 0;
  });

  $('#cancelar').on('click', function() {
    if ($('#sec1').attr('hidden', false) || $('#sec2').attr('hidden', false)) {
      $('#sec1').attr('hidden', true);
      $('#sec2').attr('hidden', true);
      $('#aceptar').show();
      $('#rechazar').show();
      $('#cancelar').hide();
    }
  });

  $('#guardar').on('click', function () {
    if (val!=0) {
      $('#btn-mostrar').removeAttr('hidden');
      $('#seccion')[0].reset();
      $('#sec1').hide();
      $('#sec2').hide();
      $('#cancelar').hide();
      $('#detalle').hide();
    } else {
      $('#sec1').hide();
      $('#sec2').hide();
      $('#cancelar').hide();
      $('#detalle').hide(); 
      $('#mensaje').removeAttr('hidden'); 
      alert('Has rechachazo esta actividad por lo que ya no podras darle seguimiento');
    }
  });

  </script>
=======
>>>>>>> fe58c26eaefd9767ea0844bafdc7b62376f6b528
  <script type="text/javascript">
    $('body').on('click', '.DetallesAsignacion', function() {
      let id = $(this).data('id');
      console.log(id);
      $.get("../DetallesAsignacion/" + id, function(data) {
        $('#asunto_a').empty();
        $('#descripcion_a').empty();
        $('#importancia_a').empty();
        $('#comunicado_a').empty();
        $('#turno_a').empty();
        $('#creador_a').empty();
        $('#area_a').empty();
        $('#f_creacion_a').empty();
        $('#periodo_atencion_a').empty();
        $('#pass').empty();
        $('#aceptar').show();
        $('#rechazar').show();


        $('#crearModal').modal('show');
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

    $("#crearModal").on('hidden.bs.modal', function() {

    $.get("../DetallesAsignacion/" + id, function(data) {
      $('#asunto_a').empty();
      $('#descripcion_a').empty();
      $('#importancia_a').empty();
      $('#comunicado_a').empty();
      $('#turno_a').empty();
      $('#creador_a').empty();
      $('#area_a').empty();
      $('#f_creacion_a').empty();
      $('#periodo_atencion_a').empty();
      $('#pass').empty();

<<<<<<< HEAD
=======
    });

    let val = 0;

  $('#aceptar').on('click', function () {
    $('#sec1').removeAttr('hidden');
    $('#cancelar').removeAttr('hidden');
    $('#rechazar').hide();
    $('#aceptar').hide();
    val = 1;
  });
  
  $('#rechazar').on('click', function () {
    $('#sec2').removeAttr('hidden');
    $('#cancelar').removeAttr('hidden');
    $('#aceptar').hide();
    $('#rechazar').hide();
    val = 0;
  });
  
  $('#cancelar').on('click', function () {
    if ($('#sec1').attr('hidden', false) || $('#sec2').attr('hidden', false)){
      $('#sec1').attr('hidden', true);
      $('#sec2').attr('hidden', true);
      $('#aceptar').show();
      $('#rechazar').show();
      $('#cancelar').hide();
    }
  });

  $('#guardar').on('click', function () {
    //alert('funciona');
    if (val!=0) {
      let idac = $('.DetallesAsignacion').data('id');
      //console.log(idac);
      let pass = $('#pass').val();
      $.ajax({
        url:'aceptarActividad',
        data:{id:idac,
          pass:pass,
          "_token": "{{ csrf_token() }}",
        },
        method:'POST',
        success:function(data) {
          $('#btn-mostrar').removeAttr('hidden');
          $('#pass').val('');
          $('#sec1').hide();
          $('#sec2').hide();
          $('#cancelar').hide();
          $('#detalle').hide();
          //console.log(data);
        }, error: function (data) {
          console.log(data);
        }
      });

    } else {
      $('#sec1').hide();
      $('#sec2').hide();
      $('#cancelar').hide();
      $('#detalle').hide(); 
      $('#mensaje').removeAttr('hidden'); 
      alert('Haz rechazado esta actividad, por lo que ya no podrás darle seguimiento');
    }
  });
  </script>
>>>>>>> fe58c26eaefd9767ea0844bafdc7b62376f6b528

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