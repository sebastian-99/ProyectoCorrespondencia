import Area from '/js/actividades/Area.js'

$(document).ready(()=>{
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
                        <th>Responsable</th>
                        <th>Asunto</th>
                        <th>Descripción</th>
                        <th>Período</th>
                        <th>Importancia</th>
                        <th>Área Responsable</th>
                        <th>Tipo Actividad</th>
                        <th>Avance</th>
                        <th>Número de Segumientos</th>
                        <th>Acciones</th>
                    </tr>
                `
                let tbody = ''
                console.log(data);
                data.forEach(dato => {
                    tbody += `
                        <tr>
                            <td>${dato.turno}</td>
                            <td>
                                ${dato.creador ? dato.creador.titulo : ''}
                                ${dato.creador ? dato.creador.nombre : ''}
                                ${dato.creador ? dato.creador.app : ''}
                                ${dato.creador ? dato.creador.apm : ''}
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
                                <a href="${dato.firma ? `/seguimiento/${dato.idac}` : `/actividades_asignadas` }" class="btn btn-link">${dato.firma ? `Ver Detalle</a>`: 'No tienes acuse de recibido dirígete a mis actividades dando click aquí'}
                            </td>
                        </tr>
                    `
                })
                area.imprimirDatosEnTabla(thead,tbody, $('#tabla'),scriptDataTables)
            },
            error: error=>{
                console.error(error);
            }
        })
    }
})
