@extends('layout.layout')
@section('content')
    @section('header')
        <script src='{{asset('src/js/zinggrid.min.js')}}'></script>
        <script src='{{asset('src/js/zinggrid-es.js')}}'></script>
        <script>
        if (es) ZingGrid.registerLanguage(es, 'custom');
        </script>
    @endsection

    {{--Inicia Reporte --}}
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
                    <h2 align="center">Áreas</h2>
                    <a href="{{url('areas/create')}}"><button class="btn btn-success"><i class="fas fa-plus-square"></i></button></a>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
    	<zing-grid
        	lang="custom"
        	caption='Reporte de áreas'
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
            	<zg-column index='idtar' header='Tipo-área'  type='text'></zg-column>
                <zg-column index='activo' header='Activo'  type='text'></zg-column>
                <zg-column align="center" filter ="disabled" index='operaciones' header='Operaciones' type='text'></zg-column>
        	</zg-colgroup>
    	</zing-grid>
	</div>
    @foreach ($areas as $area)
    <form id="eliminar-area-{{ $area->idar }}" class="ocultar" action="{{ route('areas.destroy', ['area' => $area->idar]) }}" method="POST">
        @csrf
        @method('DELETE')
    </form>
    @endforeach

    {{-- Termina reporte --}}
@endsection
