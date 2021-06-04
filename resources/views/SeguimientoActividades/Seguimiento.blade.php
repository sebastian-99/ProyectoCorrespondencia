@extends('layout.layout')
@section('content')
<input type="hidden" value="{{$consulta->idac}}" name="idac">

<div class="row">
    <div class="col-sm-4">
        <h3>Detalle del turno: {{$consulta->turno}}</h3>
    </div>
    <div class="col-sm-4">
        <h3>Comunicado: {{$consulta->comunicado}}</h3>
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
                            <th scope="row">{{$consulta->turno}}</th>
                            <td>{{$consulta->fecha_creacion}}</td>
                            <td>{{$consulta->asunto}}</td>
                            <td>{{$consulta->titulo}} {{$consulta->nombre}} {{$consulta->app}} {{$consulta->apm}}</td>
                            <td>{{$consulta->fecha_inicio}} - al - {{$consulta->fecha_fin}}</td>
                            <td>{{$consulta->importancia}}</td>
                            <td>{{$consulta->nombre_area}}</td>
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
                            <td>Carlos Millan Hinojosa</td>
                            <td>Director de Carrera TIC</td>
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
                    <div class="col-sm-10">
                        <div class="mb-3">
                            <label for="" class="form-label">Actividad creada por</label>
                            <input type="text" class="form-control form-control-sm" id="" value="Lic. Roberto Torres Martinez" disabled>
                        </div>
                    </div>
                    <div class="col-sm-10">
                        <div class="mb-3">
                            <label for="tipo_usuario" class="form-label">Tipo usuario (Detalle)</label>
                            <input type="text" class="form-control form-control-sm" id="tipo_usuario" value="Direccion de carrera - TIC" disabled>
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
                        <input class="form-group form-group-sm" id="formFileSm" name="ruta" type="file">

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
                        <tr>
                            <td colspan="5">Aun no hay un seguimiento de esta actividad por el usuario.</td>
                            <!-- <th scope="row">1</th>
                            <td>10-21-2021</td>
                            <td>Detalle de actividad 1</td>
                            <td>Pendiente</td>
                            <td>30%</td>
                            <td>Sin archivos 
                                <button class="btn btn-danger">-</button>
                            </td>-->
                        </tr>
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
                        @if($consulta->archivo1 == "Sin archivo" && $consulta->archivo2 == "Sin archivo" && $consulta->archivo3 == "Sin archivo" )
                        <tr>
                            <td colspan="3">Esta actividad no contiene archivos para atender.</td>                          
                        </tr>
                        @endif
                        @if ($consulta->archivo1 != "Sin archivo")
                        <tr>
                            <td>{{$consulta->archivo1}}</td>
                            <td>{{$consulta->link1}}</td>
                            <td><a download href="{{asset('archivos/').'/'.$consulta->archivo1}}" class="btn btn-danger" ><i class="fa fa-file"></i></a></td>
                        </tr>
                        @endif
                        @if ($consulta->archivo2 != "Sin archivo")
                        <tr>
                            <td>{{$consulta->archivo2}}</td>
                            <td>{{$consulta->link2}}</td>
                            <td><a download="" href="{{asset('archivos/').'/'.$consulta->archivo2}}" class="btn btn-danger" ><i class="fa fa-file"></i></a></td>
                        </tr>
                        @endif

                        @if ($consulta->archivo3 != "Sin archivo")
                        <tr>
                            <td>{{$consulta->archivo1}}</td>
                            <td>{{$consulta->link3}}</td>
                            <td><a download="{{$consulta->archivo3}}" href="{{asset('archivos/').'/'.$consulta->archivo3}}" class="btn btn-danger" ><i class="fa fa-file"></i></a></td>
                        </tr>
                        @endif
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    </div>
    

@stop