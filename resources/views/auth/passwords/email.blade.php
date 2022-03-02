@extends('layout.auth')
@section('contenido')
@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif
<div align="center">
    <h3>Ingresa tu Correo Electrónico</h3>
</div>
<form method="POST" action="{{ route('password.email') }}">
    @csrf
    <div class="form-group">
		<input type="text" class="form-control rounded-left"  class="@error('email') is-invalid @enderror" placeholder="Correo electrónico" name="email"  autofocus>
	@error('email')
    <div class="form-text text-danger">{{ $message }}</div>
	@enderror
	</div>
    <div class="form-group d-md-flex">
        <button type="submit" class="btn btn-primary btn-sm rounded submit">Recuperar contraseña</button>
    </div>
</form>
@endsection
