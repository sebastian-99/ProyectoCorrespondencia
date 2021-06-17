@extends('layout.layout')
@section('content')

@section('header')

<script src='{{asset('src/js/zinggrid.min.js')}}'></script>
<script src='{{asset('src/js/zinggrid-es.js')}}'></script>
<script>
    if (es) ZingGrid.registerLanguage(es, 'custom');
</script>

<script>
    addEventListener('load', inicio, false);

    function inicio() {
        document.getElementById('porcentaje').addEventListener('change', porcentajeAvance, false);
    }

    function porcentajeAvance() {
        document.getElementById('porc').innerHTML = document.getElementById('porcentaje').value;
    }
</script>

<style>
    body {
        color: #4D4D4D;
        font: 15px, Helvetica;
    }
</style>

@endsection

<input type="hidden" value="{{$actividades->idac}}" name="idac">

<div class="row">
    <div class="col-sm-6">
        <h3 class="display-6">Detalle del turno: {{$actividades->turno}}</h3>
    </div>
    <div class="col-sm-6">
        <h3 class="display-6">Comunicado: {{$actividades->comunicado}}</h3>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="bd-intro ps-lg-4">
                    <div class="d-md-flex align-items-center justify-content-between">
                        <h1 class="bd-title" id="content">{{$actividades->asunto}}</h1>
                    </div>
                    <p class="bd-lead">{{$actividades->descripcion}}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-9">

        <div class="card">
            <div class="card-body">

                <table class="table table-responsive table-striped">
                    <thead class="">
                        <tr style="background-color: #607d8b; color: #ffffff">
                            <th scope="col">Turno</th>
                            <th scope="col">Creación</th>
                            <th scope="col">Creado por </th>
                            <th scope="col">Periodo atención </th>

                            <th scope="col">Área Responsable</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">{{$actividades->turno}}</th>

                            <td> {{ Carbon\Carbon::parse($actividades->fecha_creacion)->locale('es')->isoFormat('D MMMM h:mm a') }}</td>
                            <td>{{$actividades->creador}} </td>
                            <td>
                                {{ Carbon\Carbon::parse($actividades->fecha_inicio)->locale('es')->isoFormat('D MMMM') }} al
                                {{ Carbon\Carbon::parse($actividades->fecha_fin)->locale('es')->isoFormat('D MMMM') }}
                            </td>
                            <td>{{$actividades->nombre_area}}</td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-responsive table-striped">
                    <thead class="">
                        <tr style="background-color: #607d8b; color: #ffffff">
                            <th scope="col">Atendido por</th>
                            <th scope="col">Nombre atendió</th>
                            <th scope="col">Cargo</th>
                            <th scope="col">Acuse Recibido</th>
                            <th scope="col">Nivel atención </th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{$atendido->atencion}} de {{$total_at->total}}</td>
                            <td>{{Auth()->user()->titulo . ' ' . Auth()->user()->nombre . ' '  .Auth()->user()->app . ' ' . Auth()->user()->apm}}</td>
                            <td>{{$user->tipo_usuario . ' - ' . $user->nombre_areas}}</td>
                            <td>Si</td>
                            <td>{{$actividades->importancia}}</td>

                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    <div class="col-sm-3">
        <div class="card">
            <div class="card-body">
                <div class="bd-intro ps-lg-4">
                    <div class="d-md-flex align-items-center justify-content-between">
                        <h3 class="bd-title">Avance de tu actividad</h3>
                    </div>
                    <p class="bd-lead"></p>
                    <p class="bd-lead">
                        <h6>{{$max_ai->avance_i}}%</h6>
                    </p>
                    <!--<div class="d-md-flex align-items-center justify-content-between">
                        <h3 class="bd-title">Status atención</h3>
                    </div>
                    <input class="form-control form-control-sm bg-success" value="En tiempo" type="text" disabled>-->


                </div>

            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-5">
        <div class="card">
            <div class="card-body">
                <form action="{{route('AgregarSeguimiento')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!--<div class="col-sm-4">
                        <div class="mb-3">
                            <label for="NoSeguimiento" class="form-label">No. Seguimiento</label>
                            <input type="text" class="form-control form-control-sm" id="idseac" name="idseac">
                        </div>
                    </div>-->
                    <input type="hidden" class="form-control form-control-sm" id="idreac" name="idreac_responsables_actividades" value="{{$resp->idreac}}">

                    <div class="col-sm-12">
                        <div class="mb-3">
                            <label for="" class="form-label">Seguimiento realizado por</label>
                            <input type="text" class="form-control form-control-sm" id="" value="{{Auth()->user()->titulo . ' ' . Auth()->user()->nombre . ' '  .Auth()->user()->app . ' ' . Auth()->user()->apm}}   /    {{$user->tipo_usuario . ' - ' . $user->nombre_areas}}" disabled>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="mb-3">
                            <label for="fecha_seg" class="form-label">Fecha de Seguimiento</label> ( {{$now->locale('es')->isoFormat('D MMMM h:mm')}} )
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="mb-3">
                            <label for="detalle" class="form-label">Detalle de la actividad</label>
                            <textarea class="form-control" rows="5" name="detalle" id="detalle" required></textarea>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="mb-3">
                            <label for="porcentaje" class="form-label">Porcentaje </label>
                            <input class="form-control-range" type="range" id="porcentaje" min="0" max="100" name="porcentaje" value="{{$max_ai->avance_i}}" onchange="verificar_p()">
                            <span id="porc">{{$max_ai->avance_i}}</span>%

                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado Actividad</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="estado" id="estado_p" value="Pendiente" checked>
                                <label class="form-check-label" for="inlineRadio1">Pendiente</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="estado" id="estado_c" value="Completo" onchange="verificar_s()">
                                <label class="form-check-label" for="inlineRadio2">Completo</label>
                            </div>
                        </div>
                    </div>


                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="ruta[]" class="form-label">Seleccione Archivo</label>
                            <input class="form-control form-control-sm" id="ruta[]" name="ruta[]" type="file" multiple>
                        </div>
                    </div>


                    <div class="col-sm-12">
                        <div class="mb-3">
                            <label for="detalle" class="form-label">Detalle Evidencia</label>
                            <input type="text" class="form-control form-control-sm" id="detalle" name="detalle_a" placeholder="Detalle de la evidencia">
                        </div>
                    </div>
                    <div class="col-sm-2">

                        <button class="btn btn-sm btn-success">Guardar seguimiento</button>

                    </div>

                </form>
            </div>
        </div>
    </div>

    <div class="col-sm-7">


        <div class="card">
            <div class="card-body">
                <center>
                    <h4>Archivos de la actividad</h4>
                </center><br>
                <table class="table table-responsive">
                    <thead class="">
                        <tr style="background-color: #607d8b; color: #ffffff">
                            <th scope="col">Archivo</th>
                            <th scope="col">Nombre archivo</th>
                            <th scope="col">Detalle (link)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($actividades->archivo1 == "Sin archivo" && $actividades->archivo2 == "Sin archivo" && $actividades->archivo3 == "Sin archivo" )
                        <tr>
                            <td colspan="3">Esta actividad no contiene archivos para atender, solo cuenta con links.</td>
                        </tr>
                        @if($actividades->link1 != "" || $actividades->link2 != "" || $actividades->link3 != "" )
                        <thead class="">
                            <tr style="background-color: #607d8b; color: #ffffff">
                                <td colspan="3">Links de soporte.</td>
                            </tr>
                        </thead>
                        <tr>
                            <td colspan="3">{{$actividades->link1}}</td>
                        </tr>
                        <tr>
                            <td colspan="3">{{$actividades->link2}}</td>
                        </tr>
                        <tr>
                            <td colspan="3">{{$actividades->link3}}</td>
                        </tr>
                        @endif
                        @endif
                        @if ($actividades->archivo1 != "Sin archivo")
                        <tr>
                            <td><a download href="{{asset('archivos/').'/'.$actividades->archivo1}}" class="btn btn-sm btn-danger"><i class="fa fa-download"></i></a></td>
                            <td>{{$actividades->archivo1}}</td>
                            <td>{{$actividades->link1}}</td>
                        </tr>
                        @endif
                        @if ($actividades->archivo2 != "Sin archivo")
                        <tr>
                            <td><a download="" href="{{asset('archivos/').'/'.$actividades->archivo2}}" class="btn btn-sm btn-danger"><i class="fa fa-download"></i></a></td>
                            <td>{{$actividades->archivo2}}</td>
                            <td>{{$actividades->link2}}</td>
                        </tr>
                        @endif

                        @if ($actividades->archivo3 != "Sin archivo")
                        <tr>
                            <td><a download="{{$actividades->archivo3}}" href="{{asset('archivos/').'/'.$actividades->archivo3}}" class="btn btn-sm btn-danger"><i class="fa fa-download"></i></a></td>
                            <td>{{$actividades->archivo1}}</td>
                            <td>{{$actividades->link3}}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <center>
                    <h4>Seguimientos de mi actividad</h4>
                </center><br>
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
                <zing-grid lang="custom" caption='Reporte de oficios' sort search pager page-size='10' page-size-options='1,3,5,10' layout='row' viewport-stop theme='android' id='zing-grid' filter data="{{$json_sa}}">
                    <zg-colgroup>
                        <zg-column index='idseac' header='No- Seguimiento' width="100" type='text'></zg-column>
                        <zg-column index='detalle' header='Detalle' width="300" type='text'></zg-column>
                        <zg-column index='fecha' header='Fecha de avance' width="200" type='text'></zg-column>
                        <zg-column index='estado' header='Status' width="200" type='text'></zg-column>
                        <zg-column index='porcentaje' header='% Avance' width="150" type='text'></zg-column>
                        <zg-column index='evidencia' filter="disabled" header='Importancia' width="150" type='text'></zg-column>
                    </zg-colgroup>
                </zing-grid>

            </div>
        </div>
    </div>

</div>

<!-- Modal-->
<div class="modal fade" id="ajaxModel" value="1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="DetallesArchivos" name="DetallesArchivos" class="form-horzontal">
                    <div class="card-body">
                        <div class="table-responsive">

                            <table class="table table-striped table-bordered" id="tablaModal">
                                <thead class="text-center">
                                    <tr style="background-color: #858FA3; color: #ffffff">
                                        <th scope="col">Nombre </th>
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
        //comprobar si el porcentaje de avance es igual 100% marcar estado completado
        function verificar_p() {
            var verif_p = document.getElementById("porcentaje").value;
            if (verif_p == 100) {
                $('#estado_c').prop("checked", true);
                $('#estado_p').prop('disabled', true);
            } else {
                $('#estado_p').prop("checked", true);
                $('#estado_p').prop('disabled', false);
            }
        }

        function verificar_s() {
            var verif_s = document.getElementById("estado_c").value;
            var verif_sp = document.getElementById("estado_p").value;
            if (verif_s == 'Completo') {
                $('#porcentaje').val(100);
                $('#porc').html(100);
                $('#estado_p').prop('disabled', true);
            }
        }

        $('body').on('click', '.DetallesArchivos', function() {
            var id = $(this).data('id');

            var i = 0;
            $.get("../DetallesArchivos/" + id, function(data) {
                $('#tablaModal>tbody>tr').remove();
                while (i != 1 + i) {
                    if (data[i].nombre == null) {
                        i = i + 1;
                        break;
                    } else {

                        $('#modelHeading').html("Detalles Archivos");
                        $('#ajaxModel').modal('show');
                        var nombre = "<td><input id='nombre" + i + "' name='nombre" + i + "'  style='width:400px' disabled></td>"
                        //  var detalle = "<td><input id='detalle"+i+"' name='detalle"+i+"' style='width:400px' disabled></td>"
                        if (data[i].ruta == 'Sin archivo') {
                            var texto = '<td>No hay archivos disponibles</td>';
                            $('#tablaModal>tbody').append("<tr>" + nombre + texto + "</tr>");
                            $('#nombre' + i).val(data[i].nombre);
                            // $('#detalle'+i).val(data[i].detalle);
                            $('#ruta' + i).val(ruta);
                            $('#ruta' + i).attr('href', archivo);
                            $('#ruta' + i).text(texto);
                        } else if (data[i].ruta != '') {
                            var ruta = "<td><a download id='ruta" + i + "' name='ruta" + i + "'class='btn btn-danger' ><i class='fa fa-file'></i></a></td>"
                            var archivo = '{{asset(('
                            archivos / Seguimientos '))}}/' + data[i].ruta;
                            $('#tablaModal>tbody').append("<tr>" + nombre + ruta + "</tr>");
                            $('#nombre' + i).val(data[i].nombre);
                            //  $('#detalle'+i).val(data[i].detalle);
                            $('#ruta' + i).val(ruta);
                            $('#ruta' + i).attr('href', archivo);
                            $('#ruta' + i).text(texto);
                        }



                        i = i + 1;
                    }
                }
            })

        });

        $("#ajaxModel").on('hidden.bs.modal', function() {

            $('#tablaModal>tbody>tr').remove();

        });
    </script>

    @stop