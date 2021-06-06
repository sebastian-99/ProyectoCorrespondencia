@extends('layout.layout')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="box">
            <div class="box-body">
                <div class="card-deck mb-4">
                    <div data-route="" class="card col-lg-6 col-sm-12" style="background-color: #0664c4; cursor: pointer;">
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
                                        <h5>{{ $actividadesHoy }}</h5>
                                    </div>
                                    <h2></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div data-route="" class="card col-lg-6 col-sm-12" style="background-color: #00b29a; cursor: pointer;">
                        <div class="card-body text-white">
                            <div class="row text-center">
                                <div class="col-lg-4 col-sm-12">
                                    <i class="fa fa-list-ul mt-5 pt-5" aria-hidden="true" style="font-size: 80px"></i>
                                </div>
                                <div class="col-lg-8 col-sm-12">
                                    <h5>PENDIENTES DE REVISIÓN</h5>
                                    <div class="text-center mt-5">
                                        <h2>{{ $actividadesPendientes }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-deck">
                    <div data-route="" class="card col-lg-6 col-sm-12" style="background-color: #00b29a; cursor: pointer;">
                        <div class="card-body text-white">
                            <div class="row text-center">
                                <div class="col-lg-4 col-sm-12">
                                    <i class="fa fa-calendar mt-5 pt-5 mb-2" aria-hidden="true" style="font-size: 80px"></i>
                                </div>
                                <div class="col-lg-8 col-sm-12">
                                    <h5 style="text-transform: uppercase;">ACTIVIDADES DEL MES DE {{ Carbon\Carbon::now()->locale('es')->isoFormat('MMMM') }} </h5>
                                    <div class="text-center">
                                        <h2>{{ $actividadesPorMes }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div data-route="" class="card col-lg-6 col-sm-12" style="background-color:#1d283a; cursor: pointer;">
                        <div class="card-body text-white">
                            <div class="row text-center">
                                <div class="col-lg-4 col-sm-12">
                                    <i class="fa fa-clock-o mt-5 pt-5" aria-hidden="true" style="font-size: 80px"></i>
                                </div>
                                <div class="col-lg-8 col-sm-12">
                                    <h5>Actividades Cerradas</h5>
                                    <b>{{ $actividadesCerradas['concluidas']}} de {{ $actividadesCerradas['total'] }}</b><br>
                                    <b> ACTIVIDADES EN SEGUIMIENTO</b><br>
                                    <div class="text-center">
                                        <h2>
                                            {{ $actividadesCerradas['faltantes']}} de {{ $actividadesCerradas['total'] }}
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
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded",() => {
        let cards = document.getElementsByClassName('card');
        for (let i = 0; i < cards.length; i++) {
            cards[i].addEventListener('click',() => {
                let route = cards[i].dataset.route;
                setTimeout(() => {
                    window.location.href = route, 5000
                });
            });
        }
    });
</script>
@endsection
