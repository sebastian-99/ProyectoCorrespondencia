@extends('layout.layout')

@section('header')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.25/af-2.3.7/b-1.7.1/b-colvis-1.7.1/b-html5-1.7.1/b-print-1.7.1/cr-1.5.4/date-1.1.0/fc-3.3.3/fh-3.1.9/kt-2.6.2/r-2.2.9/rg-1.1.3/rr-1.2.8/sc-2.0.4/sb-1.1.0/sp-1.3.0/sl-1.3.3/datatables.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/c3/0.7.20/c3.min.css" integrity="sha512-cznfNokevSG7QPA5dZepud8taylLdvgr0lDqw/FEZIhluFsSwyvS81CMnRdrNSKwbsmc43LtRd2/WMQV+Z85AQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

@endsection

@section('content')
<div class="form-row"> 
    <div class="form-group col-12">
        <div class="box">
            <div class="box-body">
                <div class="card-deck mb-4">
                    <div data-route="#" class="card col-lg-6 col-sm-12"
                        style="background-color: #0664c4; cursor: pointer;"
                        id="actividades_de_hoy"
                    >
                        <div class="card-body text-white">
                            <div class="row text-center">
                                <div class="col-lg-4 col-sm-12 mt-4">
                                    <i class="far fa-address-book" style="font-size: 80px"></i>
                                </div>
                                <div class="col-lg-8 col-sm-12">
                                    <div class="mt-3">
                                        <h5>
                                            @if(Auth()->user()->idtu_tipos_usuarios != 4)
                                                ACTIVIDADES DE HOY 
                                            @endif
                                            @if(Auth()->user()->idtu_tipos_usuarios === 4)
                                                ACTIVIDADES DE HOY PARA {{$nombre}}
                                            @endif    
                                        </h5>
                                    </div>
                                    <div class="mt-3">
                                        <h2>{{ $actividades_hoy }}</h2>
                                    </div>
                                    <h2></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div data-route="#" class="card col-lg-6 col-sm-12"
                        style="background-color: #00b29a; cursor: pointer;"
                        id="actividades_pendientes"
                    >
                        <div class="card-body text-white">
                            <div class="row text-center">
                                <div class="col-lg-4 col-sm-12">
                                    <i class="fa fa-list-ul mt-5 pt-5" aria-hidden="true" style="font-size: 80px"></i>
                                </div>
                                <div class="col-lg-8 col-sm-12">
                                    <h5>
                                        @if(Auth()->user()->idtu_tipos_usuarios != 4)
                                            ACTIVIDADES PENDIENTES DE REVISIÓN
                                        @endif
                                        @if(Auth()->user()->idtu_tipos_usuarios == 4)
                                            ACTIVIDADES PENDIENTES DE REVISIÓN PARA {{$nombre}}
                                        @endif
                                    </h5>
                                    <div class="text-center mt-5">
                                        <h2>{{ $actividades_pendientes }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-deck">
                    <div data-route="#" class="card col-lg-6 col-sm-12"
                        style="background-color: #00b29a; cursor: pointer;"
                        id="actividades_del_mes"
                    >
                        <div class="card-body text-white">
                            <div class="row text-center">
                                <div class="col-lg-4 col-sm-12">
                                    <i class="fa fa-calendar mt-5 pt-5 mb-2" aria-hidden="true" style="font-size: 80px"></i>
                                </div>
                                <div class="col-lg-8 col-sm-12">
                                    <h5 style="text-transform: uppercase;">
                                        @if(Auth()->user()->idtu_tipos_usuarios != 4)
                                            ACTIVIDADES DEL MES DE {{ Carbon\Carbon::now()->locale('es')->isoFormat('MMMM') }}
                                        @endif
                                        @if(Auth()->user()->idtu_tipos_usuarios == 4)
                                            ACTIVIDADES DEL MES DE {{ Carbon\Carbon::now()->locale('es')->isoFormat('MMMM') }} PARA {{$nombre}}
                                        @endif
                                    </h5>
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
                                <div class="col-lg-4 col-sm-12 mt-3">
                                    <i class="fas fa-user-clock" aria-hidden="true" style="font-size: 80px"></i>
                                </div>
                                <div class="col-lg-8 col-sm-12">
                                    <div id="actividades_cerradas">
                                        <h5>ACTIVIDADES CERRADAS</h5>
                                        <h2>{{ $actividades_cerradas['concluidas']}} de {{ $actividades_cerradas['total'] }}</h2><br>
                                    </div>
                                    <div id="actividades_en_seguimiento_concluidas">
                                        <h5> 
                                            @if(Auth()->user()->idtu_tipos_usuarios != 4)
                                                ACTIVIDADES CERRADAS
                                            @endif
                                            @if(Auth()->user()->idtu_tipos_usuarios == 4)
                                                ACTIVIDADES CERRADAS DEL {{$nombre}}
                                            @endif
                                        </h5><br>
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
        <div class="table-responsive"  id="tabla">
        </div>
    </div>


@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.25/af-2.3.7/b-1.7.1/b-colvis-1.7.1/b-html5-1.7.1/b-print-1.7.1/cr-1.5.4/date-1.1.0/fc-3.3.3/fh-3.1.9/kt-2.6.2/r-2.2.9/rg-1.1.3/rr-1.2.8/sc-2.0.4/sb-1.1.0/sp-1.3.0/sl-1.3.3/datatables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/5.0.0/d3.min.js" integrity="sha512-55FY9DHtfMBE2epZhXrWn78so/ZT5/GCLim66+L83U5LghiYwVBAEris4/13Iab9S8C9ShJp3LQL/2raiaO+0w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/c3/0.7.20/c3.min.js" integrity="sha512-+IpCthlNahOuERYUSnKFjzjdKXIbJ/7Dd6xvUp+7bEw0Jp2dg6tluyxLs+zq9BMzZgrLv8886T4cBSqnKiVgUw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        const user_id = '{{ $user }}';
        //console.log(user_id);
    </script>
    <script  type="module" src="/js/panel/panel.js"></script>
@endsection
@endsection
