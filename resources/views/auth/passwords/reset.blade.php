@extends('layout.auth')
@section('contenido')
<div align="center">
    <h3>Cambiar contraseña</h3>
</div>
<form method="POST" action="{{ route('password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <div class="form-group d-md-flex">
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Correo electrónico" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
    </div>
    <div class="form-group d-md-flex">
        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="form-group d-md-flex">
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Contraseña">
    </div>
    <div class="form-group d-md-flex">
        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="form-group d-md-flex">
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password" placeholder="Confirmar contraseña">
    </div>
    
    <div class="form-group d-md-flex">
        <button type="submit" class="btn btn-primary btn-sm rounded submit">Recuperar contraseña</button>
    </div>
    <br>
    <br>
    <p style="background: #bdf0fa; color: #0c92ac; font-weight: bold; padding: 15px; border: 2px solid #abecf9; border-radius: 6px;">
    INSTRUCCIONES:<br>Tu contraseña debe de contener:
    <br>-Una mayuscula
    <br>-Números.
    <br>-Minúsculas</p>

</form>
@endsection
