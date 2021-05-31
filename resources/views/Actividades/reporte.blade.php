@extends('layout.layout')
@section('content')
@section('header')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
@endsection
<div class="card">
    <div class="card-header">
<<<<<<< HEAD
      <h3>Reporte de actividades / oficios</h3>
     
=======
      <div class="row">
        <div class="col-sm-11">
          <h3>Reporte de actividades / oficios</h3>
        </div>
        <div class="col-sm-1">
         <a href="{{route('create_actividades')}}"><button class="btn btn-primary">Nuevo</button></a>
        </div>
      </div>
      
>>>>>>> 332fc7370bcd4c865e9041dfaf40090947a95280
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-bordered" id="tabla">
            <thead class="text-center">
              <tr style="background-color: #1F75FE; color: #ffffff">
                <th scope="col">Turno</th>
                <th scope="col">Fecha creaci&aacute;n</th>
                <th scope="col">Asunto</th>
                <th scope="col">Creado por:</th>
                <th scope="col">Periodo atenci&oacute;n</th>
                <th scope="col">Nivel de atenci&oacute;n</th>
                <th scope="col">Area responsable</th>
                <th scope="col">Avance</th>
                <th scope="col">Atendido por</th>
                <th scope="col">Operaciones</th>
              </tr>
            </thead>
            <tbody>
            @foreach ($consult as $c)
              <tr class="text-center">
                <td>{{$c->turno}}</td>
                <td>{{$c->fecha_creacion}}</td>
                <td>{{$c->asunto}}</td>
                <td>{{$c->creador}}</td>
                <td>{{$c->periodo}}</td>
                <td>{{$c->importancia}}</td>
                <td>{{$c->nombre}}</td>
                <td>100%</td>
                <td>2 de 3</td>
                <td>
<<<<<<< HEAD
                   
                <a href="javascript:void(0)" data-toggle="tooltip" data-id="{{ $c->idac}}"  data-original-title="Detalles" 
                     class="edit btn btn-success btn-sm Detalles">Detalles</a>
                    <a href=""><button type="button" class="btn btn-warning">Modificar</button></a>    
                    <a href=""><button type="button" class="btn btn-danger">Modificar</button></a>    
=======
                    <a href=""><button type="button" class="btn btn-success">Ver detalle</button></a>    
                    <a href="{{route('edit_modificacion', ['id' => encrypt($c->idac)])}}"><button type="button" class="btn btn-warning">Modificar</button></a>
                    @if($c->activo == 1)   
                      <a href="{{route('activacion', ['id' => encrypt($c->idac) , 'activo' => encrypt($c->activo)])}}"><button type="button" class="btn btn-danger">Desactivar</button></a>
                    @else
                      <a href="{{route('activacion', ['id' => encrypt($c->idac) , 'activo' => encrypt($c->activo)])}}"><button type="button" class="btn btn-primary">Activar</button></a>
                    @endif 
>>>>>>> 332fc7370bcd4c865e9041dfaf40090947a95280
                </td>
              </tr>
            @endforeach
            </tbody>
          </table>
      </div>
    </div>
  </div>

  <div class="modal fade" id="ajaxModel" value="1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title" id="modelHeading"></h4></div>
            <div class="modal-body">
                <form id="Detalles" name="Detalles" class="form-horzontal">
                <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-bordered" id="tablaModal">
            <thead class="text-center">
              <tr style="background-color: #1F75FE; color: #ffffff">
                <th scope="col">Nombre atendio</th>
                <th scope="col">Cargo</th>
                <th scope="col">Avance</th>
                <th scope="col">Status Atenci&aacute;n</th>
                <th scope="col">Acuse recibido</th>
                <th scope="col">Operaciones</th>
                
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
</div>

<script>
<<<<<<< HEAD
    $("#tabla").DataTable();
   

</script>

<script type="text/javascript">
 
    $('body').on('click', '.Detalles',function(){
      var id = $(this).data('id');

      var i = 0;
      $.get("Detalles/" + id, function(data){
       //alert( JSON.stringify(data,['app']));
       $('#tablaModal>tbody>tr').remove();
       while ( i!=1+i){
         if(data[i].nombre_us == null){
           break;
         }else{
        $('#modelHeading').html("Detalles");
        $('#ajaxModel').modal('show');
        //console.log(data);

        var nombre = "<td><input id='nombre"+i+"' name='nombre"+i+"' disabled></td>"
        var area = "<td><input id='idar"+i+"' name='idar"+i+"' disabled></td>"
        var avance = "<td><input id='avance"+i+"' name='avance"+i+"' disabled></td>"
        var status = "<td><input id='status"+i+"' name='status"+i+"' disabled></td>"
        var acuse = "<td><input id='acuse"+i+"' name='acuse"+i+"' disabled></td>"
        var detalles = "<td><a form method ='post' id='masDet"+i+"' name='masDet"+i+"' <a  target='_blank' onclick=window.open(this.href,this.target,width=600,height=800); class='btn btn-success btn-sm' >Detalles<span class='icon text-white-50'></td>"        
        $('#tablaModal>tbody').append("<tr>"+nombre+area+avance+status+acuse+detalles+"</tr>");
        $('#nombre'+i).val(data[i].nombre_us);
        $('#idar'+i).val(data[i].nombre_ar);
        $('#avance'+i).val("50%");
        $('#status'+i).val("incompleto");
        $('#acuse'+i).val(data[i].acuse);
        $('#masDet'+i).attr('href',"detallesSeguimiento/"+data[i].idreac);
        $('#masDet'+i).attr('target',"_blank");
        i=i+1;
          }
       }
      })
      
    });
    $("#ajaxModel").on('hidden.bs.modal', function () {
        
              $('#tablaModal>tbody>tr').remove();
            
    });
    
=======
    $("#tabla").DataTable({ 
        "language": {
            "url": "https://raw.githubusercontent.com/DataTables/Plugins/master/i18n/es_es.json"
        }
    });
>>>>>>> 332fc7370bcd4c865e9041dfaf40090947a95280
</script>

@endsection