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
            <div class="row">
                <div class="col-sm-11">
                    <h2 align="center">Tipos de Actividades</h2>
                    <a href="#crear" class="btn btn-success" data-toggle="modal" data-target="#crearModal">
                        Crear
                    </a>
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
        	page-size='3'
        	page-size-options='1,2,3,4,5,10'
        	layout='row'
        	viewport-stop
        	theme='android'
        	id='zing-grid'
        	filter
            data="{{ $json }}">
        	<zg-colgroup>
            	<zg-column index='idtac' header='#'  type='text'></zg-column>
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

{{-- Inicia Modal para crear --}}
    <div class="modal fade" id="crearModal" tabindex="-1" role="dialog" aria-labelledby="crearModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="crearModalLabel"><i class="far fa-user-edit"></i> Crear Tipo Actividad</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
          </div>
          <div class="modal-body">

            <form action="{{ route('tipos-actividades.store') }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('POST')

              <div class="form-group">
                <label for="nombre">Nombre: <b class="text-danger">*</b></label>
                <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                @error('nombre')<p class="text-danger" style="font-size: 14px; font-style: italic;">{{ $message }}</p>@enderror
              </div>
              <div class="form-group text-center">
                <button type="submit" class="btn btn-success">Crear</button>
                <button type="reset" class="btn btn-outline-secondary" >Limpiar</button>
              </div>
            </form>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          </div>
        </div>
      </div>
    </div>
{{-- Termina Modal de crear --}}

@foreach($tipos_actividades as $tipoactividad_edit)
{{-- Inicia Modal para editar --}}
    <div class="modal fade" id="editarModal-{{ $tipoactividad_edit->idtac }}" tabindex="-1" role="dialog" aria-labelledby="editarModal-{{ $tipoactividad_edit->idtac }}Label" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editarModal-{{ $tipoactividad_edit->idtac }}Label"> Editar Tipo Actividad</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
          </div>
          <div class="modal-body">

            <form action="{{ route('tipos-actividades.update', ['tipos_actividade' => $tipoactividad_edit->idtac]) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PATCH')
              <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $tipoactividad_edit->nombre) }}" required>
                @error('nombre')<p class="text-danger" style="font-size: 14px; font-style: italic;">{{ $message }}</p>@enderror
              </div>
              <div class="form-group">
                <label for="activo">Activo:</label>
                <select class="form-control @error('activo') is-invalid @enderror" id="activo" name="activo" required>
                  <option value="">Selección</option>
                  <option value="1" {{ (old('activo', $tipoactividad_edit->activo) == 1) ? 'selected' : '' }}>Si</option>
                  <option value="0" {{ (old('activo', $tipoactividad_edit->activo) == 0) ? 'selected' : '' }}>No</option>
                </select>
                @error('activo')<p class="text-danger" style="font-size: 14px; font-style: italic;">{{ $message }}</p>@enderror
              </div>
              <div class="form-group text-center">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <button type="reset" class="btn btn-outline-secondary" >Limpiar</button>
              </div>
            </form>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          </div>
        </div>
      </div>
    </div>
{{-- Termina Modal de editar --}}
 @endforeach
@endsection
