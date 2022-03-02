@extends('layout.layout')
@section('content')
@section('header')

<script src='{{asset('src/js/zinggrid.min.js')}}'></script>
<script src='{{asset('src/js/zinggrid-es.js')}}'></script>

<!-- Libreria para usar xlsx en js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.13.1/xlsx.full.min.js"></script>
<script src="{{ asset('src/js/xlsx.js') }}"></script>

<script>
    if (es) ZingGrid.registerLanguage(es, 'custom');
</script>

@endsection
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-sm-11">
                <h3>Reporte de actividades creadas por
                    @if(Auth()->user()->idtu_tipos_usuarios == 2)
                    mí asistente 
                    @endif
                    @if(Auth()->user()->idtu_tipos_usuarios == 4)
                    {{$dir}}
                    @endif
                </h3>
            </div>
        </div>
        <div class="text-center">
            <button id="btn_exportar_excel" type="button" class="btn btn-success">
                Exportar a EXCEL
            </button>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <label for="">Fecha orden:</label>
            </div>
            <div class="col-sm-4">
                <label for="">Fecha Inicio:</label>
            </div>

            <div class="col-sm-4">
                <label for="">Fecha Fin:</label>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <select class="form-control" name="fecha_orden" id="fecha_orden">
                    <option value="0">Todos los registros</option>
                    <option value="1">Fecha inicio</option>
                    <option value="2">Fecha fin</option>
                </select>
                <button type="button" class="btn btn-primary mt-1" id="button">Enviar</button> <button type="button" class="btn btn-primary mt-1" id="limpiar">Limpiar</button>
            </div>
            <div class="col-sm-4">
                <input class="form-control" name="fechaIni" id="fechaIni" type="date" readonly>

            </div>
            <div class="col-sm-4">
                <input class="form-control" name="fechaFin" id="fechaFin" type="date" readonly>
            </div>
        </div>
    </div>
    <div class="card-body">
        <zing-grid lang="custom" caption='Reporte de oficios' sort search pager page-size='10' page-size-options='1,2,3,4,5,10' layout='row' viewport-stop theme='android' id='zing-grid' filter selector data="{{$json}}">
            <zg-colgroup>
                <zg-column index='turno' header='Turno' width="100" type='number'></zg-column>
                <zg-column index='asunto' header='Asunto' width="200" type='text'></zg-column>
                <zg-column index='tipo_actividad' header='Tipo de actividad' width="200" type='text'></zg-column>
                <zg-column index='descripcion' header='Descripción' width="200" type='text'></zg-column>
                <zg-column index='fecha_creacion' header='Fecha creación' width="200" type='text'></zg-column>
                <zg-column index='creador' header='Creador' width="200" type='text'></zg-column>
                <zg-column index='periodo' header='Periodo' width="220" type='text'></zg-column>
                <zg-column index='importancia' header='Importancia' width="130" type='text'></zg-column>
                <zg-column index='nombre' header='Área responsable' width="170" type='text'></zg-column>
                <zg-column index='avance' header='Avance' width="120" type='text'></zg-column>
                <zg-column index='atendido_por' header='Atendido por' width="135" type='text'></zg-column>
                <zg-column index='estatus' header='Estatus' width="120" type='text'></zg-column>
                <zg-column align="center" filter="disabled" index='operaciones' header='Operaciones' type='text'></zg-column>
            </zg-colgroup>
            </zing->
    </div>
    <script>
        $("#fecha_orden").on("change", function() {

            if ($(this).val() == "3") {

                $("#fecha").attr("readonly", true);
                $("#fecha").val("");

            } else {

                $("#fecha").removeAttr("readonly");
            }



        });
        console.log($("#fecha_orden").val());
        $('#button').on("click", function() {

            let fecha_orden = $('#fecha_orden').val()
            let fechaIni = $('#fechaIni').val()
            let fechaFin = $('#fechaFin').val()
            $.ajax({
                type: "GET",
                url: "{{route('ajax_filtro_fecha')}}",
                data: {
                    fecha_orden: fecha_orden,
                    fechaIni: fechaIni,
                    fechaFin: fechaFin
                },
                success: function(data) {
                    console.log(data);
                    $('#zing-grid').removeAttr('data');
                    $('#zing-grid').attr("data", data);
                }
            })

        })
        $('#limpiar').on("click", function() {
            $("#fechaIni").val("");
            $("#fechaFin").val("");
            $("#fecha_orden").val(0);
            $('#fechaIni').attr("readOnly", true);
            $('#fechaFin').attr("readOnly", true);
            $('#fechaIni').val("");
            $('#fechaFin').val("");
        })
        $('#fecha_orden').on("change", function() {
            if ($(this).val() == 0) {
                $('#fechaIni').attr("readOnly", true);
                $('#fechaFin').attr("readOnly", true);
                $('#fechaIni').val("");
                $('#fechaFin').val("");
            } else {
                $('#fechaIni').removeAttr("readOnly");
                $('#fechaFin').removeAttr("readOnly");
            }
        })
    </script>
    @endsection



<!-- E x c e l -->


@section('scripts')
    <script>
        $(document).ready(() => {

            const excel = () => {

                let date = new Date(),
                    sheet, data, columns, rows, zing_grid = document.querySelector('zing-grid');

                const headers = ["A3", "B3", "C3", "D3", "E3", "F3", "G3", "H3", "I3", "J3", "K3", "L3"];

                data = zing_grid.getData({
                    headers: true,
                    cols: 'visible',
                    rows: 'visible',
                });

                sheet = XLSX.utils.aoa_to_sheet([
                    ["Reporte de actividades creadas"],
                ]);

                XLSX.utils.sheet_add_aoa(sheet, [
                    [`Fecha de reporte: ${ date.toLocaleDateString() } ${ date.getHours() }:${ date.getMinutes() }`],
                ], { origin: -1 } );

                XLSX.utils.sheet_add_aoa( sheet, [
                   ["Turno", "Asunto", "Tipo de Actividades",
                    "Descripción", "Fecha de Creación", "Creador",
                    "Perìodo", "Importancia", "Àrea responsable", "Avance", "Atendido por",
                    "Estatus"],
                ], { origin: -1 } );

                for ( value of data )
                {
                    XLSX.utils.sheet_add_aoa( sheet, [
                        [ value.turno, value.asunto, value.tipo_actividad, value.descripcion, value.fecha_creacion,
                          value.creador, value.periodo, value.importancia, value.nombre, value.avance, value.atendido_por, value.estatus],
                    ], { origin: -1 } );
                }

                // Size columns
                columns = [
                    {wch: 20}, // turno
                    {wch: 40}, // asunto
                    {wch: 25}, // tipo de actividad
                    {wch: 40}, // descripción
                    {wch: 20}, // fecha de creación
                    {wch: 30}, // creadi por (creador)
                    {wch: 30}, // periodo
                    {wch: 20}, // importancia
                    {wch: 30}, // área
                    {wch: 20}, // avance
                    {wch: 25}, // atendido por
                    {wch: 30}, // estatus
                ];

                sheet['!cols'] = columns;

                sheet["!rows"] = rows;

                let mergeA1K1 = {
                        s: {r: 0,c: 0},
                        e: {r: 0,c: 10}
                    }; // Merge A1:K1

                let mergeA2K2 = {
                        s: {r: 1,c: 0},
                        e: {r: 1,c: 10}
                    }; // Merge A2:K2

                if (!sheet['!merges']) sheet['!merges'] = [];

                sheet['!merges'].push(mergeA1K1);

                sheet['!merges'].push(mergeA2K2);

                // set the style of target cell
                sheet["A1"].s = {
                    font: {
                        name: 'Arial',
                        sz: 18,
                        bold: true,
                        color: {
                            rgb: "00000000"
                        }
                    },
                    alignment: {
                        horizontal: 'center',
                    },
                };

                sheet["A2"].s = {
                    font: {
                        name: 'Arial',
                        sz: 14,
                        bold: false,
                        color: {
                            rgb: "00000000"
                        }
                    },
                    alignment: {
                        horizontal: 'center',
                    },
                };

                for (value of headers) {

                    sheet[value].s = {
                        fill: {
                            patternType: 'solid',
                            fgColor: {
                                rgb: "43B105"
                            },
                            bgColor: {
                                rgb: "43B105"
                            },
                        },
                        font: {
                            name: 'Arial',
                            sz: 12,
                            bold: false,
                            color: {
                                rgb: "FFFFFFFF"
                            },
                        },
                        alignment: {
                            horizontal: 'center',
                        },
                    };

                }

                let book = XLSX.utils.book_new();

                XLSX.utils.book_append_sheet( book, sheet, 'Hoja 1' );

                XLSX.writeFile(book, 'Reporte_de_Actividades_Creadas.xlsx');

            }

            $('#btn_exportar_excel').on('click', () => {

                excel();

            });


        });
    </script>
    @endsection
