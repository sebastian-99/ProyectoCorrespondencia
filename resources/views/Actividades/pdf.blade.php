<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PDF de actividades seguimientos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    
</head>
<body>
    <center><h1 class="text-primary">REPORTE DE ACUSE DE RECIBIDO</h1></center>
    <center><h2 class="text-primary">DETALLE DE LA ACTIVIDAD</h2></center>

    <table class="table table-bordered border-primary">
        <tr>
            <td scope="row" width='50%'>
                <div class="row">
                    <div class="col">
                        <p>Folio: {{$data[0]->comunicado}}</p>
                        <p>Asunto: {{$data[0]->asunto}}</p>
                        <p>Detalle: {{$data[0]->descripcion}}</p>
                    </div>
                </div>
            </td>
            <td scope="row" width='50%'>
                <div class="row">
                    <div class="col">
                        <p>Fecha creaci&oacute;n: {{$data[0]->fecha_creacion}}</p>
                        <p>Fecha seguimiento: {{$data[0]->fecha_inicio}}</p>
                        <p>Fecha termino: {{$data[0]->fecha_fin}}</p>
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
                )
            );

            if( count($insert) == 3 )
            {
                $contenido = "<tr>";

                for( $i=0; $i < count($insert); $i++)
                {
                    $contenido .= "<td scope='row' width='30%'> <div class='row'> <div class='col'>";

                    $contenido .= "<p>Firma digital por: ".$insert[$i]['nombre']."</p>";

                    $contenido .= "<p>Fecha: ".$insert[$i]['fecha_acuse']."</p>";

                    $contenido .= "<p>Razón: FIRMA DE RECIBIDO</p>";

                    $contenido .= "<p>Área: ".$insert[$i]['area']."</p>";

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

                $contenido .= "<p>Firma digital por: ".$out[$i]['nombre']."</p>";

                $contenido .= "<p>Fecha: ".$out[$i]['fecha_acuse']."</p>";

                $contenido .= "<p>Razón: FIRMA DE RECIBIDO</p>";

                $contenido .= "<p>Área: ".$out[$i]['area']."</p>";

                $contenido .= "</div> </div> </td>";

            }

            $contenido .= "</tr>";

            echo $contenido;
        }

    @endphp
                 
    </table>
    
</body>
</html>
