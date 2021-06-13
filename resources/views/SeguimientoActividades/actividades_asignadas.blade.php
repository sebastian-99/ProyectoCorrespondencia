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
<!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
            Aceptar Asignación
            </button>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Actividad de: </h5>
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
                              <strong>Titulo (asunto).</strong>
                              <li>Descripcion</li>
                              <li>Comunicado</li>
                              <li>Importancia</li>
                              <li>Tipo de actividad</li>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div id="sec1" hidden>
                        <label>Incresa tu contraseña para confirmacion</label>
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
            </div>
{{-- Fin de modal --}}

<script>

  $('#aceptar').on('click', function () {
    $('#sec1').removeAttr('hidden');
    $('#rechazar').prop('disabled', true);
  });

  $('#aceptar').dblclick(function () {
    $('#sec1').Attr('hidden');
    $('#rechazar').prop('disabled', false);
  });

  $('#rechazar').on('click', function () {
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

@endsection
