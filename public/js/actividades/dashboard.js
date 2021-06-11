import Area from '/js/actividades/Area.js'

moment.locale('es', {
    months: 'Enero_Febrero_Marzo_Abril_Mayo_Junio_Julio_Agosto_Septiembre_Octubre_Noviembre_Diciembre'.split('_'),
    monthsShort: 'Enero._Feb._Mar_Abr._May_Jun_Jul._Ago_Sept._Oct._Nov._Dec.'.split('_'),
    weekdays: 'Domingo_Lunes_Martes_Miercoles_Jueves_Viernes_Sabado'.split('_'),
    weekdaysShort: 'Dom._Lun._Mar._Mier._Jue._Vier._Sab.'.split('_'),
    weekdaysMin: 'Do_Lu_Ma_Mi_Ju_Vi_Sa'.split('_')
  }
);

$('document').ready(()=>{

    const area = new Area()

    const selectTipoArea = $('#select_tipo_area')
    const selectArea = $('#select_area')
    const radiosDeRangoDeFechas = $('#radios_rango_de_fechas')
    const selectRango = $('#rango_inicial')
    const btnFiltrarBusquedas = $('#filtrar_busquedas')
    const inputYear = $('#year')
    selectTipoArea.change(()=>{

        if (!selectTipoArea.val()){
            resetearAreas()
            return
        }
        selectArea.removeAttr('hidden')
        area.procesarPeticionDeSelect(selectArea)

        $.ajax({
            type : 'GET',
            url : `/admin/getAreasPorTipoArea/${selectTipoArea.val()}`,
            success : response =>{
                selectArea.empty()
                selectArea.append('<option value="">-Direccion de carrera-</option>')
                response.area.forEach(area =>{
                    const template = `<option value="${area.idar}" > ${area.nombre}</option>`
                    selectArea.append(template)
                })
            },
            error: error =>{
            },
        })
    })


    selectArea.change(()=>{
        if (!selectArea.val()){
            radiosDeRangoDeFechas.attr('hidden',true)
            return
        }
        radiosDeRangoDeFechas.removeAttr('hidden')
    })

    area.generarHtmlDelGrafico('Prorcentaje total de actividades','gauge_chart')
    area.generarGreficoGauge()

    area.generarHtmlDelGrafico('Estado de las Actividades','pie_chart')
    area.generarGreficoPie()

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
        const direccionDeCarrera = selectArea.val()
        const rangoInicial = Number(selectRango.val())
        const year = inputYear.val()

        const rango = $('input:radio[name=rango]:checked').val()
        switch (rango){
            case 'mensual':
                    const mes = Number(moment().month(rangoInicial).format('MM'))
                    $.ajax({
                        type: 'GET',
                        url:  `/admin/get-actividades-ṕor-mes/${direccionDeCarrera}/${year}/${mes}`,
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
            case 'semanal':
                    const diaInicialDeLaSemana = moment(year).set({week: rangoInicial, day: 0}).format('DD-MM-YYYY')
                    const diaFinallDeLaSemana = moment(year).set({week: rangoInicial, day: 6}).format('DD-MM-YYYY')
                    $.ajax({
                        type: 'GET',
                        url:  `/admin/get-actividades-ṕor-rango-de-fechas/${direccionDeCarrera}/${diaInicialDeLaSemana}/${diaFinallDeLaSemana}`,
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

    function resetearAreas(){
        selectArea.empty()
        selectArea.attr('hidden',true)
        selectArea.empty()
        radiosDeRangoDeFechas.attr('hidden',true)
        selectRango.attr('hidden',true)
        selectRango.empty()
        btnFiltrarBusquedas.attr('hidden',true)
    }
    function resetearCarreras(){
        selectArea.attr('hidden',true)
        selectArea.empty()
        radiosDeRangoDeFechas.attr('hidden',true)
        selectRango.attr('hidden',true)
        selectRango.empty()
        btnFiltrarBusquedas.attr('hidden',true)
    }
    function resetearRadiosDeRangos(){
        radiosDeRangoDeFechas.attr('hidden',true)
        selectRango.attr('hidden',true)
        selectRango.empty()
        btnFiltrarBusquedas.attr('hidden',true)
    }

    function resetearSelectRangoInicial(){
        btnFiltrarBusquedas.attr('hidden',true)
    }

})

