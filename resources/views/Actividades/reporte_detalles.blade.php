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
        <div class="col-sm-2">
        <a href="#" onclick="javascritp:window.self.close();"><button class="btn btn-danger">Cerrar</button></a>
        </div>
        <div class="col-sm-2">
        <a href="{{route('pdf',['idac' => encrypt($idac)])}}" class="btn btn-primary" target="_blank">PDF</a>
        </div>
      </div>
      
    </div>
    <zing-grid
                lang="custom" 
                caption='Responsables' 
                sort 
                search 
                pager 
                page-size='3' 
                page-size-options='1,2,3,4,5,10' 
                layout='row' 
                viewport-stop
                theme='android'
                id='zing-grid'
                filter
                data = "{{$json}}">
                <zg-colgroup>
                    <zg-column index='nombre_us' header='Nombre atendio' width="250" type='text'></zg-column>
                    <zg-column index='nombre_ar' header='Cargo' width="150" type='text'></zg-column>
                    <zg-column index='porcentaje' header='Avance' width="120" type='text'></zg-column>
                    <zg-column index='estado' header='Estado' width="120" type='text'></zg-column>
                    <zg-column index='acuse' header='Acuse' width="100" type='text'></zg-column>
                    <zg-column align="center" filter ="disabled" index='operaciones' header='Operaciones' width="200" type='text'></zg-column>
                </zg-colgroup>
              </zing-grid>



  @endsection