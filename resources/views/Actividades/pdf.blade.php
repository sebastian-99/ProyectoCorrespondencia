<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PDF de actividades seguimientos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    
</head>
<style>
    *{
        padding: 0;
        font-family: arial;
    }
    h1 {
        font-size: 25px;
        text-align: center;
        color: #0B821D;
    }
    h2 {
        font-size: 22px;
        text-align: center;
    }
    p {
        text-align: center;
    }
</style>


<body>
    <div class="container">
        <div class="row">
            <div align="left"><img src="{{asset('images/Edomex.png')}}" height="60" alt=""></div>
            <div align="right"><img src="{{asset('images/logoUTVT.png')}}" height="60" alt=""></div>
        </div>
    </div>
    <h1>REPORTE DE ACUSE DE RECIBIDO</h1><br>
    <h2>DETALLE DE LA ACTIVIDAD</h2>

    <table class="table table-bordered border-primary">
        <tr>
            <td scope="row" width='50%'>
                <div class="row">
                    <div class="col">
                        <p>Folio: <b>{{$data[0]->comunicado}}</b></p>
                        <p>Asunto: <b>{{$data[0]->asunto}}</b></p>
                        <p>Detalle: <b>{{$data[0]->descripcion}}</b></p>
                    </div>
                </div>
            </td>
            <td scope="row" width='50%'>
                <div class="row">
                    <div class="col">
                        <p>Fecha creaci&oacute;n: <b>{{$data[0]->fecha_creacion}}</b></p>
                        <p>Fecha seguimiento: <b>{{$data[0]->fecha_inicio}}</b></p>
                        <p>Fecha termino: <b>{{$data[0]->fecha_fin}}</b></p>
                    </div>
                </div>
            </td>
        </tr>
        
        
    </table>
    
    <table class="table table-bordered border-primary" style="border-color: #0B821D">
    
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
                )
            );

            if( count($insert) == 3 )
            {
                $contenido = "<tr>";

                for( $i=0; $i < count($insert); $i++)
                {
                    $contenido .= "<td scope='row' width='30%'> <div class='row'> <div class='col'>";

                    $contenido .= "<p>Nombre: ".$insert[$i]['nombre']."</p>";

                    $contenido .= "<p>Fecha: ".$insert[$i]['fecha_acuse']."</p>";

                    //$contenido .= "<p>Razón: FIRMA DE RECIBIDO</p>";

                    $contenido .= "<p>Área: ".$insert[$i]['area']."</p>";

                    $contenido .= "<p>Firma: ".$insert[$i]['firma']."</p>";

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

                $contenido .= "<p>Nombre: ".$out[$i]['nombre']."</p>";

                $contenido .= "<p>Fecha: ".$out[$i]['fecha_acuse']."</p>";

                //$contenido .= "<p>Razón: FIRMA DE RECIBIDO</p>";

                $contenido .= "<p>Área: ".$out[$i]['area']."</p>";

                $contenido .= "<p>Firma: ".$out[$i]['firma']."</p>";

                $contenido .= "</div> </div> </td>";

            }

            $contenido .= "</tr>";

            echo $contenido;
        }

    @endphp
                 
    </table>
    
</body>
</html>
