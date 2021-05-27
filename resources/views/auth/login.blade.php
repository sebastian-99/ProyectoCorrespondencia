@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-center h-80">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">{{ __('Sistema Seguimiento de Oficios') }}</div>

                <div class="card-body">

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        INICIO DE SECION
                        <br>
                        <br>
                            <div class="input-group form-group">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"  placeholder="Email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                </div>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        
                        
                            <div class="input-group form-group">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"  placeholder="Contraseña" name="password" required autocomplete="current-password">
                            </div>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            

                        <div class="form-group row mb-12">
                            <div class="offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Entrar') }}
                                </button>
                            </div>
                            <br>
                            <br>
                            <div class="col-md-12" style="center">
                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('¿Olvidaste tu contraseña?') }}
                                    </a>
                                @endif
                            </div>
                            <br>
                            <br>
                             <div class="card-footer">
                               <div class="d-flex justify-content-center links">
                                 UNIVERSIDAD TECNOLOGICA DEL VALLE DE TOLUCA  
                               </div>
                             </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
