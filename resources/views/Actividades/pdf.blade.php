<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PDF de actividades seguimientos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    
</head>
<body>
    <center><h1 class="text-primary">Reporte de responsables de actividades</h1></center>
    <table class="table table-bordered border-primary">
        
        
        @php
         $index = 0;   
        @endphp
        @foreach ( $data as $key => $valor)
        
        @if($index != 2)
        <tr scope="row">
            
            
            <td>
                <p>{{$index}}</p>
                <p>Firma digital por: {{$valor->nombre}}</p>
                <p>Fecha: {{$valor->fecha_acuse}}</p>
                <p>Razón: FIRMA DE RECIBIDO</p>
                <p>&Aacute;rea: {{$valor->area}}</p>
            </td>
        </tr>
            
            @php
                $index++;
                    
            @endphp
            
            @else
            @php
                
            $index = 0;
                
                
            @endphp
            @endif
        @endforeach
        
        
                 
    </table>
    
</body>
</html>
