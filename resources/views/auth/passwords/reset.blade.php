@extends('layout.auth')
@section('contenido')
<div align="center">
    <h3>Cambiar contraseña</h3>
</div>
<form method="POST" action="{{ route('password.update') }}">
    @csrf
    <b>Correo Electrónico:</b>
    <input type="hidden" name="token" value="{{ $token }}">
    <div class="form-group" hidden>

    <input 
    id="email" 
    type="email"
    class="form-control rounded-left"  
    class="@error('email') is-invalid @enderror" 
    placeholder="Correo electrónico" 
    name="email" 
    value="{{ $email ?? old('email') }}" 
    >
    <i style="color:red;">Es obligatorio colocar tu correo </i>
	
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
    
</form>
@endsection
