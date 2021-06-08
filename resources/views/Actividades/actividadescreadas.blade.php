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
        	  <h3>Reporte de actividades creadas por mi persona</h3>
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
        	page-size='3'
        	page-size-options='1,2,3,4,5,10'
        	layout='row'
        	viewport-stop
        	theme='android'
        	id='zing-grid'
        	filter
        	data = "{{$json}}">
        	<zg-colgroup>
					<zg-column index='turno' header='Turno' width="100" type='number'></zg-column>
					<zg-column index='asunto' header='Asunto' width="120" type='text'></zg-column>
                    <zg-column index='descripcion' header='Descripcion' width="120" type='text'></zg-column>
                    <zg-column index='fecha_creacion' header='Fecha creación' width="120" type='text'></zg-column>
                    <zg-column index='creador' header='Creador' width="120" type='text'></zg-column>
                    <zg-column index='periodo' header='Periodo' width="120" type='text'></zg-column>
                    <zg-column index='importancia' header='Importancia' width="120" type='text'></zg-column>
                    <zg-column index='nombre' header='Area responsable' width="120" type='text'></zg-column>
                    <zg-column index='C' header='Acance' width="100" type='text'></zg-column>
                    <zg-column index='AB' header='Atendido por' width="100" type='text'></zg-column>
                    <zg-column align="center" filter ="disabled" index='operaciones' header='Operaciones' width="120" type='text'></zg-column>
        	</zg-colgroup>
    	</zing-grid>
	</div>
</div>




@endsection
