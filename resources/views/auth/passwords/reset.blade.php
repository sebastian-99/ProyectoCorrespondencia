@extends('layout.auth')
@section('contenido')
<div align="center">
    <h3>Cambiar contraseña</h3>
</div>
<form method="POST" action="{{ route('password.update') }}">
    @csrf
    <b>Correo Electrónico:</b>
    <input type="hidden" name="token" value="{{ $token }}">
    <div class="form-group">
    <input type="text" class="form-control rounded-left"  class="@error('email') is-invalid @enderror" placeholder="Correo electrónico" name="email" value="{{email}}" >
	@error('email')
    <div class="form-text text-danger">{{ $message }}</div>
	@enderror
	</div>

    <b>Nueva Contraseña:</b>
    <div class="form-group">
		<input type="password" class="form-control rounded-left"  class="@error('password') is-invalid @enderror" placeholder="Contraseña" name="password"  >
	@error('password')
    <div class="form-text text-danger" role"alert">{{ $message }}</div>
	@enderror
	</div>

    <b>Confirma tu Contraseña:</b>
    <div class="form-group ">
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password_confirmation"  placeholder="Confirmar contraseña">
    </div>
    <div class="form-group ">
        <button type="submit" class="btn btn-primary btn-sm rounded submit">Recuperar contraseña</button>
    </div>
    <br>
    <br>
    <br>
    <p style="background: #bdf0fa; color: #0c92ac; font-weight: bold; padding: 15px; border: 2px solid #abecf9; border-radius: 6px;">
    INSTRUCCIONES:<br>Tu contraseña debe de contener 8 caracteres minimo:
    <br>-Una mayuscula
    <br>-Números.
    <br>-Minúsculas
    <br>-Un Simbolo</p>

</form>
@endsection
