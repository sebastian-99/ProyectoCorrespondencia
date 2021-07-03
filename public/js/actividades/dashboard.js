import Area from '/js/actividades/Area.js'

moment.locale('es', {
    months: 'Enero_Febrero_Marzo_Abril_Mayo_Junio_Julio_Agosto_Septiembre_Octubre_Noviembre_Diciembre'.split('_'),
    monthsShort: 'Enero._Feb._Mar_Abr._May_Jun_Jul._Ago_Sept._Oct._Nov._Dec.'.split('_'),
    weekdays: 'Domingo_Lunes_Martes_Miercoles_Jueves_Viernes_Sabado'.split('_'),
    weekdaysShort: 'Dom._Lun._Mar._Mier._Jue._Vier._Sab.'.split('_'),
    weekdaysMin: 'Do_Lu_Ma_Mi_Ju_Vi_Sa'.split('_')
  }
);

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


$('document').ready(()=>{

    const area = new Area()

    const btnBuscar = $('#btn_buscar')
    btnBuscar.click(()=>{
        const inicio = $('#fecha_inicial').val()
        const fin = $('#fecha_final').val()
        const areas =$('#select_areas').val()
        const tipos_actividades = $('#select_tipo_actividades').val()

        console.log(tipos_actividades);
        $.ajax({
            type: 'POST',
            url: `/admin/dashboard`,
            data : {
                _token,
                areas,
                tipos_actividades,
                inicio,
                fin
            },
            success: data =>{
                console.log('peticion exitosaa');
                console.log(data);
                let actividadesSinEntregar = 0
                let actividadesEnProceso = 0
                let actividadesCompletadas = 0
                let actividadesConAcuseDeRecibido = 0
                let actividadesSinAcuseDeRecibido = 0
                let totalActividades=[]
                data.forEach(dato=>{
                    actividadesSinEntregar += dato.actividadesSinEntregar
                    actividadesCompletadas += dato.actividadesCompletadas
                    actividadesConAcuseDeRecibido += dato.actividadesConAcuseDeRecibido
                    actividadesEnProceso += dato.actividadesEnProceso
                    actividadesSinAcuseDeRecibido += dato.actividadesSinAcuseDeRecibido
                    totalActividades.push([ dato.nombre, dato.actividadesTotales ])
                })
                generarGraficos(
                    actividadesSinEntregar,
                    actividadesEnProceso,
                    actividadesCompletadas,
                    actividadesConAcuseDeRecibido,
                    actividadesSinAcuseDeRecibido,
                    totalActividades
                )


            },
            error : error =>{
                console.log(error);
            }
        })
    })

    function imprimirTablaConAjax(route, tipo_area = ''){
        console.log(route)
        const inicio = $('#fecha_inicial').val()
        const fin = $('#fecha_final').val()
        const areas =$('#select_areas').val()
        const tipos_actividades = $('#select_tipo_actividades').val()

        $.ajax({
            type: 'POST',
            data:{
                _token,
                areas,
                tipos_actividades,
                inicio,
                fin,
                tipo_area
            },
            url: route,
            success: data=>{
                console.log(data);
                const thead = `
                    <tr>
                        <th>Turno</th>
                        <th>Autor</th>
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
                data.forEach(element=>{
                    element.actividades.forEach(dato => {
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
                                    ${dato.seguimiento ? `<a href="/admin/seguimiento/${dato.idreac}" class="btn btn-link">Ver Detalle</a>` : 'No existen seguimientos'}
                                </td>
                            </tr>
                        `
                    });
                })
                area.imprimirDatosEnTabla(thead,tbody, $('#tabla'),scriptDataTables)
            },
            error: error=>{
                console.error(error);
            }
        })
    }


    function generarGraficos(
            actividades_sin_entregar,
            actividades_en_proceso,
            actividades_completadas,
            actividades_con_acuse_de_recibido,
            actividades_sin_acuse_de_recibido,
            total_actividades
        ){
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
                    route = `/admin/dashboard/${route}`
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
                    route = `/admin/dashboard/${route}`
                    imprimirTablaConAjax(route)
                 },
            }
        });
        const graficoTipoAreas = c3.generate({
            bindto: '#grafico_tipo_areas',
            data: {
                columns: total_actividades,
                type : 'pie',
                onclick: function (data) {
                    const route = `/admin/dashboard/get-actividades-por-area`
                    imprimirTablaConAjax(route,data.id)
                 },
            }
        });
    }



})

/*
    Calcular seguimiento
    porcentajes
    estilos panel
*/
