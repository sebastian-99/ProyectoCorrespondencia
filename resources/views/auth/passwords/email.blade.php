@extends('layout.auth')
@section('contenido')
@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif
<div align="center">
    <h3>Ingresar correo electrónico para cambio de contraseña</h3>
</div>
<form method="POST" action="{{ route('password.email') }}">
    @csrf
    <div class="form-group d-md-flex">
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Correo Electrónico" autofocus>
    </div>
    <div class="form-group d-md-flex">
        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="form-group d-md-flex">
        <button type="submit" class="btn btn-primary btn-sm rounded submit">Recuperar contraseña</button>
    </div>
</form>
@endsection
