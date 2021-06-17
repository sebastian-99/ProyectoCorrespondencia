@extends('layout.auth')
@section('contenido')
<form method="POST" action="{{ route('login') }}">
	@csrf
	<div class="form-group">
	@if($errors->first('email'))
	<p class="alert-danger">{{$errors->first('email')}}</p>
	@endif
	<input type="text" class="form-control rounded-left"  placeholder="Correo electrónico" name="email">
	</div>

	<div class="form-group d-flex">
	@if($errors->first('password'))
	<p class="alert-danger">{{$errors->first('password')}}</p>
	@endif
		<input type="password" class="form-control rounded-left"  placeholder="Contraseña" name="password" >
	
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
