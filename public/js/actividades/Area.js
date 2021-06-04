export default class Area{
    constructor(){
        this.data = {}
        this.actividad = ''
        this.elementoDOM = ''
        this.gaugeChart = {}
        this.pieChart = {}
        this.dashboardPanel = $('#dashboard_panel')
    }

    setElementoDom(elementoDOM){
        this.elementoDOM = elementoDOM
    }

    generarHtmlDelGrafico(actividad,id){
        const template = `
            <div class="form-group col-md-4">
                <label class="">${actividad}</label>
                <div id="${id}"></div>
            </div>
        `
        this.dashboardPanel.append(template)
    }

    generarGreficoGauge(data =[['dato',0]]){
        this.gaugeChart = c3.generate({
            bindto: '#gauge_chart',
            data: {
                columns: data,
                type: 'gauge',/*
                onclick: function (d, i) { console.log("onclick", d, i); },
                onmouseover: function (d, i) { console.log("onmouseover", d, i); },
                onmouseout: function (d, i) { console.log("onmouseout", d, i); }*/
            },
            gauge: {
            },
            color: {
                pattern: ['#FF0000'],
            },
            size: {
                height: 180
            },
        })
    }

    generarGreficoPie(){
        this.pieChart  = c3.generate({
            bindto: '#pie_chart',
            data: {
                columns: [
                    ['sin entregar', 0],
                    ['en proceso', 0],
                    ['completadas', 0],
                ],
                type : 'pie',/*
                onclick: function (d, i) { console.log("onclick", d, i); },
                onmouseover: function (d, i) { console.log("onmouseover", d, i); },
                onmouseout: function (d, i) { console.log("onmouseout", d, i); }*/
            }
        })
    }

    setGraficoPie(columns){
        this.pieChart.load({
            columns
        })
    }

    procesarPeticionDeSelect(select){
        select.empty()
        select.append('<option>procesando informacion .....</option>')
        return
    }

    rangoMensual(selectRangoInicial,selectRangoFinal){

    }

    rangoSemanal(){

    }

    rangoGeneral(){

    }


}
