@extends('layout.auth')

@include('flash-message')
    @yield('content')

@section('contenido')

<form method="POST" action="{{ route('login') }}">
    @csrf

    INICIAR SESIÓN
    
    <br>
    <br>
    <div class="form-group">
        <input type="text" class="form-control rounded-left" class="@error('email') is-invalid @enderror" placeholder="Correo electrónico" name="email">
        @error('email')
        <div class="form-text text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <input type="password" class="form-control rounded-left" class="@error('password') is-invalid @enderror" placeholder="Contraseña" name="password">
        @error('password')
        <div class="form-text text-danger">{{ $message }}</div>
        @enderror
    </div>


    <div class="form-group d-md-flex">
        <div align="center">
            <button type="submit" class="btn btn-primary btn-sm rounded submit mt-3">Iniciar sesión</button>
        </div>
    </div>
    <br>
    <br>
    <br>
    <br>
    <div class="card-footer">
        <a href="{{ route('password.request') }}">{{ "¿Olvidaste tu contraseña?" }}</a><br><br>
        <div>
            <h6 align=center>Universidad Tecnol&oacute;gica del Valle de Toluca</h6>
            <h6 align=center>¡¡Siempre Cuervos!!</h6>
        </div>
    </div>
</form>
@endsection 