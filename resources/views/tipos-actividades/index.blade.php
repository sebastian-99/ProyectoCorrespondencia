@extends('layout.layout')
@section('content')
    @section('header')
        <script src='{{asset('src/js/zinggrid.min.js')}}'></script>
        <script src='{{asset('src/js/zinggrid-es.js')}}'></script>
        <script>
        if (es) ZingGrid.registerLanguage(es, 'custom');
        </script>
    @endsection

{{-- Inicia Reporte --}}
    <div class="card">
        <div class="card-header">
            @if (Session::has('mensaje'))
                <div class="alert alert-success">
                    <strong>{{ Session::get('mensaje') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                </div>
            @endif
            <div class="row">
                <div class="col-sm-11">
                    <h2 align="center">Tipos de actividades</h2>
                    <a href="{{url('tipos-actividades/create')}}"><button class="btn btn-success"><i class="fas fa-plus-square"></i></button></a>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
    	<zing-grid
        	lang="custom"
        	caption='Reporte de tipos de actividades'
        	sort
        	search
        	pager
        	page-size='10'
        	page-size-options='10,15,20,25,30'
        	layout='row'
        	viewport-stop
        	theme='android'
        	id='zing-grid'
        	filter
            selector
            data="{{ $json }}">
        	<zg-colgroup>
            	<zg-column index='nombre' header='Nombre'  type='text'></zg-column>
            	<zg-column index='activo' header='Activo'  type='text'></zg-column>
                <zg-column align="center" filter ="disabled" index='operaciones' header='Operaciones' type='text'></zg-column>
        	</zg-colgroup>
    	</zing-grid>
	</div>
        @foreach($tipos_actividades as $tipoactividad)
        <form id="eliminar-tipos-actividades-{{ $tipoactividad->idtac }}" class="ocultar" action="{{ route('tipos-actividades.destroy', ['tipos_actividade' => $tipoactividad->idtac]) }}" method="POST">
             @csrf
             @method('DELETE')
        </form>
        @endforeach

{{-- Termina Reporte --}}

@endsection
