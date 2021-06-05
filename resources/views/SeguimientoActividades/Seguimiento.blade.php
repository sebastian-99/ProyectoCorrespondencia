@extends('layout.layout')
@section('content')
<input type="hidden" value="{{$actividades->idac}}" name="idac">

<div class="row">
    <div class="col-sm-4">
        <h3>Detalle del turno: {{$actividades->turno}}</h3>
    </div>
    <div class="col-sm-4">
        <h3>Comunicado: {{$actividades->comunicado}}</h3>
    </div>
    <div class="col-sm-4">
        <h3>Oficio: UTVT/SEP/000011</h3>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12"><br>
                <table class="table table-responsive table-striped">
                    <thead class="">
                        <tr class="bg-primary">
                            <th scope="col">Turno</th>
                            <th scope="col">Fecha Creación</th>
                            <th scope="col">Asunto</th>
                            <th scope="col">Creado por </th>
                            <th scope="col">Periodo atención </th>
                            <th scope="col">Nivel atención </th>
                            <th scope="col">Área Responsable</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">{{$actividades->turno}}</th>
                            <td>{{$actividades->fecha_creacion}}</td>
                            <td>{{$actividades->asunto}}</td>
                            <td>{{$actividades->titulo}} {{$actividades->nombre}} {{$actividades->app}} {{$actividades->apm}}</td>
                            <td>{{$actividades->fecha_inicio}} - al - {{$actividades->fecha_fin}}</td>
                            <td>{{$actividades->importancia}}</td>
                            <td>{{$actividades->nombre_area}}</td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-responsive table-striped">
                    <thead class="">
                        <tr class="bg-primary">
                            <th scope="col">Avance</th>
                            <th scope="col">Atendido por</th>
                            <th scope="col">Nombre atendió</th>
                            <th scope="col">Cargo</th>
                            <th scope="col">Avance indv.</th>
                            <th scope="col">Status Atención</th>
                            <th scope="col">Acuse Recibido</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">80%</th>
                            <td>3 de 3</td>
                            <td>{{Auth()->user()->titulo . ' ' . Auth()->user()->nombre . ' '  .Auth()->user()->app . ' ' . Auth()->user()->apm}}</td>
                            <td>{{$user->tipo_usuario . ' - ' . $user->nombre_areas}}</td>
                            <td>100%</td>
                            <td>En tiempo</td>
                            <td>Si</td>
                        </tr>
                    </tbody>
                </table>
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

                        <label for="formFileSm" class="form-label">Seleccione Archivo</label>
                        <input class="form-group form-group-sm" id="formFileSm" name="ruta" type="file" multiple>

                    </div>


                    <div class="col-sm-10">
                        <div class="mb-3">
                            <label for="detalle" class="form-label">Detalle Evidencia</label>
                            <input type="text" class="form-control form-control-sm" id="detalle" name="detalle" placeholder="Detalle de la evidencia">
                        </div>
                    </div>
                    <div class="col-sm-2">

                        <button class="btn btn-sm btn-success">+</button>

                    </div>

                </form>



            </div>
        </div>
    </div>

    <div class="col-sm-7">
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
                <table class="table table-responsive">
                    <thead class="">
                        <tr class="bg-primary">
                            <th scope="col">No. Segui.</th>
                            <th scope="col">Fecha avance</th>
                            <th scope="col">Detalle</th>
                            <th scope="col">Status</th>
                            <th scope="col">% Avance</th>
                            <th scope="col">Archivos Evidencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($seguimientos as $seg)
                        <tr>
                            <td>{{$seg->idseac}}</td>
                            <td>{{$seg->fecha}}</td>
                            <td>{{$seg->detalle}}</td>
                            <td>{{$seg->estado}}</td>
                            <td>{{$seg->porcentaje}}</td>
                            <td><a href="" class="btn btn-danger"><i class="fa fa-trash"></i></a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <center>
                    <h4>Archivos de la actividad</h4>
                </center><br>
                <table class="table table-responsive table-striped">
                    <thead class="">
                        <tr class="bg-primary">
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
                            <td><a download href="{{asset('archivos/').'/'.$actividades->archivo1}}" class="btn btn-danger"><i class="fa fa-file"></i></a></td>
                        </tr>
                        @endif
                        @if ($actividades->archivo2 != "Sin archivo")
                        <tr>
                            <td>{{$actividades->archivo2}}</td>
                            <td>{{$actividades->link2}}</td>
                            <td><a download="" href="{{asset('archivos/').'/'.$actividades->archivo2}}" class="btn btn-danger"><i class="fa fa-file"></i></a></td>
                        </tr>
                        @endif

                        @if ($actividades->archivo3 != "Sin archivo")
                        <tr>
                            <td>{{$actividades->archivo1}}</td>
                            <td>{{$actividades->link3}}</td>
                            <td><a download="{{$actividades->archivo3}}" href="{{asset('archivos/').'/'.$actividades->archivo3}}" class="btn btn-danger"><i class="fa fa-file"></i></a></td>
                        </tr>
                        @endif
                    </tbody>
                </table>

            </div>
        </div>
    </div>

</div>


@stop