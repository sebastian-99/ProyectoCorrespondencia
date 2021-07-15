@extends('layout.layout')
@section('content')
    @section('header')

    <script src='{{asset('src/js/zinggrid.min.js')}}'></script>
    <script src='{{asset('src/js/zinggrid-es.js')}}'></script>

    <!-- Libreria para usar xlsx en js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.13.1/xlsx.full.min.js"></script>

    <script>
    if (es) ZingGrid.registerLanguage(es, 'custom');
    </script>

    @endsection
<div class="card">
  <div class="card-header">
    <div class="row">
      <div class="col-sm-11">
        <h3>Reporte de actividades / Oficios</h3>
      </div>
      <div class="col-sm-1">
        <a href="{{route('create_actividades')}}"><button class="btn btn-primary">Nuevo</button></a>
      </div>
    </div>

        <div class="text-center">
            <button id="btn_exportar_excel" type="button" class="btn btn-success">
                Exportar a EXCEL
            </button>
        </div>

    <div class="row">
      <div class="col-sm-6">
        <label for="">Fecha orden:</label>
      </div>
      <div class="col-sm-6">
        <label for="">Fecha:</label>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
        <select class="form-control" name="fecha_orden" id="fecha_orden">
          <option value="0">Todas los registros</option>
          <option value="1">Fecha inicio</option>
          <option value="2">Fecha fin</option>
        </select>
        <button type="button" class="btn btn-primary mt-1" id="button">Enviar</button> <button type="button" class="btn btn-primary mt-1" id="limpiar">Limpiar</button>
      </div>
      <div class="col-sm-6">
        <input class="form-control" name="fecha" id="fecha" type="date" readonly>
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
    <zing-grid lang="custom" caption='Reporte de oficios' sort search pager page-size='10' page-size-options='1,2,3,4,5,10' layout='row' viewport-stop theme='android' id='zing-grid' filter data="{{$json}}">
      <zg-colgroup>
        <zg-column index='turno' header='Turno' width="100" type='number'></zg-column>
        <zg-column index='asunto' header='Asunto' width="200" type='text'></zg-column>
        <zg-column index='nombre_actividad' header='Tipo actividad' width="200" type='text'></zg-column>
        <zg-column index='descripcion' header='Descripción' width='200'></zg-column>
        <zg-column index='fecha_creacion' header='Fecha creación' width="200" type='text'></zg-column>
        <zg-column index='creador' header='Creador' width="200" type='text'></zg-column>
        <zg-column index='periodo' header='Periodo' width="220" type='text'></zg-column>
        <zg-column index='importancia' header='Importancia' width="130" type='text'></zg-column>
        <zg-column index='nombre' header='Área responsable' width="170" type='text'></zg-column>
        <zg-column index='avance' header='Avance' width="120" type='text'></zg-column>
        <zg-column index='atendido_por' header='Atendido por' width="135" type='text'></zg-column>
        <zg-column index='estatus' header='Estado' width="120" type='text'></zg-column>
        <zg-column align="center" filter="disabled" index='operaciones' header='Operaciones' width="150" type='text'></zg-column>
      </zg-colgroup>
    </zing-grid>
  </div>
</div>
<script>
  $('#button').on("click", function() {

    let fecha_orden = $('#fecha_orden').val()
    let fecha = $('#fecha').val()
    $.ajax({
      type: "GET",
      url: "{{route('fecha_ajax')}}",
      data: {
        fecha_orden: fecha_orden,
        fecha: fecha
      },
      success: function(data) {
        console.log(data);
        $('#zing-grid').removeAttr('data');
        $('#zing-grid').attr("data", data);
      }
    })

  })
  $('#limpiar').on("click", function() {
    $("#fecha").val("");
    $("#fecha_orden").val(0);
    $('#fecha').attr("readOnly", true);
    $('#fecha').val("");
  })
  $('#fecha_orden').on("change", function() {
    if ($(this).val() == 0) {
      $('#fecha').attr("readOnly", true);
      $('#fecha').val("");
    } else {
      $('#fecha').removeAttr("readOnly");
    }
  })
</script>
@endsection

<!-- E x c e l -->
 @section('scripts')
        <script>
            $(window).on('load', function(){
                const $NAME_EXCEL = 'Reporte de Actividades Asignadas';
                const $BTN_EXPORTAR_EXCEL = $('#btn_exportar_excel');
                const $ZING_GRID = document.querySelector('zing-grid');

                $BTN_EXPORTAR_EXCEL.on('click', function(){
                    let $gridData = $ZING_GRID.getData({
                        headers:true,
                        cols:'visible',
                        rows:'visible'
                    });

                    $.map($gridData, function(data){
                        delete data.operaciones;
                    });

                    let $sheet = XLSX.utils.json_to_sheet($gridData);
                    let $book = XLSX.utils.book_new();
                    XLSX.utils.book_append_sheet($book, $sheet, 'Hoja 1');
                    XLSX.writeFile($book, $NAME_EXCEL + '.xlsx');
                });
            });
        </script>
    @endsection
