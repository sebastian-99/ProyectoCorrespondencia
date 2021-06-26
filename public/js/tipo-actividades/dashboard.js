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
    area.rangoSemanal()

    const selectTipoArea = $('#select_tipo_area')
    const selectArea = $('#select_area')
    const radiosDeRangoDeFechas = $('#radios_rango_de_fechas')
    const selectRango = $('#rango_inicial')
    const btnFiltrarBusquedas = $('#filtrar_busquedas')
    const inputYear = $('#year')

    selectArea.change(()=>{
        if (!selectArea.val()){
            //radiosDeRangoDeFechas.attr('hidden',true)
            return
        }
        radiosDeRangoDeFechas.removeAttr('hidden')
    })

    area.generarHtmlDelGrafico('Prorcentaje total de actividades','gauge_chart')
    //area.generarGreficoGauge()

    area.generarHtmlDelGrafico('Estado de las Actividades','pie_chart')
    //area.generarGreficoPie()

    $('input:radio[name=rango]').click(()=>{
        const rango = $('input:radio[name=rango]:checked').val()
        switch(rango){
            case 'mensual':
                selectRango.empty()
                selectRango.append('<option value="">-Selecciona Mes inicial-</option>')
                moment.months().forEach((mes,indice)=>{
                    const template = `
                        <option value="${indice}">${mes}</option>
                    `
                    selectRango.append(template)
                })
                btnFiltrarBusquedas.removeAttr('disabled')
                //area.rangoMensual(selectRango.val(),selectRangoFinal.val())
                break
            case 'semanal':
                const year = `01/01/${inputYear.val()}`
                selectRango.empty()
                selectRango.append('<option value="">-Selecciona Semana Inicial-</option>')
                for (let index = 1; index <= moment().weeksInYear(); index++) {
                    const template = `
                        <option value="${index}">
                            DEL
                            ${moment(year).set({week: index, day: 0}).format('DD-MMM-YYYY') }
                            AL
                            ${moment(year).set({week: index, day: 6}).format('DD-MMM-YYYY') }
                            </option>
                    `
                    selectRango.append(template)
                }
                btnFiltrarBusquedas.removeAttr('disabled')
                //area.rangoSemanal()
                break
            case 'general':
                //area.rangoGeneral()
                break
            default :

                break
        }
    })


    btnFiltrarBusquedas.click(()=>{

        //const areaAdministrativa = selectArea.val()
        const tipoActividad = selectArea.val()
        const rangoInicial = Number(selectRango.val())
        const year = inputYear.val()

        const rango = $('input:radio[name=rango]:checked').val()
        switch (rango){
            case 'mensual':
                    const mes = Number(moment().month(rangoInicial).format('MM'))
                    $.ajax({
                        type: 'GET',
                        url:  `/dashboard/${user_id}/get-actividades-ṕor-mes/${tipoActividad}/${year}/${mes}`,
                        success: data =>{
                            const gaugeChart ={
                                columns: [[data.area.nombre, data.promedio]],
                                type: 'gauge',
                                onclick: (data, i) => {
                                    const route = `/dashboard/${user_id}/get-actividades-totales/${$('#select_area').val()}`
                                    console.log(route);
                                    imprimirTablaConAjax(route)
                                },
                            }
                            area.generarGreficoGauge(gaugeChart)

                            const parametrosGrafico = {
                                columns: [
                                    ['sin entregar', data.actividades.incompletas],
                                    ['en proceso', data.actividades.enProceso],
                                    ['completadas', data.actividades.completadas],
                                ],
                                type : 'pie',
                                onclick: data=>{
                                    clickGraficoPie(data)
                                }
                            }
                            area.generarGreficoPie(parametrosGrafico);
                        },
                        error: error =>{
                            console.log(error);
                        },
                    })
                break
            case 'semanal':
                    const diaInicialDeLaSemana = moment(year).set({week: rangoInicial, day: 0}).format('DD-MM-YYYY')
                    const diaFinallDeLaSemana = moment(year).set({week: rangoInicial, day: 6}).format('DD-MM-YYYY')
                    $.ajax({
                        type: 'GET',
                        url:  `/dashboard/${user_id}/get-actividades-ṕor-rango-de-fechas/${tipoActividad}/${diaInicialDeLaSemana}/${diaFinallDeLaSemana}`,
                        success: data =>{
                            area.generarGreficoGauge([[data.area.nombre, data.promedio]])
                            area.setGraficoPie([
                                ['sin entregar', data.actividades.incompletas],
                                ['en proceso', data.actividades.enProceso],
                                ['completadas', data.actividades.completadas],
                            ]);
                        },
                        error: error =>{
                            console.log(error);
                        },
                    })
                break
            case 'general':
                break
            default:
                break
        }

    })

    function clickGraficoPie(data){
        console.log('click');
        $('#tabla').empty()
        let route = ''
        let bandera = true
        const year = Number($('#year').val())
        const area = Number($('#select_area').val())
        const select = $('#rango_inicial').val()
        switch ($('input:radio[name=rango]:checked').val())
        {
            case 'mensual':
                route = `-por-mes`
                bandera = false
                break;
        }
        switch(data.index){
            case 0:
                route= `get-actividades-sin-entregar${route}/${area}`
            break;
            case 1:
                route= `get-actividades-en-proceso${route}/${area}`
                break;
            case 2:
                route= `get-actividades-completadas${route}/${area}`
                break;
        }

        if(bandera){
            const inicio = moment(year).set({week: select, day: 0}).format('DD-M-YYYY')
            const fin = moment(year).set({week: select, day: 6}).format('DD-M-YYYY')
            route=`${route}/${inicio}/${fin}`
        }else{
            route= `${route}/${Number(moment().month(select).format('MM'))}`
        }
        route = `/dashboard/${user_id}/${route}/${year}`

        imprimirTablaConAjax(route)
    }
    function imprimirTablaConAjax(route){
        console.log(route)
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

    function resetearAreas(){
        selectArea.empty()
        //selectArea.attr('hidden',true)
        selectArea.empty()
        //radiosDeRangoDeFechas.attr('hidden',true)
        //selectRango.attr('hidden',true)
        selectRango.empty()
        //btnFiltrarBusquedas.attr('hidden',true)
    }
    function resetearCarreras(){
        //selectArea.attr('hidden',true)
        selectArea.empty()
        radiosDeRangoDeFechas.attr('hidden',true)
        //selectRango.attr('hidden',true)
        selectRango.empty()
        //btnFiltrarBusquedas.attr('hidden',true)
    }
    function resetearRadiosDeRangos(){
        //radiosDeRangoDeFechas.attr('hidden',true)
        //selectRango.attr('hidden',true)
        selectRango.empty()
        //btnFiltrarBusquedas.attr('hidden',true)
    }

    function resetearSelectRangoInicial(){
        //btnFiltrarBusquedas.attr('hidden',true)
    }

})

/*
    Calcular seguimiento
    porcentajes
    estilos panel
*/
