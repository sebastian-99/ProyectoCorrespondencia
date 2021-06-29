<!DOCTYPE html> 
<html lang="es"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <meta http-equiv="X-UA-Compatible" content="ie=edge"> 
    <title>PDF de acuse recibido de actividades</title> 
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<style>
    .titulo p {
        font-family: Helvetica;
        font-size: 16pt;
        text-align: center;
        color: #0B821D;
    }
    .sub p{
        font-family: Helvetica;
        font-size: 14pt;
        text-align: center;
    }
    div p {
        font-family: Helvetica;
        font-size: 12pt;
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
        font-family: Helvetica;
        font-size: 8pt;
        /line-height: 35pt;/
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
                <img src="{{public_path('images/Gob.png')}}" height="45" align="left">
                <img src="{{public_path('images/logoUTVT.png')}}" height="40" alt="">
                <img src="{{public_path('images/Edomex.png')}}" height="40" alt="">
            </div>
        </div>
    </div>
    <br><br>
    <center><p style="font-family: Helvetica; font-size:10pt"><b>“2021. Año de la Consumación de la Independencia y la Grandeza de México".</b></p></center>
    <div class="titulo"><p style="font-family: Helvetica;"><b>REPORTE DE ACUSE DE RECIBIDO</b></p></div>
    <div class="sub"><p><b>DETALLE DE LA ACTIVIDAD</b></p></div>

    <table class="table table-bordered border-primary">
        <tr>
            <td width='50%'>
                <div>
                    <div class="col" style="font-family: Helvetica;">
                        <p>Folio: <b>{{$data[0]->comunicado}}</b></p>
                        <p>Asunto: <b>{{$data[0]->asunto}}</b></p>
                        <p>Detalle: <b>{{$data[0]->descripcion}}</b></p>
                    </div>
                </div>
            </td>
            <td width='50%'>
                <div>
                    <div>
                        <p>Fecha creaci&oacute;n: <b>{{$data[0]->fecha_creacion}}</b></p>
                        <p>Fecha seguimiento: <b>{{$data[0]->fecha_inicio}}</b></p>
                        <p>Fecha termino: <b>{{$data[0]->fecha_fin}}</b></p>
                    </div>
                </div>
            </td>
        </tr>
        
        
    </table>
    
    <table class="table table-bordered" style="border-color: #0B821D">
    
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

                for( $i=0; $i < count($insert); $i++)
                {
                    $contenido .= "<td scope='row' width='30%'> <div class='row'> <div class='col'>";

                    $contenido .= "<p>Nombre: <b>".$insert[$i]['nombre']."</b></p>";

                    $contenido .= "<p>Fecha de acuse: <b>".$insert[$i]['fecha_acuse']."</b></p>";

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

                $contenido .= "<p>Fecha de acuse: <b>".$out[$i]['fecha_acuse']."</b></p>";

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
        <div><img src="{{public_path('images/Abajo.JPG')}}" width="100%" ></div>
    </footer>
</body>
</html>

