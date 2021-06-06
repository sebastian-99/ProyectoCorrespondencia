@extends('layout.layout')
@section('content')
    @section('header')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
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

    <div class="table-responsive">
        <table class="table table-striped table-bordered" id="tabla">
            <thead class="text-center">
             <tr style="background-color: #1F75FE; color: #ffffff">

                <th scope="col">#</th>
                <th scope="col">Nombre(s)</th>
                <th scope="col">Activo</th>
                <th scope="col">Opciones</th>
            </tr>
        </thead>
        <tbody>
  	        @foreach($tipos_actividades as $tipoactividad)
                <tr class="text-center">
                    <td>{{$tipoactividad->idtac }}</td>
                    <td>{{$tipoactividad->nombre}}</td>
                    <td>{{ ($tipoactividad->activo == 1) ? 'Si' : 'No' }}</td>
                    <td>
                        <div class="btn-group">
                            <a href="#editar" class="btn btn-primary" data-toggle="modal" data-target="#editarModal-{{ $tipoactividad->idtac }}">
                                Editar
                            </a>
                            <form id="eliminar-tipos-actividades-{{ $tipoactividad->idtac }}" class="ocultar" action="{{ route('tipos-actividades.destroy', ['tipos_actividade' => $tipoactividad->idtac]) }}" method="POST">
                              @csrf
                              @method('DELETE')
                            </form>
                            <a href="#eliminar" class="btn btn-danger" onclick="formSubmit('eliminar-tipos-actividades-{{ $tipoactividad->idtac }}')">Eliminar</a>
                        </div>
                    </td>
                </tr>
             @endforeach
        </tbody>
    </table>

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
                <label for="nombre">Nombre: <b class="text-danger">*</b></label>
                <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $tipoactividad_edit->nombre) }}" required>
                @error('nombre')<p class="text-danger" style="font-size: 14px; font-style: italic;">{{ $message }}</p>@enderror
              </div>
              <div class="form-group">
                <label for="activo">Activo: <b class="text-danger">*</b></label>
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
