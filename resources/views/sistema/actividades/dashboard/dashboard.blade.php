@extends('layout.layout')

@section('header')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/c3/0.7.20/c3.min.css" integrity="sha512-cznfNokevSG7QPA5dZepud8taylLdvgr0lDqw/FEZIhluFsSwyvS81CMnRdrNSKwbsmc43LtRd2/WMQV+Z85AQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')
    .<div class="container-fluid">
        <div class="card bg-light">
            <div class="card-header bg-success text-center">
                <h2>Medidor de Actividades realizadas por Area  Administrativa</h2>
            </div>
            <div class="card-body">
                <div class="container">
                    <h4 class="text-right mb -3">Actividades del mes de enero</h4>
                </div>
                <div class="form-row" id="dashboard_panel">
                    <div class="form-group col-md-4">
                        <select class="custom-select" name="areas" id="select_tipo_area">
                            <option value="">--Area--</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area->idtar }}"> {{ $area->nombre }} </option>
                            @endforeach
                        </select>
                        <select class="custom-select mt-3" name="areas" id="select_area" hidden>
                        </select>

                        <div class="form-group">
                            <label for="year">Año</label>
                            <input type="number" class="form-control" id="year" min="2020" value="2020">
                        </div>

                        <div class="form-check mt-3" id="radios_rango_de_fechas">
                            <label class="form-check-label col-md-5">
                                <input type="radio" class="form-check-input" name="rango" id="" value="mensual">
                                Mensual
                            </label>
                            <label class="form-check-label col-md-6">
                                <input type="radio" class="form-check-input" name="rango" id="" value="semanal">
                                Semanal
                            </label>
                            <label class="form-check-label col-md-6">
                                <input type="radio" class="form-check-input" name="rango" id="" value="general">
                                General
                            </label>
                        </div>
                        <div class="form-group mt-3">
                          <select class="form-control" name="rango_inicial" id="rango_inicial">
                          </select>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-success" id="filtrar_busquedas" disabled>Buscar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/5.0.0/d3.min.js" integrity="sha512-55FY9DHtfMBE2epZhXrWn78so/ZT5/GCLim66+L83U5LghiYwVBAEris4/13Iab9S8C9ShJp3LQL/2raiaO+0w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/c3/0.7.20/c3.min.js" integrity="sha512-+IpCthlNahOuERYUSnKFjzjdKXIbJ/7Dd6xvUp+7bEw0Jp2dg6tluyxLs+zq9BMzZgrLv8886T4cBSqnKiVgUw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="module"  src="{{ asset('js/actividades/dashboard.js') }}"></script>
@endsection
