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
        <div class="card-header bg-success text-light" style="text-align: center;">
            <h2>Gesti&oacute;n de actividades</h2>
        </div>
            <div class="card-body">
            <div class="row">
                <div class="col-xs-5 col-sm-5 col-md-5">
                <form action="{{route('insert_actividad')}}" method="POST" enctype="multipart/form-data" id="form">
                @csrf
                    <div class="row">
                        <!--Inicio seccion izquierda-->
                        <!--Primera sección-->

                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Fecha creaci&oacute;n:</strong>
                                        <input type="text" class="form-control" id="fechacreacion" name="fechacreacion" value="{{$hoy}}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Turno:</strong>
                                        <input type="text" class="form-control" id="turno" name="turno" value="{{$consul}}" readonly>
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
                                    <input type="text" class="form-control" id="actividadcreador" value ="{{$user[0]->titulo . ' ' . $user[0]->nombre . ' ' . $user[0]->app . ' ' . $user[0]->apm}}" readonly>
                                    <input type="hidden" name="idusuario" value="{{$user[0]->idu}}">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Tipo de usuario-Detalle:</strong>
                                    <input type="text" class="form-control" id="tipodetalle" name="tipodetalle" value="{{$user[0]->tipo_usuario . ' - ' . $user[0]->nombre_areas}}" readonly>
                                    <input type="hidden" name="idar_areas" value="{{$user[0]->idar}}" >
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
                                <input type="text" class="form-control" id="comunicado" name="comunicado" required>
                            </div>
                        </div>
                    </div>
                    <!--fin Tercera sección-->
                    <!--Cuarta sección-->
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Asunto:</strong>
                                <input type="text" class="form-control" id="Asunto" name="Asunto" required>
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
                                <input class="form-control" type="date" name="fechainicio" id="fechainicio" required>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>Fecha de termino:</strong>
                                <input class="form-control" type="date" name="fechatermino" id="fechatermino" required>
                            </div>
                        </div>
                    </div>
                    <!--fin Sexta sección-->
                    <!--Septima sección-->
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>Hora de inicio:</strong>
                                <input class="form-control" type="time" name="horadeinicio" id="horadeinicio">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>Hora de termino:</strong>
                                <input class="form-control" type="time" name="horatermino" id="horatermino">
                            </div>
                        </div>
                    </div>
                    <!--fin Septima sección-->
                    <!--Octava sección-->
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Detalle de actividad:</strong>
                                <textarea  class="form-control" name="detalleactividad" id="detalleactividad" rows="3" required></textarea>
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
                            </div>
                        </div>
                        <div class="col-xs-1 col-sm-1 col-md-1">
                            <div class="form-group">
                                <br>
                                <button type="button" class="btn btn-primary" id="boton">+</button>
                            </div>
                        </div>
                        <div class="col-xs-11 col-sm-11 col-md-11">
                            <div class="form-group">
                                <strong>Link de soportes:</strong>
                                <input type="text" class="form-control" id="link" name="link">
                            </div>
                        </div>
                    </div>
                    <div class="row" id="oculto">

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
                                    <select class="form-control" name="tipousuario[]" id="tipousuario" multiple="multiple"  required>
                                    @foreach($tipous as $tu)
                                        <option value="{{$tu->idar}}">{{$tu->nombre}}</option>
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
					<input class="form-check-input" type="radio" name="estado" id="estado2" value="1" checked>
                                        <label class="form-check-label" for="estado2">Desarrollo</label>
                                      </div>
                                      <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="estado" id="estado" value="2" >
                                        <label class="form-check-label" for="estado">Concluida</label>
                                      </div>
                                      <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="estado" id="estado3" value="3">
                                        <label class="form-check-label" for="estado3">Cancelado</label>
                                      </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <strong>Importancia:</strong>
                                    <br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="importancia" id="importancia" value="Baja">
                                        <label class="form-check-label" for="importancia">Baja</label>
                                      </div>
                                      <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="importancia" id="importancia1" value="Media">
                                        <label class="form-check-label" for="importancia1">Media</label>
                                      </div>
                                      <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="importancia" id="importancia2" value="Alta" checked>
                                        <label class="form-check-label" for="importancia2">Alta</label>
                                      </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" id="button"class="btn btn-primary" readonly>Enviar</button>    
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


    $("#tipousuario").on('select2:select',function(e){
        
        $("#tipousuarioarea").attr("disabled", true);
        let tipo_u = $("#tipousuario").val();
        //console.log(tipo_u);
        /* $("#tipousuarioarea").empty(); */
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
                $("#tipousuarioarea").attr("disabled", false);

            },error:function(data){

                console.log(data);

            }
        });


    });



    $("#tipousuario").on("select2:unselecting", function(e) {
        $("#tipousuarioarea").empty();
        $(this).val(null);
    });
    
    $("#form").submit(function(event){
        
        $("#button").prop("disabled", true);
       
    });
</script>

@endsection
