@extends('errors::illustrated-layout')

@section('code', '419')
@section('title', __('Página expirada'))

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

@section('message', __('La sesion expiro, por favor vuelve a cargar la página.'))