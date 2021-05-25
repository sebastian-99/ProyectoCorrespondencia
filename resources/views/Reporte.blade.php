@extends('layout/layout')
@section('content')


<body>
<div class="container mt-5">
    <h2 class="mb-4">Reporte de actividades / Oficios</h2>

    <table class="table table-bordered yajra-datatable "  >
   
        <thead>
            <tr>
                <th>Turno</th>
                <th>fecha_creacion</th>
                <th>asunto</th>
                <th>idu_users</th>
                <th>fecha_hora_inicio</th>
                <th>fecha_hora_fin</th>
                <th>importancia</th>
                <th>idar_areas</th>
                <th>status</th>
                <th>atendido por</th>
                <th>operaciones</th>
            </tr>
        </thead>
        <tbody>
        </tbody>

    </table>
</div>
   
<div class="modal fade" id="ajaxModel" value="1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h4 class="modal-title" id="modelHeading"></h4></div>
            <div class="modal-body">
                <form id="Detalles" name="Detalles" class="form-horzontal">
                    <div class="form-group"><label class="col-sm-2 control-label">Nombre atendio</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="" value="" disabled>
                    </div>
                    </div>
                    <div class="form-group"><label class="col-sm-2 control-label">Cargo</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="idar" name="idar" placeholder="" value="" disabled>
                    </div>
                    </div>
                    <div class="form-group"><label class="col-sm-2 control-label">Avance</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="avance" name="avance" placeholder="" value="" disabled>
                    </div>
                    </div>
                    <div class="form-group"><label class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="status" name="status" placeholder="" value=""disabled>
                    </div>
                    </div>
                    
                    <div class="form-group"><label class="col-sm-2 control-label">Acuse</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="acuse" name="acuse" placeholder="" value="" disabled>
                    </div>
                    </div>
                    
                    <div class="form-group"><label class="col-sm-2 control-label">Operaciones</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="modelextra" name="modelextra" placeholder="" value="" >
                    </div>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>

</body>


@stop