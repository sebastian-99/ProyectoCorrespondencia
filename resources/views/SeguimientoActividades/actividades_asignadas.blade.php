@extends('layout.layout')
@section('content')
@section('header')


<script src='{{asset('src/js/zinggrid.min.js')}}'></script>
<script src='{{asset('src/js/zinggrid-es.js')}}'></script>

<!-- Libreria para usar xlsx en js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.13.1/xlsx.full.min.js"></script>
<script src="{{ asset('src/js/xlsx.js') }}"></script>

<script>
  if (es) ZingGrid.registerLanguage(es, 'custom');
</script>

@endsection
<div class="card">
  <div class="card-header">
    <div class="row">
      <div class="col-sm-11">
        <h3>Actividades asignadas
          @if(Auth()->user()->idtu_tipos_usuarios != 4)
          al: {{Auth()->user()->titulo}} {{Auth()->user()->nombre}} {{Auth()->user()->app}} {{Auth()->user()->apm}}
          @endif
          @if(Auth()->user()->idtu_tipos_usuarios == 4)
          del: {{$dir}}
          @endif
        </h3>
      </div>
    </div>
    <div class="text-center">
      <button id="btn_exportar_excel" type="button" class="btn btn-success">
        Exportar a EXCEL
      </button>
    </div>

    <div class="row">
      <div class="col-sm-4">
        <label for="">Fecha orden:</label>
      </div>
      <div class="col-sm-4">
        <label for="">Fecha Inicio:</label>
      </div>

      <div class="col-sm-4">
        <label for="">Fecha Fin:</label>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-4">
        <select class="form-control" name="fecha_orden" id="fecha_orden">
          <option value="0">Todos los registros</option>
          <option value="1">Fecha inicio</option>
          <option value="2">Fecha fin</option>
        </select>
        <button type="button" class="btn btn-primary mt-1" id="button">Enviar</button> <button type="button" class="btn btn-primary mt-1" id="limpiar">Limpiar</button>
      </div>
      <div class="col-sm-4">
        <input class="form-control" name="fechaIni" id="fechaIni" type="date" readonly>

      </div>
      <div class="col-sm-4">
        <input class="form-control" name="fechaFin" id="fechaFin" type="date" readonly>
      </div>
    </div>
  </div>
  @if (Session::has('message'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Contraseña correcta.</strong> {{Session::get('message')}}.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  @endif
  @if (Session::has('rechazo'))
  <div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>¡Alerta!</strong> {{Session::get('rechazo')}}.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  @endif
  @if (Session::has('message2'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Error!</strong> {{Session::get('message2')}}.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  @endif
  <div class="card-body">
    <zing-grid lang="custom" caption='Reporte de oficios' sort search pager page-size='10' page-size-options='10,15,20,25,30' layout='row' viewport-stop theme='android' id='zing-grid' filter selector data="{{$json}}">
      <zg-colgroup>
        <zg-column index='turno' header='Turno' width="100" type='text'></zg-column>
        <zg-column index='asunto' header='Asunto' width="200" type='text'></zg-column>
        <zg-column index='tipo_actividad' header='Tipo de actividad' width="200" type='text'></zg-column>
        <zg-column index='descripcion' header='Descripción' width="200" type='text'></zg-column>
        <zg-column index='fecha_creacion' header='Fecha de creación' width="200" type='text'></zg-column>
        <zg-column index='creador' header='Creador' width="200" type='text'></zg-column>
        <zg-column index='periodo' header='Periodo' width="220" type='text'></zg-column>
        <zg-column index='importancia' header='Importancia' width="130" type='text'></zg-column>
        <zg-column index='area' header='Área' width="170" type='text'></zg-column>
        <zg-column index='porcentaje' header='Avance individual' width="180" type='text'></zg-column>
        <zg-column index='estado' header='Estado' width="210" type='text'></zg-column>
        <zg-column align="center" filter="disabled" index='operaciones' header='Operaciones' width="150" type='text'></zg-column>
      </zg-colgroup>
    </zing-grid>
  </div>
</div>

{{-- Inicia Modal --}}
<div class="modal fade" id="crearModal" value="1" tabindex="-1" role="dialog" aria-labelledby="crearModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Actividad
          @if(Auth()->user()->idtu_tipos_usuarios != 4)
          para: {{Auth()->user()->titulo}} {{Auth()->user()->nombre}} {{Auth()->user()->app}} {{Auth()->user()->apm}}
          @endif
          @if(Auth()->user()->idtu_tipos_usuarios == 4)
          del: {{$dir}}
          @endif
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="container">
        <!--<div class="accordion" id="accordionExample">
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                Ver Detalles
              </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
              <div class="accordion-body">-->
        <div class="row">
          <div class="col-sm-12 mb-3" id="asunto_a"></div>
        </div>
        <div class="row">
          <div class="col-sm-12 mb-3" id="descripcion_a"></div>
        </div>
        <div class="row">
          <div class="col-sm-4 mb-3" id="importancia_a"></div>
          <div class="col-sm-5 mb-3" id="comunicado_a"></div>
          <div class="col-sm-3 mb-3" id="turno_a"></div>
        </div>
        <div class="row">
          <div class="col-sm-6 mb-3" id="creador_a"></div>
          <div class="col-sm-6 mb-3" id="area_a"></div>
        </div>
        <div class="row">
          <div class="col-sm-6 mb-3" id="f_creacion_a"></div>
          <div class="col-sm-6 mb-3" id="periodo_atencion_a"></div>
          <div class="col-sm-12 mb-3" id="razon_activacion_a" ></div>
        </div>
        <!--</div>
            </div>
          </div>{{-- cierre de acordion--}}
        </div>{{-- cierre de acordion-item --}}-->
        <div>
          <div id="sec1" hidden>
            <label>Ingresa tu contraseña para confirmación</label>
            <input type="password" id="pass" name="pass" class="form-control" placeholder="Escribe tu contraseña para aceptar la actividad" required>
            <label hidden>Fecha de acuse de recibido</label>
            <input type="text" id="fechaacuse" name="fecha_acuse" hidden>
          </div>
        </div>
        <div id="sec2" hidden>
          <label>Describe la situación del porque rechazas la actividad</label>
          <Textarea class="form-control" name="rechazo" id="razon_r" value="{{old('rechazo')}}" rows="5" required></Textarea>
        </div>

      </div>
      <br><br>
      <div class="form-group text-center">
        <button type="button" class="btn btn-danger" id="cancelar" hidden="">Cancelar</button>
        <button type="button" class="btn btn-success" id="aceptar">Aceptar actividad</button>
        <button type="button" class="btn btn-secondary" id="rechazar">Rechazar actividad</button>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-outline-primary" id="guardar" data-dismiss="modal"></button>
      </div>
    </div>
  </div>
</div>

{{-- Fin de modal --}}

<script type="text/javascript">
  var id_actividad = null;
  $('body').on('click', '.DetallesAsignacion', function() {
    var id = $(this).data('id');
    id_actividad = id;
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
      $('#razon_activacion_a').empty();
      $('#pass').empty();
      $('#aceptar').show();
      $('#rechazar').show();
      $('#guardar').attr('hidden', true);
      $('#cancelar').attr('hidden', true);

      var val = 0;

      $('#aceptar').on('click', function() {
        $('#sec1').attr('hidden', false);
        $('#guardar').removeAttr('hidden');
        $('#guardar').html('Aceptar');
        $('#cancelar').removeAttr('hidden');
        $('#rechazar').hide();
        $('#aceptar').hide();
        val = 1;
      });

      $('#rechazar').on('click', function() {
        $('#sec2').attr('hidden', false);
        $('#guardar').removeAttr('hidden');
        $('#guardar').html('Rechazar');
        $('#cancelar').removeAttr('hidden');
        $('#rechazar').hide();
        $('#aceptar').hide();
        val = 0;
      });

      $('#cancelar').on('click', function() {

        $('#sec1').attr('hidden', true);
        $('#sec2').attr('hidden', true);
        $('#aceptar').show();
        $('#rechazar').show();
        $('#guardar').attr('hidden', true);
        $('#cancelar').attr('hidden', true);

      });

      $('#crearModal').modal('show');
      var asunto = "<input id='asunto' name='asunto' class='form-control form-control-sm' disabled>"
      var descripcion = "<textarea id='descripcion' name='descripcion' rows='8' class='form-control form-control-sm' disabled></textarea>"
      var importancia = "<input id='importancia' name='importancia'  class='form-control form-control-sm' disabled>"
      var comunicado = "<input id='comunicado' name='comunicado'  class='form-control form-control-sm' disabled>"
      var turno = "<input id='turno' name='turno'  class='form-control form-control-sm' disabled>"
      var creador = "<input id='creador' name='creador'  class='form-control form-control-sm' disabled>"
      var area = "<input id='area' name='area'  class='form-control form-control-sm' disabled>"
      var creacion = "<input id='creacion' name='creacion'  class='form-control form-control-sm' disabled>"
      var periodo = "<input id='periodo' name='periodo'  class='form-control form-control-sm' disabled>"
      var razon_activacion = "<textarea id='razon_activacion' name='razon_activacion' rows='8' class='form-control form-control-sm' disabled></textarea>"

      $('#asunto_a').append("<strong>Asunto </strong>" + asunto);
      $('#descripcion_a').append("<strong>Descripción </strong>" + descripcion);
      $('#importancia_a').append("<strong>Importancia </strong>" + importancia);
      $('#comunicado_a').append("<strong>Comunicado </strong>" + comunicado);
      $('#turno_a').append("<strong>Turno </strong>" + turno);
      $('#creador_a').append("<strong>Creador </strong>" + creador);
      $('#area_a').append("<strong>Área responsable </strong>" + area);
      $('#f_creacion_a').append("<strong>Fecha de creación </strong>" + creacion);
      $('#periodo_atencion_a').append("<strong>Periodo de atención </strong>" + periodo);
      $('#razon_activacion_a').append("<strong>Razón de activación </strong>" + razon_activacion);

      $('#asunto').val(data[0].asunto);
      $('#descripcion').val(data[0].descripcion);
      $('#importancia').val(data[0].importancia);
      $('#comunicado').val(data[0].comunicado);
      $('#turno').val(data[0].turno);
      $('#creador').val(data[0].creador);
      $('#area').val(data[0].nombre_area);
      $('#creacion').val(data[0].fecha_creacion);
      $('#periodo').val(data[0].fecha_inicio + ' al ' + data[0].fecha_fin);
      $('#razon_activacion').val(data[0].razon_activacion);
      //console.log(data[0]);

      //Verificar si hay razon de activacion
      var ra = $('#razon_activacion').val();
      console.log(ra);
      if (ra == "") {
        $('#razon_activacion_a').attr('hidden', true);
      } else {
        $('#razon_activacion_a').attr('hidden', false);
      }

      //Guardar informacion de recibo de actividad

      $('#guardar').on('click', function() {
        if (val != 0) {
          let pass = $('#pass').val();
          $.ajax({
            url: 'aceptarActividad',
            data: {
              id: id_actividad,
              pass: pass,
              "_token": "{{ csrf_token() }}",
            },
            method: 'POST',
            success: function(data) {
              location.reload()
            },
            error: function(data) {
              console.log(data);
            }
          });
          //En caso de que el usuario rechaze la actividada
        } else {
          $('#sec1').hide();
          $('#sec2').hide();
          $('#cancelar').hide();
          $('#detalle').hide();
          $('#mensaje').removeAttr('hidden');
          let rechazo = $('#razon_r').val();
          $.ajax({
            url: 'rechazarActividad',
            data: {
              id_a: id_actividad,
              rechazo: rechazo,
              "_token": "{{ csrf_token() }}",
            },
            method: 'POST',
            success: function(data) {
              location.reload();
              //alert('no funciona');
            },
            error: function(data) {
              console.log(data);
            }
          }); //cierre ajax == rechazo ==
        } //cierre de else
      }); //cierre function guardar
    }); //cierre de function cuando abre ventana modal
  }); //cierre function get


  $("#crearModal").on('hidden.bs.modal', function() {

    $('#asunto_a').empty();
    $('#descripcion_a').empty();
    $('#razon_activacion').empty();
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
    $('#guardar').attr('hidden', true);
    $('#cancelar').attr('hidden', true);
    val = 0;
  }); //cierre acciones al cierre modal


  $('#button').on("click", function() {

    let fecha_orden = $('#fecha_orden').val()
    let fechaIni = $('#fechaIni').val()
    let fechaFin = $('#fechaFin').val()
    $.ajax({
      type: "GET",
      url: "{{route('fecha_actividades_asignadas')}}",
      data: {
        fecha_orden: fecha_orden,
        fechaIni: fechaIni,
        fechaFin: fechaFin
      },
      success: function(data) {
        console.log(data);
        $('#zing-grid').removeAttr('data');
        $('#zing-grid').attr("data", data);
      }
    })

  })
  $('#limpiar').on("click", function() {
    $("#fechaIni").val("");
    $("#fechaFin").val("");
    $("#fecha_orden").val(0);
    $('#fechaIni').attr("readOnly", true);
    $('#fechaFin').attr("readOnly", true);
    $('#fechaIni').val("");
    $('#fechaFin').val("");
  })
  $('#fecha_orden').on("change", function() {
    if ($(this).val() == 0) {
      $('#fechaIni').attr("readOnly", true);
      $('#fechaFin').attr("readOnly", true);
      $('#fechaIni').val("");
      $('#fechaFin').val("");
    } else {
      $('#fechaIni').removeAttr("readOnly");
      $('#fechaFin').removeAttr("readOnly");
    }
  })
</script>
@endsection


<!-- E x c e l -->

@section('scripts')
<script>
  $(document).ready(() => {

    const excel = () => {

      let date = new Date(),
        sheet, data, columns, rows, zing_grid = document.querySelector('zing-grid');

      const headers = ["A3", "B3", "C3", "D3", "E3", "F3", "G3", "H3", "I3", "J3", "K3"];

      data = zing_grid.getData({
        headers: true,
        cols: 'visible',
        rows: 'visible',
      });

      sheet = XLSX.utils.aoa_to_sheet([
        ["Reporte de actividades asignadas"],
      ]);

      XLSX.utils.sheet_add_aoa(sheet, [
        [`Fecha de reporte: ${ date.toLocaleDateString() } ${ date.getHours() }:${ date.getMinutes() }`],
      ], {
        origin: -1
      });

      XLSX.utils.sheet_add_aoa(sheet, [
        ["Turno", "Asunto", "Tipo de Actividades",
          "Descripción", "Fecha de Creación", "Creador",
          "Periodo", "Importancia", "Área", "Avance Individual",
          "Estado"
        ],
      ], {
        origin: -1
      });

      for (value of data) {
        XLSX.utils.sheet_add_aoa(sheet, [
          [value.turno, value.asunto, value.tipo_actividad, value.descripcion, value.fecha_creacion,
            value.creador, value.periodo, value.importancia, value.area, value.porcentaje, value.estado
          ],
        ], {
          origin: -1
        });
      }

      // Size columns
      columns = [
            {wch: 20}, // turno
            {wch: 40}, // asunto
            {wch: 25}, // tipo de actividad
            {wch: 40}, // descripción
            {wch: 20}, // fecha de creación
            {wch: 30}, // creadi por (creador)
            {wch: 30}, // periodo
            {wch: 20}, // importancia
            {wch: 30}, // área
            {wch: 20}, // porcentaje
            {wch: 30}, // estado
        ];

      sheet['!cols'] = columns;

      sheet["!rows"] = rows;

      let mergeA1K1 = {
            s: {r: 0,c: 0},
            e: {r: 0,c: 10}
        }; // Merge A1:K1

      let mergeA2K2 = {
            s: {r: 1,c: 0},
            e: {r: 1,c: 10}
        }; // Merge A2:K2

      if (!sheet['!merges']) sheet['!merges'] = [];

      sheet['!merges'].push(mergeA1K1);

      sheet['!merges'].push(mergeA2K2);

      // set the style of target cell
      sheet["A1"].s = {
        font: {
          name: 'Arial',
          sz: 18,
          bold: true,
          color: {
            rgb: "00000000"
          }
        },
        alignment: {
          horizontal: 'center',
        },
      };

      sheet["A2"].s = {
        font: {
          name: 'Arial',
          sz: 14,
          bold: false,
          color: {
            rgb: "00000000"
          }
        },
        alignment: {
          horizontal: 'center',
        },
      };

      for (value of headers) {

        sheet[value].s = {
          fill: {
            patternType: 'solid',
            fgColor: {
              rgb: "43B105"
            },
            bgColor: {
              rgb: "43B105"
            },
          },
          font: {
            name: 'Arial',
            sz: 12,
            bold: false,
            color: {
              rgb: "FFFFFFFF"
            },
          },
          alignment: {
            horizontal: 'center',
          },
        };

      }

      let book = XLSX.utils.book_new();

      XLSX.utils.book_append_sheet(book, sheet, 'Worksheet 1');

      XLSX.writeFile(book, 'Reporte_de_Actividades_Asignadas.xlsx');

    }

    $('#btn_exportar_excel').on('click', () => {

      excel();

    });


  });
</script>
@endsection
