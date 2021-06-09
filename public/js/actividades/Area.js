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
                type: 'gauge',
                onclick: function (data, i) {
                    $('#tabla').empty()
                    $.ajax({
                        type: 'GET',
                        url: `/admin/get-actividades-totales/${$('#select_area').val()}`,
                        success: data=>{
                            const table = $('#tabla')
                            table.empty()
                            let tbody = ''
                            data.forEach(dato=>{
                                tbody+=`
                                    <tr>
                                        <td> ${dato.nombre} </td>
                                        <td> ${dato.asunto} </td>
                                        <td> ${dato.fecha_inicio} </td>
                                        <td> ${dato.fecha_fin} </td>
                                        <td> ${dato.importancia} </td>
                                        <td>
                                            <a href="/admin/seguimiento/${dato.idac}" class="btn btn-link">Ver Detalle</a>
                                        </td>
                                    </tr>
                                `
                            })
                            const template = `
                                <table id="table" class="display responsive" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Actividad</th>
                                            <th>Inicia</th>
                                            <th>Finaliza</th>
                                            <th>Importancia date</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${tbody}
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Actividad</th>
                                            <th>Inicia</th>
                                            <th>Finaliza</th>
                                            <th>Importancia date</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <script>
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
                                </script>
                            `
                            table.append(template)
                        },
                        error: error=>{
                            console.error(error);
                        }
                    })
                },
                /*
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
                type : 'pie',
                onclick: data=>{
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

                    console.log(route)

                    $.ajax({
                        type: 'GET',
                        url: `/admin/${route}/${year}`,
                        success: data=>{
                            const table = $('#tabla')
                            table.empty()
                            let tbody = ''
                            data.forEach(dato=>{
                                tbody+=`
                                    <tr>
                                        <td> ${dato.nombre} </td>
                                        <td> ${dato.asunto} </td>
                                        <td> ${dato.fecha_inicio} </td>
                                        <td> ${dato.fecha_fin} </td>
                                        <td> ${dato.importancia} </td>
                                        <td>
                                            <a href="/admin/seguimiento/${dato.idac}" class="btn btn-link">Ver Detalle</a>
                                        </td>
                                    </tr>
                                `
                            })
                            const template = `
                                <table id="table" class="display responsive" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Actividad</th>
                                            <th>Inicia</th>
                                            <th>Finaliza</th>
                                            <th>Importancia date</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${tbody}
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Actividad</th>
                                            <th>Inicia</th>
                                            <th>Finaliza</th>
                                            <th>Importancia date</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <script>
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
                                </script>
                            `
                            table.append(template)
                        },
                        error: error=>{
                            console.error(error);
                        }
                    })


                },
                /*onmouseover: function (d, i) { console.log("onmouseover", d, i); },
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
