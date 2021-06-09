@extends('layout.layout')

@section('header')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.25/af-2.3.7/b-1.7.1/b-colvis-1.7.1/b-html5-1.7.1/b-print-1.7.1/cr-1.5.4/date-1.1.0/fc-3.3.3/fh-3.1.9/kt-2.6.2/r-2.2.8/rg-1.1.3/rr-1.2.8/sc-2.0.4/sb-1.1.0/sp-1.3.0/sl-1.3.3/datatables.min.css"/>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.25/af-2.3.7/b-1.7.1/b-colvis-1.7.1/b-html5-1.7.1/b-print-1.7.1/cr-1.5.4/date-1.1.0/fc-3.3.3/fh-3.1.9/kt-2.6.2/r-2.2.8/rg-1.1.3/rr-1.2.8/sc-2.0.4/sb-1.1.0/sp-1.3.0/sl-1.3.3/datatables.min.js"></script>
@endsection

@section('content')
<div class="form-row">
    <div class="form-group col-12">
        <div class="box">
            <div class="box-body">
                <div class="card-deck mb-4">
                    <div data-route="#" class="card col-lg-6 col-sm-12" style="background-color: #0664c4; cursor: pointer;">
                        <div class="card-body text-white">
                            <div class="row text-center">
                                <div class="col-lg-4 col-sm-12 mt-4">
                                    <i class="far fa-address-book" style="font-size: 80px"></i>
                                </div>
                                <div class="col-lg-8 col-sm-12">
                                    <div class="mt-3">
                                        <h5>EVENTOS DE HOY</h5>
                                    </div>
                                    <div class="mt-3">
                                        <h5>{{ $actividades_hoy }}</h5>
                                    </div>
                                    <h2></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div data-route="#" class="card col-lg-6 col-sm-12" style="background-color: #00b29a; cursor: pointer;">
                        <div class="card-body text-white">
                            <div class="row text-center">
                                <div class="col-lg-4 col-sm-12">
                                    <i class="fa fa-list-ul mt-5 pt-5" aria-hidden="true" style="font-size: 80px"></i>
                                </div>
                                <div class="col-lg-8 col-sm-12">
                                    <h5>ACTIVIDADES PENDIENTES DE REVISIÓN</h5>
                                    <div class="text-center mt-5">
                                        <h2>{{ $actividades_pendientes }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-deck">
                    <div data-route="#" class="card col-lg-6 col-sm-12" style="background-color: #00b29a; cursor: pointer;">
                        <div class="card-body text-white">
                            <div class="row text-center">
                                <div class="col-lg-4 col-sm-12">
                                    <i class="fa fa-calendar mt-5 pt-5 mb-2" aria-hidden="true" style="font-size: 80px"></i>
                                </div>
                                <div class="col-lg-8 col-sm-12">
                                    <h5 style="text-transform: uppercase;">ACTIVIDADES DEL MES DE {{ Carbon\Carbon::now()->locale('es')->isoFormat('MMMM') }} </h5>
                                    <div class="text-center">
                                        <h2>{{ $actividades_por_mes }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div data-route="#" class="card col-lg-6 col-sm-12" style="background-color:#1d283a; cursor: pointer;">
                        <div class="card-body text-white">
                            <div class="row text-center">
                                <div class="col-lg-4 col-sm-12">
                                    <i class="fa fa-clock-o mt-5 pt-5" aria-hidden="true" style="font-size: 80px"></i>
                                </div>
                                <div class="col-lg-8 col-sm-12">
                                    <h5>Actividades Cerradas</h5>
                                    <b>{{ $actividades_cerradas['concluidas']}} de {{ $actividades_cerradas['total'] }}</b><br>
                                    <b> ACTIVIDADES EN SEGUIMIENTO CONCLUIDAS</b><br>
                                    <div class="text-center">
                                        <h2>
                                            {{ $actividades_en_seguimiento['completadas']}} de {{ $actividades_en_seguimiento['total'] }}
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

        </div>

            </div>
        </div>
    </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
@endsection
