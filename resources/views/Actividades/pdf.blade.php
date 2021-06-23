<!DOCTYPE html> 
<html lang="es"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <meta http-equiv="X-UA-Compatible" content="ie=edge"> 
    <title>PDF de acuse recibido de actividades</title> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<style>
    h1 {
        font-family: Arial;
        font-size: 20px;
        text-align: center;
        color: #0B821D;
    }
    h2 {
        font-family: Arial;
        font-size: 17px;
        text-align: center;
    }
    div p {
        font-family: Arial;
        font-size: 16px;
        text-align: left;
        line-height: 1.5em;
    }

    footer {
        position: fixed;
        bottom: 0cm;
        height: 2cm;
        margin-left: 0px;
        margin-right: 0px;
        width: 100%;
        background-color: #696969;
        color: white;
        font-family: Arial;
        font-size: 8pt;
        /*line-height: 35pt;*/
    }
    div img{
        left: 0px;
        right: 0px;
        margin-left: 0px;
    }
    
</style>
<body>
    <div>
        <div align="right">
            <div>
                <img src="{{public_path('images/logoGob.png')}}" height="35" align="left">
                <!--<img src="{{public_path('images/logoGob.png')}}" height="35" align="left">-->
                <img src="{{public_path('images/logoUTVT.png')}}" height="35" alt="">
                <img src="{{public_path('images/Edomex.png')}}" height="35" alt="">
            </div>
        </div>
    </div>
    <br><br>
    <center><p>“2021. Año de la Consumación de la Independencia y la Grandeza de México".</p></center>
    <h1 style="font-family: Arial;">REPORTE DE ACUSE DE RECIBIDO</h1><br>
    <p><h2>DETALLE DE LA ACTIVIDAD</h2></p><br><br>

    <table class="table table-bordered border-primary">
        <tr>
            <td width='50%'>
                <div>
                    <div class="col" style="font-family: Arial;">
                        <p>Folio: <b>{{$data[0]->comunicado}}</b></p>
                        <p>Asunto: <b>{{$data[0]->asunto}}</b></p>
                        <p>Detalle: <b>{{$data[0]->descripcion}}</b></p>
                    </div>
                </div>
            </td>
            <td width='50%'>
                <div>
                    <div style="font-family: Arial;">
                        <p>Fecha creaci&oacute;n: <b>{{$data[0]->fecha_creacion}}</b></p>
                        <p>Fecha seguimiento: <b>{{$data[0]->fecha_inicio}}</b></p>
                        <p>Fecha termino: <b>{{$data[0]->fecha_fin}}</b></p>
                    </div>
                </div>
            </td>
        </tr>
        
        
    </table>
    
    <table class="table table-bordered border-primary">
    
    @php

        $out = $insert = array();

        $final = count($data) % 3;

        $pos = count($data) - $final;

        foreach ( $data as $key => $valor)
        {
            array_push($insert, 
                array( 
                    'nombre' => $valor->nombre, 
                    'fecha_acuse' =>  $valor->fecha_acuse, 
                    'area' => $valor->area,
                    'firma' => $valor->firma,
                    'firma2' => $valor->firma2,
                )
            );

            if( count($insert) == 3 )
            {
                $contenido = "<tr>";

                for( $i=0; $i < count($insert); $i++){
                
                    $contenido .= "<td scope='row' width='30%'> <div class='row'> <div class='col'>";

                    $contenido .= "<p>Nombre: <b>".$insert[$i]['nombre']."</b></p>";

                    $contenido .= "<p>Fecha: <b>".$insert[$i]['fecha_acuse']."</b></p>";

                    //$contenido .= "<p>Razón: FIRMA DE RECIBIDO</p>";

                    $contenido .= "<p>Área: <b>".$insert[$i]['area']."</b></p>";

                    $contenido .= "<p>Firma: <b>".$insert[$i]['firma']."</b></p>";
                    $contenido .= "<p><b>".$insert[$i]['firma2']."</b><p>";

                    $contenido .= "</div> </div> </td>";

                }

                $contenido .= "</tr>";

                echo $contenido;

                $insert = array();
            }

            if( $key >= $pos )
            {
                array_push($out, 
                    array( 
                        'nombre' => $valor->nombre, 
                        'fecha_acuse' =>  $valor->fecha_acuse, 
                        'area' => $valor->area,
                        'firma' => $valor->firma,
                        'firma2' => $valor->firma2,
                    )
                );
            }
        }

        if( count($out) != 0 )
        {
            $contenido = "<tr>";

            for( $i=0; $i < count($out); $i++)
            {
                $contenido .= "<td scope='row' width='30%'> <div class='row'> <div class='col'>";

                $contenido .= "<p>Nombre: <b>".$out[$i]['nombre']."</b></p>";

                $contenido .= "<p>Fecha: <b>".$out[$i]['fecha_acuse']."</b></p>";

                //$contenido .= "<p>Razón: FIRMA DE RECIBIDO</p>";

                $contenido .= "<p>Área: <b>".$out[$i]['area']."</b></p>";

                $contenido .= "<p>Firma: <b>".$out[$i]['firma']."</b></p>";
                $contenido .= "<p><b>".$insert[$i]['firma2']."</b><p>";

                $contenido .= "</div> </div> </td>";

            }

            $contenido .= "</tr>";

            echo $contenido;
        }

    @endphp
                 
    </table>    
    
    

    <footer>
        <div><img src="{{public_path('images/M-Edomex.png')}}" width="170" height="170" align="left"></div>
        <br><br>
        <center><p>Carretera del Departamento del D.F. km 7.5, Santa María Atarasquillo, C.P. 52044, Lerma, Estado de México. <br>Tels.: (728) 285 95 52, 285 99 69, 282 22 47. utvtol.edu.mx</p></center>
    </footer>
</body>
</html>
