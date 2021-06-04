@extends('layout.layout')
@section('content')
@section('header')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
@endsection


    <div class="card">
    <div class="card-header">
    <div class="row">
    <div class="col-sm-11">
    @foreach ($consult as $c)
    @if ($loop->first)
    <h3>Reporte de actividades de {{$c->nombre}} </h3>
    @endif
    @endforeach
    </div>
    <div class="col-sm-10">
    <a href="javascript:history.back()"><button class="btn btn-warning">Regresar a responsables</button></a>
        </div>
    <div class="col-sm-2">
        <a href="#" onclick="javascritp:window.self.close();"><button class="btn btn-danger">Cerrar</button></a>
        </div>
    </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-bordered" id="tabla">
            <thead class="text-center">
              <tr style="background-color: #1F75FE; color: #ffffff">
                <th scope="col">No. Seguimiento</th>
                <th scope="col">Fecha de avance</th>
                <th scope="col">Detalle</th>
                <th scope="col">Status</th>
                <th scope="col">% de avance</th>
                <th scope="col">Archivos</th>
              </tr>
            </thead>
            <tbody>
            @foreach ($consult as $c)
            <tr class="text-center">
                <td>{{$c->idseac}}</td>
                <td>{{$c->fecha}}</td>
                <td>{{$c->detalle}}</td>
                <td>{{$c->estado}}</td>
                <td>{{$c->porcentaje}}</td>
                <td><a href="javascript:void(0)" data-toggle="tooltip" data-id="{{encrypt($c->idseac)}}"  data-original-title="DetallesArchivos" 
                     class="edit btn btn-success btn-sm DetallesArchivos">DetallesArchivos</a></td>
                

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
                <form id="DetallesArchivos" name="DetallesArchivos" class="form-horzontal">
                <div class="card-body">
      <div class="table-responsive">
     
      @foreach ($consult as $c)
    @if ($loop->first)
    <h4>
    <div >Actividad: {{$c->asunto}} </div>
   <div> Usuario: {{$c->nombre}} </div> 
   @endif
    </h4>
   
    
    @endforeach
        <table class="table table-striped table-bordered" id="tablaModal">
            <thead class="text-center">
              <tr style="background-color: #1F75FE; color: #ffffff">
                <th scope="col">Nombre </th>
                <th scope="col">Detalle</th>
                <th scope="col">Arrchivo</th>
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


<script>
    $("#tabla").DataTable();

</script>
<script type="text/javascript">
 
    $('body').on('click', '.DetallesArchivos',function(){
      var id = $(this).data('id');
     
      var i = 0;
      $.get("../DetallesArchivos/" + id, function(data){
        $('#tablaModal>tbody>tr').remove();
       while ( i!=1+i){
         if(data[i].nombre == null){
           break;
         }else{
      
       $('#modelHeading').html("Detalles Archivos");
        $('#ajaxModel').modal('show');
        var nombre = "<td><input id='nombre"+i+"' name='nombre"+i+"' disabled></td>"
        var detalle = "<td><input id='detalle"+i+"' name='detalle"+i+"' disabled></td>"
        var ruta = "<td><input id='ruta"+i+"' name='ruta"+i+"' disabled></td>"
        
        $('#tablaModal>tbody').append("<tr>"+nombre+detalle+ruta+"</tr>");
        $('#nombre'+i).val(data[i].nombre);
        $('#detalle'+i).val(data[i].detalle);
        $('#ruta'+i).val(data[i].ruta);
  
        i=i+1;
         }
        }
      })
      
    });

    $("#ajaxModel").on('hidden.bs.modal', function () {
        
              $('#tablaModal>tbody>tr').remove();
            
    });
    
</script>
@endsection