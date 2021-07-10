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
        	  <h3>Reporte de actividades creadas por mí persona</h3>
        	</div>
    	</div>
      	<div class="row">
        	<div class="col-sm-6">
        	  <label for="">Fecha orden:</label>
        	  
        	</div>
        	<div class="col-sm-6">
        	  <label for="">Fecha:</label>
        	</div>
    	</div>
			<div class="row">
				<div class="col-sm-6">
				  <select class="form-control" name="fecha_orden" id="fecha_orden">
					  <option value="0">Todas los registros</option>
					  <option value="1">Fecha inicio</option>
					  <option value="2">Fecha fin</option>
				  </select>
				  <button type="button" class="btn btn-primary mt-1" id="button">Enviar</button> <button type="button" class="btn btn-primary mt-1" id="limpiar">Limpiar</button>
				</div>
				<div class="col-sm-6">
				  <input class="form-control" name="fecha" id="fecha" type="date" readonly>
				</div>
			</div>
	</div>
	<div class="card-body">
    	<zing-grid
        	lang="custom"
        	caption='Reporte de oficios'
        	sort
        	search
        	pager
        	page-size='10'
        	page-size-options='1,2,3,4,5,10'
        	layout='row'
        	viewport-stop
        	theme='android'
        	id='zing-grid'
        	filter
        	data = "{{$json}}">
        	<zg-colgroup>
					<zg-column index='turno' header='Turno' width="100" type='number'></zg-column>
					<zg-column index='asunto' header='Asunto' width="200" type='text'></zg-column>
					<zg-column index='tipo_actividad' header='Tipo de actividad' width="200" type='text'></zg-column>
                    <zg-column index='descripcion' header='Descripción' width="200" type='text'></zg-column>
                    <zg-column index='fecha_creacion' header='Fecha creación' width="200" type='text'></zg-column>
                    <zg-column index='creador' header='Creador' width="200" type='text'></zg-column>
                    <zg-column index='periodo' header='Periodo' width="220" type='text'></zg-column>
                    <zg-column index='importancia' header='Importancia' width="130" type='text'></zg-column>
                    <zg-column index='nombre' header='Área responsable' width="170" type='text'></zg-column>
                    <zg-column index='C' header='Avance' width="120" type='text'></zg-column>
                    <zg-column index='AB' header='Atendido por' width="135" type='text'></zg-column>
                    <zg-column index='E' header='Estatus' width="120" type='text'></zg-column>
                    <zg-column align="center" filter ="disabled" index='operaciones' header='Operaciones' type='text'></zg-column>
        	</zg-colgroup>
    	</zing-grid>
	</div>
</div>

<script>

	$("#fecha_orden").on("change", function(){

		if($(this).val() == "3"){

			$("#fecha").attr("readonly", true);
			$("#fecha").val("");

		}else{

			$("#fecha").removeAttr("readonly");
		}
		


	});
	console.log($("#fecha_orden").val());
	$("#button").on("click", function(){

		let fecha_orden = $("#fecha_orden").val();
		let fecha = $("#fecha").val();
		
		$.ajax({
			type: "get",
			url: "{{route('ajax_filtro_fecha')}}",
			data: {
				fecha_orden:fecha_orden,
				fecha:fecha
			},
			success: function (data) {

				
				$("#zing-grid").removeAttr("data");
				$("#zing-grid").attr("data", data);
				//$("#zing-grid").data("data", data);


			},
			error(error){
				console.log(error);
			}
		});
	
	$('#limpiar').on("click",function(){
 	$("#fecha").val("");
  	$("#fecha_orden").val(0);
  	$('#fecha').attr("readOnly",true);
    $('#fecha').val("");
	});
	
	$('#fecha_orden').on("change",function(){
		if($(this).val() == 0){
		$('#fecha').attr("readOnly",true);
		$('#fecha').val("");
  	}
	else{
		$('#fecha').removeAttr("readOnly");
		}
	});
	});
</script>


@endsection
