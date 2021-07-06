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
    const spinnerEstadisticas = $('#spinner_estadisticas')
    const btnBuscar = $('#btn_buscar')
    btnBuscar.click(()=>{
        spinnerEstadisticas.removeAttr('hidden')
        const inicio = $('#fecha_inicial').val()
        const fin = $('#fecha_final').val()
        const tipos_actividades = $('#select_tipo_actividades').val()
        console.log(tipos_actividades);
        $.ajax({
            type: 'POST',
            url: `/dashboard/${user_id}`,
            data : {
                _token,
                tipos_actividades,
                inicio,
                fin
            },
            success: data =>{
                console.log('peticion exitosa');
                console.log(data);
                let actividadesSinEntregar = 0
                let actividadesEnProceso = 0
                let actividadesCompletadas = 0
                let actividadesConAcuseDeRecibido = 0
                let actividadesSinAcuseDeRecibido = 0
                let actividadesCompletadasEnTiempo = 0
                let actividadesCompletadasFueraDeTiempo = 0
                let actividadesEnProcesoEnTiempo = 0
                let actividadesEnProcesoFueraDeTiempo = 0
                let totalActividades=[]
                data.forEach(dato=>{
                    actividadesSinEntregar += dato.actividadesSinEntregar
                    actividadesCompletadas += dato.actividadesCompletadas
                    actividadesConAcuseDeRecibido += dato.actividadesConAcuseDeRecibido
                    actividadesEnProceso += dato.actividadesEnProceso
                    actividadesSinAcuseDeRecibido += dato.actividadesSinAcuseDeRecibido

                    actividadesCompletadasEnTiempo += dato.actividadesCompletadasEnTiempo
                    actividadesCompletadasFueraDeTiempo += dato.actividadesCompletadasFueraDeTiempo
                    actividadesEnProcesoEnTiempo += dato.actividadesEnProcesoEnTiempo
                    actividadesEnProcesoFueraDeTiempo += dato.actividadesEnProcesoFueraDeTiempo

                    totalActividades.push([ dato.nombre, dato.actividadesTotales ])
                })
                generarGraficos(
                    actividadesSinEntregar,
                    actividadesEnProceso,
                    actividadesCompletadas,
                    actividadesConAcuseDeRecibido,
                    actividadesSinAcuseDeRecibido,
                    totalActividades,
                    actividadesCompletadasEnTiempo,
                    actividadesCompletadasFueraDeTiempo,
                    actividadesEnProcesoEnTiempo,
                    actividadesEnProcesoFueraDeTiempo
                )
                spinnerEstadisticas.attr('hidden',true)

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
        const tipos_actividades = $('#select_tipo_actividades').val()
        $.ajax({
            type: 'POST',
            data:{
                _token,
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
                        <th>Comunicado - Asunto</th>
                        <th>Creador</th>
                        <th>Avance</th>
                        <th>Responsable</th>
                        <th>Descripción</th>
                        <th>Período</th>
                        <th>Importancia</th>
                        <th>Área Responsable</th>
                        <th>Tipo Actividad</th>
                        <th>Número de Segumientos</th>
                        <th>Acciones</th>
                    </tr>
                `
                let tbody = ''
                data.map((element,key)=>{
                    const {actividades} = element
                    for (const key in actividades) {
                        tbody += `
                            <tr>
                                <td>${actividades[key].comunicado}-${actividades[key].asunto}</td>
                                <td>
                                    ${actividades[key].creador.titulo}
                                    ${actividades[key].creador.nombre}
                                    ${actividades[key].creador.app}
                                    ${actividades[key].creador.apm}
                                </td>
                                <td>${actividades[key].seguimiento ? `${actividades[key].seguimiento.porcentaje} %` : 'No existen seguimientos'}</td>
                                <td>${actividades[key].responsable}</td>
                                <td>${actividades[key].descripcion}</td>
                                <td>${actividades[key].periodo}</td>
                                <td>${actividades[key].importancia}</td>
                                <td>${actividades[key].area_responsable}</td>
                                <td>${actividades[key].tipo_actividad}</td>
                                <td>${actividades[key].seguimiento ? actividades[key].numero_de_seguimiento : 'No existen seguimientos'}</td>
                                <td>
                                    <a href="${actividades[key].firma ? `/seguimiento/${actividades[key].idac}` : `/actividades_asignadas` }" class="btn btn-link">${actividades[key].firma ? `Ver Detalle</a>`: 'No tienes acuse de recibido dirígete a mis actividades dando click aquí'}
                                </td>
                            </tr>
                        `
                    }
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
            total_actividades,
            actividades_completadas_en_tiempo,
            actividades_completadas_fuera_de_tiempo,
            actividades_en_proceso_en_tiempo,
            actividades_en_proceso_fuera_de_tiempo
        ){
        const graficoActividaes = c3.generate({
            bindto: '#grafico_actividades',
            data: {
                columns: [
                    ['sin seguimientos', actividades_sin_entregar],
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
                    route = `/dashboard/${user_id}/${route}`
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
                    route = `/dashboard/${user_id}/${route}`
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
                    const route = `/dashboard/${user_id}/get-actividades-por-area`
                    imprimirTablaConAjax(route,data.id)
                 },
            }
        });

        const graficoDeStatus = c3.generate({
            bindto: '#grafico_de_status',
            data: {
                columns: [
                    ['completadas en tiempo', actividades_completadas_en_tiempo],
                    ['completadas fuera de tiempo', actividades_completadas_fuera_de_tiempo],
                    ['en proceso en tiempo', actividades_en_proceso_en_tiempo],
                    ['en proceso fuera de tiempo', actividades_en_proceso_fuera_de_tiempo],
                ],
                type : 'pie',
                onclick: function (data) {
                    let route = ''
                    switch(data.index){
                        case 0:
                            route= `actividades-completadas-en-tiempo`
                        break;
                        case 1:
                            route= `actividades-completadas-fuera-de-tiempo`
                            break;
                        case 2:
                            route= `actividades-en-proceso-en-tiempo`
                            break;
                        case 3:
                            route= `actividades-en-proceso-fuera-de-tiempo`
                        break;
                    }
                    route = `/dashboard/${user_id}/${route}`
                    imprimirTablaConAjax(route)
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
