@extends('layout.layout')
@section('content')
<style type="text/css">
    html {
 box-sizing: border-box;
}
*,
*::before,
*::after {
 box-sizing: inherit;
 margin: 0;
 padding: 0;
}
.row {
 display: flex;
}
.sep {
 display: flex;
 flex-direction: column;
 justify-content: center;
}
.sepText {
 display: flex;
 flex-direction: column;
 justify-content: center;
 align-items: center;
 flex: 1;
}
.sepText::before,
.sepText::after {
 content: '';
 flex: 1;
 width: 1px;
 background: #FFCA28;
 /* matches font color */
 margin: .25em;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #e9830e;
        color: white;

    }

    .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice__remove {
        color: #000000;
        cursor: pointer;
        display: inline-block;
        font-weight: bold;
        margin-right: 2px;
    }

    .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
        background-color: #416CC3;
        color: white;
        border: 1px solid black;
        border-radius: 0.2rem;
        padding: 0;
        padding-right: 5px;
        cursor: pointer;
        float: left;
        margin-top: 0.3em;
        margin-right: 5px;
    }
</style>

    <div class="card">
        <div class="card-header" style="background: #FFCA28; color:#ffff; text-align: center;">
            <strong>Gesti&oacute;n de Actividades</strong>
        </div>
            <div class="card-body">
            <div class="row">
                <div class="col-xs-5 col-sm-5 col-md-5">
                <form action="{{route('insert_actividad')}}" method="POST" enctype="multipart/form-data">
                @csrf
                    <div class="row">
                        <!--Inicio seccion izquierda-->
                        <!--Primera sección-->
                    
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Fecha creaci&oacute;n:</strong>
                                        <input type="text" class="form-control" id="fechacreacion" name="fechacreacion" value="{{$consul[0]->fecha_creacion}}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Turno:</strong>
                                        <input type="text" class="form-control" id="turno" name="turno" value="{{$consul[0]->turno}}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--fin primera sección-->
                    <!--Segunda sección-->
                    <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Actividad Creada por:</strong>
                                    <input type="text" class="form-control" id="actividadcreador" value ="{{$consul[0]->titulo. ' ' . $consul[0]->nombre . ' ' . $consul[0]->app . ' ' . $consul[0]->apm}}" readonly>
                                    
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Tipo de usuario - Detalle:</strong>
                                    <input type="text" class="form-control" id="tipodetalle" name="tipodetalle" value="Administrador - Dirección de area de control escolar" readonly>
                                    
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    </div>
                    <!--fin Segunda sección-->
                    <!--Tercera sección-->
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>#Comunicado:</strong>
                                <input type="text" class="form-control" id="comunicado" name="comunicado" value="{{$consul[0]->comunicado}}" required>
                            </div>
                        </div>
                    </div>
                    <!--fin Tercera sección-->
                    <!--Cuarta sección-->
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Asunto:</strong>
                                <input type="text" class="form-control" id="Asunto" name="Asunto" value="{{$consul[0]->asunto}}" required>
                            </div>
                        </div>
                    </div>
                    <!--fin Cuarta sección-->
                    <!--Quinta sección-->
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Tipo actividad:</strong>
                                <select class="form-control" name="tipoactividad" id="tipoactividad">
                                    @foreach($tipo_actividad as $tipo)
                                        <option value="{{$tipo->idtac}}">{{$tipo->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <!--fin Quinta sección-->
                    <!--Sexta sección-->
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>Fecha de inicio:</strong>
                                <input class="form-control" type="date" name="fechainicio" id="fechainicio" value="{{$consul[0]->fecha_inicio}}" required>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>Fecha de termino:</strong>
                                <input class="form-control" type="date" name="fechatermino" id="fechatermino" value="{{$consul[0]->fecha_fin}}" required>
                            </div>
                        </div>
                    </div>
                    <!--fin Sexta sección-->
                    <!--Septima sección-->
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>Hora de inicio:</strong>
                                <input class="form-control" type="time" name="horadeinicio" id="horadeinicio" value="{{$consul[0]->hora_inicio}}" required>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>Hora de termino:</strong>
                                <input class="form-control" type="time" name="horatermino" id="horatermino" value="{{$consul[0]->hora_fin}}" required>
                            </div>
                        </div>
                    </div>
                    <!--fin Septima sección-->
                    <!--Octava sección-->
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Detalle de actividad:</strong>
                                <textarea  class="form-control" name="detalleactividad" id="detalleactividad" rows="3" required>{{$consul[0]->descripcion}}</textarea>
                            </div>
                        </div>
                    </div>
                    <!--Fin Octava sección-->
                    <!--Novena sección-->
                    <div class="row">
                        <div class="col-xs-11 col-sm-11 col-md-11">
                            <div class="form-group">                                
                                <strong>Archivos soporte:</strong>
                                <input type="file" class="form-control" id="arvhivos" name="archivos">
                                @if($consul[0]->archivo1 != null)
                                    <label for="">{{$consul[0]->archivo1}}</label>
                                @endif
                                             
                            </div>
                        </div>
                        <div class="col-xs-1 col-sm-1 col-md-1">
                            <div class="form-group">
                                <br>
                            </div>
                        </div>
                        <div class="col-xs-11 col-sm-11 col-md-11">
                            <div class="form-group">                            
                                <strong>Link de soportes:</strong>
                                <input type="text" class="form-control" id="link" name="link" value="{{$consul[0]->link1}}">
                            </div>
                        </div> 
                    </div>
                    <div class="row" id="oculto">
                        <div class="col-xs-11 col-sm-11 col-md-11">
                            <div class="form-group">                                
                                <strong>Archivos soporte:</strong>
                                <input type="file" class="form-control" id="archivos" name="archivos2">
                                @if($consul[0]->archivo2 != null)
                                    <label for="">{{$consul[0]->archivo2}}</label>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-xs-11 col-sm-11 col-md-11">
                            <div class="form-group">                            
                                <strong>Link de soportes:</strong>
                                <input type="text" class="form-control" id="link" name="link2" value="{{$consul[0]->link2}}">
                            </div>
                        </div>
                        <div class="col-xs-11 col-sm-11 col-md-11">
                            <div class="form-group">                                
                                <strong>Archivos soporte:</strong>
                                <input type="file" class="form-control" id="archivos" name="archivos3">
                                @if($consul[0]->archivo3 != null)
                                    <label for="">{{$consul[0]->archivo3}}</label>
                                @endif                   
                            </div>
                        </div>
                        
                        <div class="col-xs-11 col-sm-11 col-md-11">
                            <div class="form-group">                            
                                <strong>Link de soportes:</strong>
                                <input type="text" class="form-control" id="link" name="link3" value="{{$consul[0]->link3}}">
                            </div>
                        </div>
                        
                    </div>
                    <!--Fin Novena sección-->
                </div>
                <div class="col-xs-1 col-sm-1 col-md-1 sep">
                    <span class="sepText">
                                    
                                </span>
              
                  </div>
                    <!--Parte derecha-->
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Selccione participantes:</strong>
                                    <br>
                                    <label>Tipo de usuario:</label>
                                    <select class="form-control" name="tipousuario[]" id="tipousuario" multiple="multiple" required>
                                    @foreach($tipous as $tu)
                                        <option selected value="{{$tu->idar}}">{{$tu->nombre}}</option>
                                    @endforeach                    
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Selccione usuarios de las area:</strong>
                                    <br>
                                    <label>&nbsp;</label>
                                    <select class="form-control" name="tipousuarioarea[]" id="tipousuarioarea" multiple="multiple" required>
                                        @foreach($users as $tu)
                                            <option selected value="{{$tu->idu}}">{{$tu->usuario}}</option>
                                        @endforeach 
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <strong>Estado actividad:</strong>
                                    <br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="estado" id="estado" value="1" {{($consul[0]->status == 1)? 'checked' : ''}}>
                                        <label class="form-check-label" for="estado">Activo</label>
                                      </div>
                                      <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="estado" id="estado2" value="2" {{($consul[0]->status == 2)? 'checked' : ''}}>
                                        <label class="form-check-label" for="estado2">Desarrollo</label>
                                      </div>
                                      <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="estado" id="estado3" value="3" {{($consul[0]->status == 3)? 'checked' : ''}}>
                                        <label class="form-check-label" for="estado3">Cancelado</label>
                                      </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <strong>Importancia:</strong>
                                    <br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="importancia" id="importancia" value="Baja" {{($consul[0]->importancia == "Baja")? 'checked' : ''}}>
                                        <label class="form-check-label" for="importancia">baja</label>
                                      </div>
                                      <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="importancia" id="importancia1" value="Media" {{($consul[0]->importancia == "Media")? 'checked' : ''}}>
                                        <label class="form-check-label" for="importancia1">Media</label>
                                      </div>
                                      <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="importancia" id="importancia2" value="Alta" {{($consul[0]->importancia == "Alta")? 'checked' : ''}}>
                                        <label class="form-check-label" for="importancia2">Alta</label>
                                      </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Enviar</button>    
                        </div>
                    </div>
                
            </div>
            </form>
        </div>
                
        
    </div>

<script>
/* Incrementorio de campos */

    let suma = 1;
    console.log(suma);
    $("#boton").on('click',function(){

        suma = suma + 1;
        console.log(suma);
        
        $("#oculto").append(`<div class="col-xs-11 col-sm-11 col-md-11">
                            <div class="form-group">                                
                                <strong>Archivos soporte:</strong>
                                <input type="file" class="form-control" id="archivos" name="archivos${suma}">                    
                            </div>
                        </div>
                        
                        <div class="col-xs-11 col-sm-11 col-md-11">
                            <div class="form-group">                            
                                <strong>Link de soportes:</strong>
                                <input type="text" class="form-control" id="link" name="link${suma}">
                            </div>
                        </div> `);
        if(suma == 3){
            $("#boton").attr('disabled',true);
        }
    });


/* Instancia de Select2 */

     $("#tipousuario").select2({
        closeOnSelect : false,
        
      });

     $("#tipousuarioarea").select2({
        closeOnSelect : false,
        
      });


    $("#tipousuario").on('change',function(e){
        let tipo_u = $("#tipousuario").val();
        //console.log(tipo_u);
        $("#tipousuarioarea").empty();
        $.ajax({
            type:'GET',
            data:{
                tipo_u:tipo_u
            },
            url : "{{route('ajax_tipousuarios')}}",
            success:function(data){

               
               
                for(let i = data.length - 1; i >= 0; i--){
                    
                    $("#tipousuarioarea").append(`<option value="${data[i].idu}">${data[i].titulo} ${data[i].nombre} ${data[i].app} ${data[i].apm} - ${data[i].areas}</option>`).trigger('change')
                    
                }

            },error:function(data){

                console.log(data);

            }
        });


    });

   
    
    
    
</script>
    
@endsection