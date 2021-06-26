import Area from '/js/actividades/Area.js'

$(document).ready(()=>{



    const graficoActividaes = c3.generate({
        bindto: '#grafico_actividades',
        data: {
            columns: [
                ['sin enttregar', actividades_sin_entregar],
                ['en proceso', actividades_en_proceso],
                ['completadas', actividades_completadas],
            ],
            type : 'pie',
            onclick: function (data) {
                let route = ''
                switch(data.index){
                    case 0:
                        route= `get-actividades-sin-entregar`
                    break;
                    case 1:
                        route= `get-actividades-en-proceso`
                        break;
                    case 2:
                        route= `get-actividades-completadas`
                        break;
                }
                route = `panel/${route}`
                imprimirTablaConAjax(route)
             },
        }
    });

    const graficoAcuse = c3.generate({
        bindto: '#grafico_acuse',
        data: {
            columns: [
                ['con acuse', actividades_con_acuse_de_recibido],
                ['sin acuse', actividades_sin_acuse_de_recibido],
            ],
            type : 'pie',
            onclick: function (data) {
                let route = ''
                switch(data.index){
                    case 0:
                        route= `get-actividades-con-acuse-de-recibido`
                    break;
                    case 1:
                        route= `get-actividades-sin-acuse-de-recibido`
                        break;
                }
                route = `panel/${route}`
                imprimirTablaConAjax(route)
             },
        }
    });



    const scriptDataTables = `
    $('#table tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    } );

    $('#table').DataTable({
        responsive : true,
        language: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json',
        initComplete: function () {
            this.api().columns().every( function () {
                var that = this;
                $( 'input', this.footer() ).on( 'keyup change clear', function () {
                    if ( that.search() !== this.value ) {
                        that
                            .search( this.value )
                            .draw();
                    }
                } );
            } );
        }
    })
`
    const area = new Area()

    $('#actividades_de_hoy').click(()=>{
        const route = `/panel/get-actividades-hoy/${user_id}`
        imprimirTablaConAjax(route)
    })

    $('#actividades_pendientes').click(()=>{
        const route = `/panel/get-actividades-pendientes/${user_id}`
        imprimirTablaConAjax(route)
    })

    $('#actividades_del_mes').click(()=>{
        const route = `/panel/get-actividades-por-mes/${user_id}`
        imprimirTablaConAjax(route)
    })

    $('#actividades_cerradas').click(()=>{
        const route = `/panel/get-actividades-cerradas/${user_id}`
        imprimirTablaConAjax(route)
    })

    $('#actividades_en_seguimiento_concluidas').click(()=>{
        const route = `/panel/get-actividades-en-seguimiento/${user_id}`
        imprimirTablaConAjax(route)
    })

    function imprimirTablaConAjax(route){
        $.ajax({
            type: 'GET',
            url: route,
            success: data=>{
                const thead = `
                    <tr>
                        <th>Turno</th>
                        <th>Creador</th>
                        <th>Responsale</th>
                        <th>Asunto</th>
                        <th>Descripcion</th>
                        <th>Periodo</th>
                        <th>Inportancia</th>
                        <th>Area Responsable</th>
                        <th>Tipo Actividad</th>
                        <th>Avance</th>
                        <th>Numero de Segumientos</th>
                        <th>Acciones</th>
                    </tr>
                `
                let tbody = ''
                data.forEach(dato => {
                    tbody += `
                        <tr>
                            <td>${dato.turno}</td>
                            <td>
                                ${dato.creador.titulo}
                                ${dato.creador.nombre}
                                ${dato.creador.app}
                                ${dato.creador.apm}
                            </td>
                            <td>${dato.responsable}</td>
                            <td>${dato.asunto}</td>
                            <td>${dato.descripcion}</td>
                            <td>${dato.periodo}</td>
                            <td>${dato.importancia}</td>
                            <td>${dato.area_responsable}</td>
                            <td>${dato.tipo_actividad}</td>
                            <td>${dato.seguimiento ? `${dato.porcentaje_seguimiento} %` : 'No existen seguimientos'}</td>
                            <td>${dato.seguimiento ? dato.numero_de_seguimiento : 'No existen seguimientos'}</td>
                            <td>
                                <a href="${dato.firma ? `/seguimiento/${dato.idac}` : `/actividades_asignadas` }" class="btn btn-link">${dato.firma ? `Ver Detalle</a>`: 'No tienes acuse de recibido dirijete a mis actividades dando click aquí'}
                            </td>
                        </tr>
                    `
                });
                area.imprimirDatosEnTabla(thead,tbody, $('#tabla'),scriptDataTables)
            },
            error: error=>{
                console.error(error);
            }
        })
    }
})
