@extends('layout.layout')

@section('header')
    <link rel="stylesheet" href="{{ asset('css/actividades/dashboard.css') }}">
@endsection

@section('content')
    <div class="card dashboard-dark">
        <div class="card-header text-center text-white">
            <h2>Medidor de Actividades realizadas por Area  Administrativa</h2>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label class="text-white">Control Escolar</label>
                    <div class="circular-progress html"></div>
                </div>
                <div class="form-group col-md-4">
                    <label class="text-white">Control Escolar</label>
                    <div class="circular-progress html"></div>
                </div>
                <div class="form-group col-md-4">
                    <label class="text-white">Control Escolar</label>
                    <div class="circular-progress html"></div>
                </div>

            </div>

        </div>
    </div>
@endsection
