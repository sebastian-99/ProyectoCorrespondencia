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
        <h3>Actividades asignadas al {{Auth()->user()->titulo}} {{Auth()->user()->nombre}} {{Auth()->user()->app}} {{Auth()->user()->apm}} </h3>
      </div>
    </div>
  </div>
  <div class="card-body">
    <zing-grid lang="custom" caption='Reporte de oficios' sort search pager page-size='3' page-size-options='1,2,3,4,5,10' layout='row' viewport-stop theme='android' id='zing-grid' filter data="{{$json}}">
      <zg-colgroup>
        <zg-column index='turno' header='Turno' width="100" type='text'></zg-column>
        <zg-column index='fecha_creacion' header='Fecha de creacion' width="100" type='text'></zg-column>
        <zg-column index='asunto' header='Asunto' width="200" type='text'></zg-column>
        <zg-column index='creador' header='Creador' width="200" type='text'></zg-column>
        <zg-column index='periodo' header='Periodo' width="150" type='text'></zg-column>
        <zg-column index='importancia' header='Importancia' width="100" type='text'></zg-column>
        <zg-column index='area' header='Àrea' width="100" type='text'></zg-column>
        <zg-column index='recibo' header='Atendido por' width="100" type='text'></zg-column>
        <zg-column index='porcentaje' header='Avance' width="100" type='text'></zg-column>
       
        <zg-column align="center" filter="disabled" index='operaciones' header='Operaciones' width="100" type='text'></zg-column>
      </zg-colgroup>
    </zing-grid>
  </div>
</div>




@endsection