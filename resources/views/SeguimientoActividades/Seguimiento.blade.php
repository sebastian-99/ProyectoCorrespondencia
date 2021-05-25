@extends('layout.layout')
@section('content')

<div class="row">
    <div class="col-sm-4">
        <h3>Detalle del turno: 143</h3>
    </div>
    <div class="col-sm-4">
        <h3>Comunicado: 62235</h3>
    </div>
    <div class="col-sm-4">
        <h3>Oficio: UTVT/SEP/000011</h3>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12"><br>
                <table class="table table-responsive">
                    <thead class="thead-light">
                        <tr>
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
                            <th scope="row">143</th>
                            <td>10-03-2021</td>
                            <td>Acreditacion TIC</td>
                            <td>Carlos Millan Hinojosa</td>
                            <td>12-03-2021 al 25-03-2021</td>
                            <td>Alta</td>
                            <td>Direccion de carrera TIC</td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-responsive">
                    <thead class="thead-light">
                        <tr>
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
                <form action=""></form>
                {{csrf_field()}}
                <div class="col-sm-4">
                    <div class="mb-3">
                        <label for="NoSeguimiento" class="form-label">No. Seguimiento</label>
                        <input type="text" class="form-control form-control-sm" id="NoSeguimiento" placeholder="1" disabled>
                    </div>
                </div>
                <div class="col-sm-10">
                    <div class="mb-3">
                        <label for="NoSeguimiento" class="form-label">Actividad creada por</label>
                        <input type="text" class="form-control form-control-sm" id="NoSeguimiento" value="Lic. Roberto Torres Martinez" disabled>
                    </div>
                </div>
                <div class="col-sm-10">
                    <div class="mb-3">
                        <label for="NoSeguimiento" class="form-label">Tipo usuario (Detalle)</label>
                        <input type="text" class="form-control form-control-sm" id="NoSeguimiento" value="Direccion de carrera - TIC" disabled>
                    </div>
                </div>
                <div class="col-sm-10">
                    <div class="mb-3">
                        <label for="NoSeguimiento" class="form-label">Fecha de Seguimiento</label>
                        <input type="text" class="form-control form-control-sm" id="NoSeguimiento" value="24-04-2021" disabled>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="mb-3">
                        <label for="NoSeguimiento" class="form-label">Detalle de la actividad</label>
                        <div class="form-floating">
                            <textarea class="form-control" placeholder="Detalle de la actividad" id="floatingTextarea"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-5">
                        <div class="mb-3">
                            <label for="NoSeguimiento" class="form-label">Porcentaje Avance</label>
                            <span class="input-group-text"><input type="text" class="form-control form-control-sm" id="NoSeguimiento" value=""> %</span>
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <div class="mb-3">
                            <label for="NoSeguimiento" class="form-label">Estado Actividad</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                                <label class="form-check-label" for="inlineRadio1">Completo</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                                <label class="form-check-label" for="inlineRadio2">Pendiente</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-10">

                    <label for="formFileSm" class="form-label">Seleccione Archivo</label>
                    <input class="form-group form-group-sm" id="formFileSm" type="file">

                </div>


                <div class="col-sm-10">
                    <div class="mb-3">
                        <label for="NoSeguimiento" class="form-label">Detalle Evidencia</label>
                        <input type="text" class="form-control form-control-sm" id="NoSeguimiento" value="" placeholder="Detalle de la evidencia">
                    </div>
                </div>
                <div class="col-sm-2">

                    <button class="btn btn-sm btn-success">+</button>

                </div>



            </div>
        </div>
    </div>

    <div class="col-sm-7">
        <div class="card">
            <div class="card-body">
                <center>
                    <h4>Seguimiento de la actividad</h4>
                </center><br>
                <table class="table table-responsive">
                    <thead class="thead-light">
                        <tr>
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
                            <th scope="row">1</th>
                            <td>10-21-2021</td>
                            <td>Detalle de actividad 1</td>
                            <td>Pendiente</td>
                            <td>30%</td>
                            <td>Sin archivos
                                <button class="btn btn-danger">-</button>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

@stop