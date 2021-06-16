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
                        <th>Autor</th>
                        <th>Responsale</th>
                        <th>Asunto</th>
                        <th>Descripcion</th>
                        <th>Periodo</th>
                        <th>Inportancia</th>
                        <th>Area Responsable</th>
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
                            <td>
                                <a href="/admin/seguimiento/${dato.idreac}" class="btn btn-link">Ver Detalle</a>
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
