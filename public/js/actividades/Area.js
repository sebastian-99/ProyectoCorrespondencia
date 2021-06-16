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
            data: data,
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

    generarGreficoPie(data = this.data){
        this.pieChart  = c3.generate({
            bindto: '#pie_chart',
            data: data
        })
    }

    setGraficoPie(columns){
        this.pieChart.load({
            columns : columns,
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
        $(this.rangoGeneral('lol'))
    }

    rangoGeneral(rango){
        console.log('funcion llamada')
    }


    imprimirDatosEnTabla(thead = null,tbody = null, table = $('#tabla'),script = '' ){
        table.empty()
        const template = `
            <table id="table" class="display responsive" style="width:100%">
                <thead>
                    ${thead}
                </thead>
                <tbody>
                    ${tbody}
                </tbody>
                <tfoot>
                    ${thead}
                </tfoot>
            </table>
            <script>
                ${script}
            </script>
        `
        //${script}
        table.append(template)


    }


}
