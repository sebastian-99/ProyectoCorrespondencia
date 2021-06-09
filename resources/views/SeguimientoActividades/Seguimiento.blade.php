@extends('layout.layout')
@section('content')

@section('header')

<script src='{{asset('src/js/zinggrid.min.js')}}'></script>
<script src='{{asset('src/js/zinggrid-es.js')}}'></script>
<script>
    if (es) ZingGrid.registerLanguage(es, 'custom');
</script>

@endsection

<input type="hidden" value="{{$actividades->idac}}" name="idac">

<div class="row">
    <div class="col-sm-4">
        <h3 class="display-6">Detalle del turno: {{$actividades->turno}}</h3>
    </div>
    <div class="col-sm-4">
        <h3 class="display-6">Comunicado: {{$actividades->comunicado}}</h3>
    </div>
    <div class="col-sm-4">
        <h3 class="display-6">Oficio: UTVT/SEP/000011</h3>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="bd-intro ps-lg-4">
                    <div class="d-md-flex flex-md-row-reverse align-items-center justify-content-between">
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
                        <tr class="bg-secondary">
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

                            <td> {{ Carbon\Carbon::parse($actividades->fecha_creacion)->locale('es')->isoFormat('D MMMM') }}</td>
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
                        <tr class="bg-secondary">
                            <th scope="col">Atendido por</th>
                            <th scope="col">Nombre atendió</th>
                            <th scope="col">Cargo</th>
                            <th scope="col">Acuse Recibido</th>
                            <th scope="col">Nivel atención </th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2</td>
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
                        <h3 class="bd-title">Avance</h3>
                    </div>
                    <p class="bd-lead">General</p>
                    <p class="bd-lead">Individual</p>
                    <div class="d-md-flex align-items-center justify-content-between">
                        <h3 class="bd-title">Status atención</h3>
                    </div>
                    <p class="bd-lead">En tiempo</p>


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

                    <div class="col-sm-10">
                        <div class="mb-3">
                            <label for="" class="form-label">Seguimiento realizado por</label>
                            <input type="text" class="form-control form-control-sm" id="" value="{{Auth()->user()->titulo . ' ' . Auth()->user()->nombre . ' '  .Auth()->user()->app . ' ' . Auth()->user()->apm}}" disabled>
                        </div>
                    </div>
                    <div class="col-sm-10">
                        <div class="mb-3">
                            <label for="tipo_usuario" class="form-label">Tipo usuario (Detalle)</label>
                            <input type="text" class="form-control form-control-sm" id="tipo_usuario" value="{{$user->tipo_usuario . ' - ' . $user->nombre_areas}}" disabled>
                        </div>
                    </div>
                    <div class="col-sm-10">
                        <div class="mb-3">
                            <label for="fecha_seg" class="form-label">Fecha de Seguimiento</label>
                            <input type="text" class="form-control form-control-sm" id="fecha_seg" name="fecha" value="{{$now->format('d-m-y')}}" disabled>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="mb-3">
                            <label for="detalle" class="form-label">Detalle de la actividad</label>
                            <div class="form-floating">
                                <textarea class="form-control" name="detalle" placeholder="Detalle de la actividad" id="detalle"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="mb-3">
                                <label for="porcentaje" class="form-label">Porcentaje Avance</label>
                                <span class="input-group-text"><input type="text" class="form-control form-control-sm" id="porcentaje" name="porcentaje"> %</span>
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado Actividad</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="estado" id="inlineRadio1" value="Pendiente">
                                    <label class="form-check-label" for="inlineRadio1">Pendiente</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="estado" id="inlineRadio2" value="Completo">
                                    <label class="form-check-label" for="inlineRadio2">Completo</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-10">

                        <label for="ruta[]" class="form-label">Seleccione Archivo</label>
                        <input class="form-group form-group-sm" id="ruta[]" name="ruta[]" type="file" multiple>

                    </div>


                    <div class="col-sm-10">
                        <div class="mb-3">
                            <label for="detalle" class="form-label">Detalle Evidencia</label>
                            <input type="text" class="form-control form-control-sm" id="detalle" name="detalle" placeholder="Detalle de la evidencia">
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
                        <tr class="bg-secondary">
                            <th scope="col">Nombre archivo</th>
                            <th scope="col">Detalle</th>
                            <th scope="col">Archivo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($actividades->archivo1 == "Sin archivo" && $actividades->archivo2 == "Sin archivo" && $actividades->archivo3 == "Sin archivo" )
                        <tr>
                            <td colspan="3">Esta actividad no contiene archivos para atender.</td>
                        </tr>
                        @endif
                        @if ($actividades->archivo1 != "Sin archivo")
                        <tr>
                            <td>{{$actividades->archivo1}}</td>
                            <td>{{$actividades->link1}}</td>
                            <td><a download href="{{asset('archivos/').'/'.$actividades->archivo1}}" class="btn btn-sm btn-danger"><i class="fa fa-download"></i></a></td>
                        </tr>
                        @endif
                        @if ($actividades->archivo2 != "Sin archivo")
                        <tr>
                            <td>{{$actividades->archivo2}}</td>
                            <td>{{$actividades->link2}}</td>
                            <td><a download="" href="{{asset('archivos/').'/'.$actividades->archivo2}}" class="btn btn-sm btn-danger"><i class="fa fa-download"></i></a></td>
                        </tr>
                        @endif

                        @if ($actividades->archivo3 != "Sin archivo")
                        <tr>
                            <td>{{$actividades->archivo1}}</td>
                            <td>{{$actividades->link3}}</td>
                            <td><a download="{{$actividades->archivo3}}" href="{{asset('archivos/').'/'.$actividades->archivo3}}" class="btn btn-sm btn-danger"><i class="fa fa-download"></i></a></td>
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
                    <h4>Seguimientos de la actividad</h4>
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
                <zing-grid lang="custom" caption='Reporte de oficios' sort search pager page-size='3' page-size-options='1,3,5,10' layout='row' viewport-stop theme='android' id='zing-grid' filter data="{{$json_sa}}">
                    <zg-colgroup>
                        <zg-column index='idseac' header='No- Seguimiento' width="100" type='text'></zg-column>
                        <zg-column index='detalle' header='Detalle' width="200" type='text'></zg-column>
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


    @stop