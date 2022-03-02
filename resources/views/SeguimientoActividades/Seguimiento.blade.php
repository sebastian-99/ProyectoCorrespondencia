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
        document.getElementById('porcentaje').addEventListener('mousemove', porcentajeAvance, false);
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
    
</div>

<div class="card">
    <div class="card-body">
        <div class="row">
        <div class="col-sm-6">
        <h4 class="">Detalle del turno: {{$actividades->turno}}</h4>
    </div>
    <div class="col-sm-6">
        <h4 class="">Comunicado: {{$actividades->comunicado}}</h4>
    </div>
            <div class="col-sm-12">
               
                    <div class="d-md-flex align-items-center justify-content-between">
                        <h2 class="bd-title" id="content">{{$actividades->asunto}}</h2>
                    </div>
                    <p class="bd-lead">{{$actividades->descripcion}}</p>
                
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-8">

        <div class="card">
            <div class="card-body">
            <center>
                    <h4>Detalles de la actividad</h4>
                </center><br>
                <table class="table table-responsive table-striped">
                    <thead class="">
                        <tr style="background-color: #607d8b; color: #ffffff">
                            <th scope="col" style='width:200px'>Fecha de creación</th>
                            <th scope="col" style='width:250px'>Actividad creada por </th>
                            <th scope="col" style='width:190px'>Periodo de atención </th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>

                            <td> {{ Carbon\Carbon::parse($actividades->fecha_creacion)->locale('es')->isoFormat('D MMMM h:mm a') }}</td>
                            <td>{{$actividades->creador}} </td>
                            <td>
                                {{ Carbon\Carbon::parse($actividades->fecha_inicio)->locale('es')->isoFormat('D MMMM') }} al
                                {{ Carbon\Carbon::parse($actividades->fecha_fin)->locale('es')->isoFormat('D MMMM') }}
                            </td>
                        </tr>
                    </tbody>
                </table><br>

                <table class="table table-responsive table-striped">
                    <thead class="">
                        <tr style="background-color: #607d8b; color: #ffffff">
                            <th scope="col">Atendido por</th>
                            <th scope="col" style='width:250px'>Nombre atendió</th>
                            <th scope="col" style='width:150px'>Cargo</th>
                            <th scope="col">Nivel atención </th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{$atendido->atencion}} de {{$total_at->total}}</td>
                            <td>
                                @if(Auth()->user()->idtu_tipos_usuarios != 4)
                                    {{Auth()->user()->titulo}} {{Auth()->user()->nombre}} {{Auth()->user()->app}} {{Auth()->user()->apm}} 
                                @endif
                                @if(Auth()->user()->idtu_tipos_usuarios == 4)
                                    {{$dir}} 
                                @endif
                            </td>
                            <td>{{$user->tipo_usuario . ' - ' . $user->nombre_areas}}</td>
                            <td>{{$actividades->importancia}}</td>

                        </tr>
                    </tbody>
                </table><br>
                <table class="table table-responsive table-striped">
                    <thead class="">
                        <tr style="background-color: #607d8b; color: #ffffff">
                        <th scope="col" style='width:214px'>Área responsable</th>
                        <th scope="col" style='width:214px'>Acuse de recibido</th>
                        <th scope="col" style='width:214px'>Tipo de actividad </th>
                            

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <td>{{$actividades->nombre_area}}</td>
                            <td>Si</td>
                            <td>{{$actividades->tipo_act}}</td>
                            
                           

                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    <div class="col-sm-4">
        <div class="card">
            <div class="card-body">
                <div class="bd-intro ps-lg-4">
                    <div class="d-md-flex align-items-center justify-content-between">
                    <center>
                    <h4>Avance de actividad</h4>
                    </center><br><br><br><br>
                    </div>
                    <div class='btn-group me-2' role='group' aria-label='Second group'>
                    <p class="bd-lead">
                        <h6>Avance individual: </h6>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="form-control form-control-sm" style='width: 40px;' disabled type="text" id="porc_ind" value="{{$max_ai->avance_i}}">%
                    </p></div><br><br><br>
                    <div class='btn-group me-2' role='group' aria-label='Second group'>
                    <p class="bd-lead">
                       <h6>Avance total:</h6>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="form-control form-control-sm" style='width: 40px;' disabled type="text"  value="{{$general}}">%
                    </p></div>
                    
                    
                    <br><br>
                    <p class="bd-lead">
                        <center><h5>Estado de la actividad:</h5></center>
                    </p>
                    <p class="bd-lead">
                        <center><h6>{{$est_act}}</h6></center>
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
<div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <center>
                    <h4>Archivos de la actividad</h4>
                </center><br>
                <table class="table table-responsive table-striped">
                    <thead class="">
                        <tr style="background-color: #607d8b; color: #ffffff">
                            <th scope="col" style='width:33.3%'>Archivo</th>
                            <th scope="col" style='width:33.3%'>Nombre del archivo</th>
                            <th scope="col" style='width:33.3%'>Detalle (link)</th>
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
                            <td><a download={{$archivo1}} href="{{asset('archivos/').'/'.$actividades->archivo1}}" class="btn btn-sm btn-danger"><i class="fa fa-download"></i></a></td>
                            <td>{{$archivo1}}</td>
                            <td>
                            @if ($actividades->link1 != "Sin Link")
                                <a href="{{$actividades->link1}}" target="_blank">{{$actividades->link1}}</a>
                            @else
                            Este archivo no contiene un link de referencia
                            @endif    
                            </td>
                        </tr>
                        @endif
                        @if ($actividades->archivo2 != "Sin archivo")
                        <tr>
                            <td><a download="{{$archivo2}}" href="{{asset('archivos/').'/'.$actividades->archivo2}}" class="btn btn-sm btn-danger"><i class="fa fa-download"></i></a></td>
                            <td>{{$archivo2}}</td>
                            <td>
                            @if ($actividades->link2 != "Sin Link")
                                <a href="{{$actividades->link2}}" target="_blank">{{$actividades->link2}}</a>
                                @else
                            Este archivo no contiene un link de referencia
                            @endif    
                            </td>
                        </tr>
                        @endif

                        @if ($actividades->archivo3 != "Sin archivo")
                        <tr>
                            <td><a download="{{$archivo3}}" href="{{asset('archivos/').'/'.$actividades->archivo3}}" class="btn btn-sm btn-danger"><i class="fa fa-download"></i></a></td>
                            <td>{{$archivo3}}</td>
                            <td>
                            @if ($actividades->link3 != "Sin Link")
                                <a href="{{$actividades->link3}}" target="_blank">{{$actividades->link3}}</a>
                                @else
                            Este archivo no contiene un link de referencia
                            @endif    
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
          


            </div>
        </div>
       
    </div>
    @if(Auth()->user()->idtu_tipos_usuarios == 2 && $max_ai->avance_i != "100")                      
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <form action="{{route('AgregarSeguimiento')}}" method="POST" enctype="multipart/form-data" id="form">
                    @csrf
                    <input type="hidden" name="idac" value="{{$idac}}">
                    <!--<div class="col-sm-4">
                        <div class="mb-3">
                            <label for="NoSeguimiento" class="form-label">No. Seguimiento</label>
                            <input type="text" class="form-control form-control-sm" id="idseac" name="idseac">
                        </div>
                    </div>-->
                    <center>
                    <h4>Dar un nuevo seguimiento</h4>
                </center><br>
                    <input type="hidden" class="form-control form-control-sm" id="idreac" name="idreac_responsables_actividades" value="{{$resp->idreac}}">

                    <div class="col-sm-12">
                        <div class="mb-3">
                            <label for="" class="form-label">Seguimiento realizado por</label>
                             @if(Auth()->user()->idtu_tipos_usuarios == 2)
                                <input type="text" class="form-control form-control-sm" id="" value="{{Auth()->user()->titulo . ' ' . Auth()->user()->nombre . ' '  .Auth()->user()->app . ' ' . Auth()->user()->apm}}   /    {{$user->tipo_usuario . ' - ' . $user->nombre_areas}}" disabled>
                            @endif
                            @if(Auth()->user()->idtu_tipos_usuarios == 4)
                                <input type="text" class="form-control form-control-sm" id="" value="{{$dir}}  /    {{$user->tipo_usuario . ' - ' . $user->nombre_areas}}" disabled>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="mb-3">
                            <label for="fecha_seg" class="form-label">Fecha de Seguimiento</label> ( {{$now->locale('es')->isoFormat('DD MMMM h:mm')}} )
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
                            <input class="form-control-range" type="range" step="5" id="porcentaje" min="0" max="100" name="porcentaje" value="{{$max_ai->avance_i}}" onchange="verificar_p()">
                            <span id="porc">{{$max_ai->avance_i}}</span>%

                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado actividad</label><p>(Al marcar completo tu avance cambia a 100%)</p><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="estado" id="estado_p" value="Pendiente" checked>
                                <label class="form-check-label" for="inlineRadio1">Pendiente</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="estado" id="estado_c" value="Completo" onchange="verificar_s()">
                                <label class="form-check-label" for="inlineRadio2">Completo</label>
                            </div>
                            <div id="file_fin">
                                {{--- Aquí se agrega el archivo definalizacion del seguimiento---}}
                            </div>
                        </div>
                    </div>

                    
                    <div class="col-sm-12">
                        <div class="form-group">                     
                            <label for="archivo" class="form-label">Agregar archivos</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="btn btn-sm rounded-circle btn-success addfile" id="addfile"><i class='fa fa-plus-circle'></i></a>           
                        </div>
                    </div>             
                   
                        <table class="table table-responsive">           
                        <tr>
                            <div id="nuevoInputfile">
                                {{-- Aqui se van agregando más inputs type file para agregar varios archivos --}}
                            </div>
                        </tr>
                        </table>   
                           
                        
                    

                    <div class="col-sm-2">

                        <button type="submit" class="btn btn-sm btn-success" id="dar_seg">Guardar seguimiento</button>

                    </div>
                   
                </form>
            </div>
        </div>
    </div>
    @endif
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
                <zing-grid lang="custom" caption='Reporte de seguimientos' sort search pager page-size='10' page-size-options='5,10,20,30' layout='row' viewport-stop theme='android' id='zing-grid' filter  selector data="{{$json_sa}}">
                    <zg-colgroup>
                        <zg-column index='idseac' header='No. Seguimiento' width="" type='text'></zg-column>
                        <zg-column index='detalle' header='Detalle' width="300" type='text'></zg-column>
                        <zg-column index='fecha' header='Fecha de avance' width="200" type='text'></zg-column>
                        <zg-column index='estado' header='Estatus' width="200" type='text'></zg-column>
                        <zg-column index='porcentaje' header='% Avance' width="150" type='text'></zg-column>
                        <zg-column index='evidencia' filter="disabled" header='Evidencia' width="150" type='text'></zg-column>
                    </zg-colgroup>
                </zing-grid>

            </div>
        </div>
    </div>

</div>

<!-- Modal-->
<div class="modal fade" id="ajaxModel" value="1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#198754; color: #ffffff">
                <h4 class="modal-title" id="modelHeading"></h4><p id="fecha_info"></p>
            </div>
            <div class="modal-body">
                <form id="DetallesArchivos" name="DetallesArchivos" class="form-horzontal">
                    <div class="card-body">
                        <div class="table-responsive">
                         <div id="det_seg"></div>   
                            <table class="table table-sm table-striped table-bordered" id="tablaModal">
                                <thead class="text-center">
                                    <tr style="background-color: #858FA3; color: #ffffff">
                                        <th scope="col">Nombre </th>
                                        <th scope="col">Detalle de evidencia</th>
                                        <th scope="col"></th>
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
    var fname=null;
    var dname=null;
    
    //comprobar si el porcentaje de avance es igual 100% marcar estado completado
    function verificar_p() {
            var vp = document.getElementById("porc_ind").value;
            var verif_p = document.getElementById("porcentaje").value;
            
            if (verif_p == 100) {
                $('#estado_c').prop("checked", true);
                $('#estado_p').prop('disabled', true);
                var f_f = "<input type='file' id='' name='archivo_fin' class='form-control form-control-sm' required>";
                $('#file_fin').append("<br><label class='form-label'>Antes de marcar la actividad como completada sube tu oficio de término de actividad</label>"+f_f);
            } else {
                $('#estado_p').prop("checked", true);
                $('#estado_p').prop('disabled', false);
                $('#file_fin').empty();

            }
            
            if(Number(verif_p) <= Number(vp)){
                alert('El porcentaje no puede ser menor que el ultimo');
                $('#porc').html(vp);
                $('#porcentaje').val(vp);
                //console.log(verif_p);
                //console.log(vp);
            }
            
        }
        function verificar_s() {
            var verif_s = document.getElementById("estado_c").value;
            var verif_sp = document.getElementById("estado_p").value;
            if (verif_s == 'Completo') {
                $('#porcentaje').val(100);
                $('#porc').html(100);
                $('#estado_p').prop('disabled', true);
                var f_f = "<input type='file' id='' name='archivo_fin' class='form-control form-control-sm' required>";
                $('#file_fin').append("<br><label class='form-label'>Antes de marcar la actividad como completada sube tu oficio de término de actividad</label>"+f_f);
            }
        }
    //Agregar mas archivos en nueva seccion ------------------------------------------------------------------
    var f = 1;
    
    $('body').on('click', '#addfile',function(){
        
           
        var newInputFile = "<tr><td style='width:450px'><label for='archivo"+f+"' class='form-label'>Seleccione un archivo </label><input type='file' id='archivo"+f+"' name='ruta[]' class='form-control form-control-sm' required></td><td style='width:600px'><label for='archivo"+f+"' class='form-label'>Detalle de evidencia </label><input type='text' id='detalle_a"+f+"' placeholder='Escribe el detalle del archivo' name='detalle_a[]' class='form-control form-control-sm'  required></td><td><BR><a href='javascript:void(0)' class='btn btn-sm rounded-circle btn-danger borrar'><i class='fa fa-trash'></i></a></td></tr>" ;
        $('#nuevoInputfile').append(newInputFile);
            
        f=f+1;   
    });
     
    $(document).on('click', '.borrar', function (event) {
    event.preventDefault();
    $(this).closest('tr').remove();
        });
    
// ------------------------------------------------------------------------------------------------
$("#form").submit(function(event){
        
        $("#dar_seg").prop("disabled", true);
       
    });
 //--------------------------------------------------------------------------------------------------------------
    $('body').on('click', '.DetallesArchivos',function(){
      var id = $(this).data('id');
     
      var i = 0;
      $.get("../DetallesArchivos/" + id, function(data){
        $('#tablaModal>tbody>tr').remove();
       while ( i!=1+i){
         if(!data[i]){
           i=i+1;
           break;
         }else{
      
        $('#modelHeading').html("Detalles de tus archivos subidos");
        $('#fecha_info').html("<p>" + '  ( ' + data[i].fecha + ' )' + "</p>");
        $('#det_seg').html("<h4>" + data[0].detalle + "</h4><br>");
        $('#ajaxModel').modal('show');
        var nombre = "<td><input id='nombre"+i+"' name='nombre"+i+"'  style='width:100%' disabled class='form-control form-control-sm'></td>"
        var detalle = "<td><input id='detalle"+i+"' name='detalle"+i+"' style='width:100%' disabled class='form-control form-control-sm'></td>"
        if(data[i].ruta == 'Sin archivo'){
        var texto = '<td>No hay archivos disponibles</td>';
        $('#tablaModal>tbody').append("<tr>"+nombre+texto+"</tr>");
        $('#nombre'+i).val(data[i].nombre);
        $('#detalle'+i).val(data[i].detalle);
        $('#ruta'+i).val(ruta);
        $('#ruta'+i).attr('href',archivo);
        $('#ruta'+i).text(texto);
        }else if(data[i].ruta != '' ){
          var ruta = "<td><a download id='ruta"+i+"' name='ruta"+i+"'class='btn btn-sm btn-danger' ><i class='fa fa-download'></i></a></td>"
        var archivo = '{{asset(('archivos/Seguimientos'))}}/'+data[i].ruta;
        $('#tablaModal>tbody').append("<tr>"+nombre+detalle+ruta+"</tr>");
        $('#nombre'+i).val(data[i].nombre);
        $('#detalle'+i).val(data[i].detalle_a);
        $('#ruta'+i).val(ruta);
        $('#ruta'+i).attr('href',archivo);
        $('#ruta'+i).attr('download',data[i].nombre);
        $('#ruta'+i).text(texto);
        }
       
     
  
        i=i+1;
         }
        }
      })
      
    });
    $("#ajaxModel").on('hidden.bs.modal', function () {
        
              $('#tablaModal>tbody>tr').remove();
            
    });

//----------------------------------
    
</script>

    @stop