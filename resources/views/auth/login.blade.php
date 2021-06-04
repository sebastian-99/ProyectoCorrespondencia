@extends('layout.auth')
@section('contenido')
<form method="POST" action="{{ route('login') }}">
	@csrf
	<div class="form-group">
		<input type="text" class="form-control rounded-left" placeholder="Correo electrónico" name="email" required>
		@error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
           </span>
        @enderror
	</div>
	<div class="form-group d-flex">
		<input type="password" class="form-control rounded-left" placeholder="Contraseña" name="password" required>
		@error('password')
		    <span class="invalid-feedback" role="alert">
		        <strong>{{ $message }}</strong>
		    </span>
		@enderror
	</div>
	<div class="form-group d-md-flex">
	<div>
		<a href="{{ route('password.request') }}">{{ "¿Olvidaste tu contraseña?" }}</a>
		<button type="submit" class="btn btn-primary btn-sm rounded submit mt-3">Iniciar sesión</button>
	</div>
	</div>
</form>
@endsection