@extends('layout.layout')
@section('content')
@section('header')

    <script src='{{asset('src/js/zinggrid.min.js')}}'></script>
    <script src='{{asset('src/js/zinggrid-es.js')}}'></script>

	<!-- Libreria para usar xlsx en js -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.13.1/xlsx.full.min.js"></script>

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
		<div class="text-center">
			<button id="btn_exportar_excel" type="button" class="btn btn-success">
				Exportar a EXCEL
			</button>
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
                    <zg-column index='avance' header='Avance' width="120" type='text'></zg-column>
                    <zg-column index='atendido_por' header='Atendido por' width="135" type='text'></zg-column>
                    <zg-column index='estatus' header='Estatus' width="120" type='text'></zg-column>
                    <zg-column align="center" filter ="disabled" index='operaciones' header='Operaciones' type='text'></zg-column>
        	</zg-colgroup>
    	</zing-grid>
	</div>
</div>
@endsection

@section('scripts')
	<script>
		$(window).on('load', function(){
	        const $NAME_EXCEL = 'Actividades Creadas';
	        const $BTN_EXPORTAR_EXCEL = $('#btn_exportar_excel');
	        const $ZING_GRID = document.querySelector('zing-grid');

	        $BTN_EXPORTAR_EXCEL.on('click', function(){
	        	let $gridData = $ZING_GRID.getData({
					headers:true,
	        		cols:'visible',
					rows:'visible'
	        	});

				$.map($gridData, function(data){
					delete data.operaciones;
				});

                let $sheet = XLSX.utils.json_to_sheet($gridData);
                let $book = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet($book, $sheet, 'Hoja 1');
                XLSX.writeFile($book, $NAME_EXCEL + '.xlsx');
            });
		});
	</script>
@endsection
