@extends('layout.layout')
@section('content')
@section('header')
<script src='{{asset('src/js/zinggrid.min.js')}}'></script>
    <script src='{{asset('src/js/zinggrid-es.js')}}'></script>
    <script>
      if (es) ZingGrid.registerLanguage(es, 'custom');
    </script>
@endsection


    <div class="card">
    <div class="card-header">
    <div class="row">
    <div class="col-sm-11">
    @foreach ($consult as $c)
    @if ($loop->first)
    <h3>Reporte de actividades de: {{$c->nombre}} </h3>
    @endif
    @endforeach
    </div>
    <div class="col-sm-10">
    <a href="{{ route('Detalles', ['id'=>$id_actividad]) }}"><button class="btn btn-warning">Regresar a responsables</button></a>
        </div>
   
    </div>
    </div>


    <zing-grid
                lang="custom"
                caption='Actividades'
                sort
                search
                pager
                page-size='10'
                page-size-options='10,20,50'
                layout='row'
                viewport-stop
                theme='android'
                id='zing-grid'
                filter
                data = "{{$json}}">
                <zg-colgroup>
                    <zg-column index='idseac' header='No. Seguimeinto' width="170" type='text'></zg-column>
                    <zg-column index='fecha' header='Fecha de avance' width="170" type='text'></zg-column>
                    <zg-column index='detalle' header='Detalles' width="500" type='text'></zg-column>
                    <zg-column index='estado' header='Estado' width="100" type='text'></zg-column>
                    <zg-column index='porcentaje' filter ="disabled" header='Porcentaje' width="130" type='text'></zg-column>
                    <zg-column align="center" filter ="disabled" index='operaciones' header='Operaciones' width="150" type='text'></zg-column>
                </zg-colgroup>
              </zing-grid>



  
       <div class="modal fade" id="ajaxModel" value="1" aria-hidden="true">
       <div class="modal-dialog modal-lg">
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

    $('body').on('click', '.DetallesArchivos',function(){
      var id = $(this).data('id');

      var i = 0;
      $.get("../DetallesArchivos/" + id, function(data){
        $('#tablaModal>tbody>tr').remove();
       while ( i!=1+i){
         if(data[i].nombre == null){
           i=i+1;
           break;
         }else{

       $('#modelHeading').html("Detalles Archivos");
        $('#ajaxModel').modal('show');
        var nombre = "<td><input id='nombre"+i+"' name='nombre"+i+"'  style='width:400px' disabled></td>"

        if(data[i].ruta == 'Sin archivo'){
        var texto = '<td>No hay archivos disponibles</td>';
        $('#tablaModal>tbody').append("<tr>"+nombre+texto+"</tr>");
        $('#nombre'+i).val(data[i].nombre);
        $('#ruta'+i).val(ruta);
        $('#ruta'+i).attr('href',archivo);
        $('#ruta'+i).text(texto);
        }

        else 

        if(data[i].ruta != '' ){
        var ruta = "<td><a download id='ruta"+i+"' name='ruta"+i+"'class='btn btn-danger' ><i class='fa fa-file'></i></a></td>"
        var archivo = '{{asset(('archivos/Seguimientos'))}}/'+data[i].ruta;
        $('#tablaModal>tbody').append("<tr>"+nombre+ruta+"</tr>");
        $('#nombre'+i).val(data[i].nombre);
        $('#ruta'+i).val(ruta);
        $('#ruta'+i).attr('href',archivo);
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

</script>
@endsection
