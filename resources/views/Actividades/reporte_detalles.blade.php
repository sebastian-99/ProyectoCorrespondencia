@extends('layout.layout')
@section('content')
@section('header')

    <script src='{{asset('src/js/zinggrid.min.js')}}'></script>    
    <script src='{{asset('src/js/zinggrid-es.js')}}'></script>   
    <script>
      if (es) ZingGrid.registerLanguage(es, 'custom');
    </script> 
@endsection

<div class="card">
<div class="card-header">
      <div class="row">
        <div class="col-sm-11">
          <h3>Responsables de la actividad</h3>
        </div>
        @if(session()->has('message'))
           <div class="alert alert-success">
            {{ session()->get('message') }}
          </div>
        @endif
        @if (Session::has('message2'))
                <p class="alert alert-danger">
                    {{Session::get('message2')}}
                </p>
          @endif
        <div class="col-sm-2">
        <a href="{{route('pdf',['idac' => encrypt($idac)])}}" class="btn btn-primary" target="_blank" {{($boton->boton == 0) ? 'hidden' : ''}}>PDF</a>
        </div>
      </div>
      
    </div>
    <zing-grid
                lang="custom" 
                caption='Responsables' 
                sort 
                search 
                pager 
                page-size='10' 
                page-size-options='10,20,50' 
                layout='row' 
                viewport-stop
                theme='android'
                id='zing-grid'
                filter
                data = "{{$json}}">
                <zg-colgroup>
                    <zg-column index='nombre_us' header='Nombre atendio' width="" type='text'></zg-column>
                    <zg-column index='nombre_ar' header='Cargo' width="" type='text'></zg-column>
                    <zg-column index='porcentaje' filter ="disabled" header='Avance' width="" type='text'></zg-column>
                    <zg-column index='estado' header='Estado' width="" type='text'></zg-column>
                    <zg-column index='acuse' filter ="disabled" header='Acuse' width="" type='text'></zg-column>  
                    <zg-column align="center" filter ="disabled" index='operaciones' header='Operaciones' width="" type='text'></zg-column>
                </zg-colgroup>
              </zing-grid>

  @endsection