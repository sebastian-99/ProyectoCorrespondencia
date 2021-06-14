@extends('layout.auth')
@section('contenido')
<form method="POST" action="{{ route('login') }}">
	@csrf
	<div class="form-group">
		<input type="text" class="form-control rounded-left" placeholder="Correo electrónico" name="email" required>
		@error('email')
                <strong>{{ $message }}</strong>
        @enderror
	</div>
	<div class="form-group d-flex">
		<input type="password" class="form-control rounded-left" placeholder="Contraseña" name="password" required>
		@error('password')
		        <strong>{{ $message }}</strong>
		@enderror
	</div>
	<div class="form-group d-md-flex">
	<div>
		<a href="{{ route('password.request') }}">{{ "¿Olvidaste tu contraseña?" }}</a>
		<button type="submit" class="btn btn-primary btn-sm rounded submit mt-3">Iniciar sesión</button>
	</div>
	</div>
    <br>
	<br>
	<div class="form-group md-flex">
	<div>
	<h6 align=center>Universidad Tecnol&oacute;gica del Valle de Toluca</h6>
	<h6 align=center>¡¡Siempre Cuervos!!</h6>
	</div>
	</div>
</form>
@endsection
