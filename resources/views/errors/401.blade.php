@extends('errors::illustrated-layout')

@section('code', '401')
@section('title', __('Unauthorized'))

@section('image')
<style>
    #apartado-derecho{
        text-align:center;       
    }
    ul{
        text-decoration: none !important;
        list-style: none;
        color: black;
        font-weight: bold;
    }
</style>
<div id="apartado-derecho" style="background-image: url('/images/gif_c.gif')" class="absolute pin bg-cover lg:bg-center">
   
</div>
@endsection

@section('message', __('La petición (request) no ha sido ejecutada porque carece de credenciales válidas de autenticación para el recurso solicitado.'))