<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8">
	<title>Correo</title>
</head>

<body style="background-color: white ">

	<table style="max-width: 600px; padding: 10px; margin:0 auto; border-collapse: collapse;">
		<tr>
			<td style="padding: 0">
				<img style="padding: 0; display: block" src="https://correspondencia.utvtol.org.mx/public/images/utvtMailHeader.png" width="100%">
			</td>
		</tr>

		<tr>
			<td style="background-color: #ecf0f1">
				<div style="color: #34495e; margin: 4% 10% 2%; text-align: justify;font-family: sans-serif">
					<h1 style="color: #e67e22; margin: 0 0 9px">Hola {{ $msg->nombre }}</h1>
					<p style="margin: 2px; font-size: 18px">Aún no ha completado la actividad: <strong>"{{ $msg->asunto }}" </strong></p>
					<p style="margin: 2px; font-size: 18px">Asignada por
					@if ($msg->nombre == $msg->creador)
						usted.
					@else
						<strong>: "{{ $msg->creador }}" </strong>
                    @endif 
					</p>
					<p style="margin: 2px; font-size: 18px">Finaliza el {{ $msg->fecha_fin }}, esperamos que la pueda atender y completar lo antes posible.</p>
					<h3>Un cordial saludo.</h3>
					<div style="width: 100%;margin:20px 0; display: inline-block;text-align: center">

					</div>
					<div style="width: 100%; text-align: center">
						<a style="text-decoration: none; border-radius: 5px; padding: 11px 23px; color: white; background-color: #39BB1C" href="https://correspondencia.utvtol.org.mx/login">Ir a la página</a>
					</div>
					<p style="color: #b3b3b3; font-size: 15px; text-align: center;margin: 30px 0 0">Correspondencia UTVT</p>
					<img style="padding: 0; display: block" src="https://correspondencia.utvtol.org.mx/public/images/BarraColores.png" width="100%">

				</div>
			</td>
		</tr>
	</table>
</body>

</html>