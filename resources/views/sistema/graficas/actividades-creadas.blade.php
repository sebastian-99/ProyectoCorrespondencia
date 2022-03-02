@extends('layout.layout')

@section('header')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/c3/0.7.20/c3.min.css" integrity="sha512-cznfNokevSG7QPA5dZepud8taylLdvgr0lDqw/FEZIhluFsSwyvS81CMnRdrNSKwbsmc43LtRd2/WMQV+Z85AQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.25/af-2.3.7/b-1.7.1/b-colvis-1.7.1/b-html5-1.7.1/b-print-1.7.1/cr-1.5.4/date-1.1.0/fc-3.3.3/fh-3.1.9/kt-2.6.2/r-2.2.8/rg-1.1.3/rr-1.2.8/sc-2.0.4/sb-1.1.0/sp-1.3.0/sl-1.3.3/datatables.min.css"/>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.25/af-2.3.7/b-1.7.1/b-colvis-1.7.1/b-html5-1.7.1/b-print-1.7.1/cr-1.5.4/date-1.1.0/fc-3.3.3/fh-3.1.9/kt-2.6.2/r-2.2.8/rg-1.1.3/rr-1.2.8/sc-2.0.4/sb-1.1.0/sp-1.3.0/sl-1.3.3/datatables.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #e9830e;
        color: white;

    }

    .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice__remove {
        color: #000000;
        cursor: pointer;
        display: inline-block;
        font-weight: bold;
        margin-right: 2px;
    }

    .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
        background-color: #416CC3;
        color: white;
        border: 1px solid black;
        border-radius: 0.2rem;
        padding: 0;
        padding-right: 5px;
        cursor: pointer;
        float: left;
        margin-top: 0.3em;
        margin-right: 5px;
    }
    </style>
@endsection

@section('content')
    @csrf
    <div class="container-fluid">
        <div class="card bg-light">
            <div class="card-header bg-success text-center">
                <h2>
                    @if(Auth()->user()->idtu_tipos_usuarios != 4)
                        Medidor de mis actividades creadas 
                    @endif
                    @if(Auth()->user()->idtu_tipos_usuarios == 4)
                        @php
                        $ar = Auth()->user()->idar_areas;

                        $director = DB::SELECT("SELECT CONCAT(titulo, ' ',nombre, ' ', app, ' ', apm) AS nombre FROM users WHERE idtu_tipos_usuarios = 2 AND idar_areas = $ar");
                        $nom = $director[0]->nombre;
                        @endphp
                        Medidor de actividades creadas por: {{$nom}}
                    @endif
                </h2>
            </div>
            <div class="card-body">
                <div class="form-row mt-3" id="dashboard_panel">
                    <div class="form-group col-md-4">
                        <label>Tipo de área</label>
                        <select class="custom-select" name="tipo_actividades[]" id="select_tipo_actividades" multiple style="color:back">
                            @foreach ($tipo_actividades as $tipo_actividad)
                                <option value="{{ $tipo_actividad->idtac }}"> {{  $tipo_actividad->nombre }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Fecha inicial</label>
                        <input type="date" class="form-control" name="inicio" id="fecha_inicial">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Fecha final</label>
                        <input type="date" class="form-control" name="fin" id="fecha_final">
                    </div>
                    <button class="btn btn-success" id="btn_buscar">
                        Buscar
                        <span class="spinner-border spinner-border-sm" id="spinner_estadisticas" hidden></span>
                    </button>
                </div>
                <div class="form-row mt-3">
                    <div class="col-md-3">
                        <label>Seguimientos</label>
                        <div id="grafico_actividades"></div>
                    </div>
                    <div class="col-md-3">
                        <label>Acuse de recibido</label>
                        <div id="grafico_acuse"></div>
                    </div>
                    <div class="col-md-3">
                        <label>Porcentaje de tipo áreas</label>
                        <div id="grafico_tipo_areas"></div>
                    </div>
                    <div class="col-md-3">
                        <label>Estatus</label>
                        <div id="grafico_de_status"></div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="table-responsive" id="tabla">

                </div>
            </div>
        </div>
@endsection
@section('scripts')
    <script>
        $('#select_tipo_actividades').select2();
        const user_id = '{{ $user }}';
        const _token = $('input[name="_token"]').val();
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/5.0.0/d3.min.js" integrity="sha512-55FY9DHtfMBE2epZhXrWn78so/ZT5/GCLim66+L83U5LghiYwVBAEris4/13Iab9S8C9ShJp3LQL/2raiaO+0w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/c3/0.7.20/c3.min.js" integrity="sha512-+IpCthlNahOuERYUSnKFjzjdKXIbJ/7Dd6xvUp+7bEw0Jp2dg6tluyxLs+zq9BMzZgrLv8886T4cBSqnKiVgUw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="module"  src="{{ asset('js/tipo-actividades/dashboard-actividades-creadas.js') }}"></script>
@endsection
