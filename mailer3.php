<link rel="stylesheet" type="text/css" href="css/style.css">
<link href="css/bootstrap.min.css" rel="stylesheet">
<style>
.style1 {color: #FFFFFF; background-color: #000033}
.style2 {color: #000033}
.style3 {color: #000033; font-weight:bold; background-color: #CCCCCC}
.style4 {color: #000033; font-weight:bold; background-color:#B2D0E4}
</style>
<script>
	$(document).ready(function() { 
      $("#d_export_excel").click(function(event)  {
        $("#datos_a_enviar").val( $("<div>").append( $("#detalle_Exporta_Excel").eq(0).clone()).html());
        $("#FormularioExportacion").submit();
      });
    });
</script>
<?php
require ('phpmailer/PHPMailerAutoload.php');
$mail = new PHPMailer;

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'mail.logexsrl.com.ar';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'contacto@logexsrl.com.ar';                 // SMTP username
$mail->Password = 'C0ntacto';                           // SMTP password
$mail->SMTPSecure = '';                            // Enable encryption, 'ssl' also accepted
$mail->Port = 587; // or 587
$mail->isHTML(true);  

include ('conexion.php');
conectar();

$fecha=date('Y-m-d');

$nuevafecha=strtotime('+1 day',strtotime($fecha));
$titulo    = 'Rechazos Logex Rutas '.date('Y-m-d',$nuevafecha);

$sucursal=3;

$sqlRutas="SELECT ruta, d_ruta, c_perso, d_perso, C_SUPER, D_SUPER FROM RUTAS_DIA";

$resRutas=mysql_query($sqlRutas) or die(exit("Error en odbc_exec"));

$rutas="(";
$i=0;
$vecRutas=array();
while ($rowRutas=mysql_fetch_array($resRutas))
{
	$vecRutas[$i]['ruta']=$rowRutas['ruta'];
	$vecRutas[$i]['d_ruta']=$rowRutas['d_ruta'];
	$vecRutas[$i]['c_perso']=$rowRutas['c_perso'];
	$vecRutas[$i]['d_perso']=$rowRutas['d_perso'];
	$vecRutas[$i]['c_super']=$rowRutas['C_SUPER'];	
	$vecRutas[$i]['d_super']=$rowRutas['D_SUPER'];	
	$vecRutas[$i]['cant_rec']=0;
	$rutas=$rutas.$vecRutas[$i]['ruta'].",";
	$i++;
}

$rutas=substr($rutas, 0,-1);
$rutas=$rutas.")";

$fecha_antes=strtotime('-4 day',strtotime($fecha));
$fecha_antes=date('Y/m/d', $fecha_antes);

$sqlRechazos="SELECT * FROM RECHAZOS WHERE RUTA IN ".$rutas;

$resRechazos=mysql_query($sqlRechazos);
$vecRechazos=array();
$i=0;
while ($rowRechazos=mysql_fetch_array($resRechazos))
{
	$vecRechazos[$i]['idcliente']=$rowRechazos['idcliente'];
	$vecRechazos[$i]['nomcli']=$rowRechazos['nomcli'];
	$vecRechazos[$i]['fechafac']=$rowRechazos['fechafac'];
	$vecRechazos[$i]['ruta']=$rowRechazos['ruta'];
	$vecRechazos[$i]['dsrechazo']=$rowRechazos['dsrechazo'];
	$vecRechazos[$i]['c_perso']=$rowRechazos['c_perso'];
	$i++;
}

for ($i=0;$i<count($vecRutas);$i++)
{
	for ($j=0;$j<count($vecRechazos);$j++)
	{
		if (($vecRechazos[$j]['ruta']==$vecRutas[$i]['ruta'])&&($vecRechazos[$j]['c_perso']==$vecRutas[$i]['c_perso']))
		{
			$vecRutas[$i]['cant_rec']=$vecRutas[$i]['cant_rec']+1;
		}
	}
}

$vecSupervisores=array();
$vecSupervisores[0]['c_super']=3;
$vecSupervisores[0]['correo']='julio.almada@logexsrl.com.ar';
$vecSupervisores[0]['idsucur']=1;
$vecSupervisores[1]['c_super']=4;
$vecSupervisores[1]['correo']='gonzalo.roldan@logexsrl.com.ar';
$vecSupervisores[1]['idsucur']=1;
$vecSupervisores[2]['c_super']=5;
$vecSupervisores[2]['correo']='edgar@logexsrl.com.ar';
$vecSupervisores[2]['idsucur']=1;
$vecSupervisores[3]['c_super']=6;
$vecSupervisores[3]['correo']='marcos.holot@logexsrl.com.ar';
$vecSupervisores[3]['idsucur']=1;
$vecSupervisores[4]['c_super']=7;
$vecSupervisores[4]['correo']='nicolas@logexsrl.com.ar';
$vecSupervisores[4]['idsucur']=1;
$vecSupervisores[5]['c_super']=4;
$vecSupervisores[5]['correo']='leonardo@logexsrl.com.ar';
$vecSupervisores[5]['idsucur']=2;
$vecSupervisores[6]['c_super']=2;
$vecSupervisores[6]['correo']='braian@logexsrl.com.ar';
$vecSupervisores[6]['idsucur']=3;

desconectar();

$cabecera_tabla = '
<table style="width:100%; font-family: "ARIAL", serif;">
		<tr style="color: #FFFFFF; background-color: #000033;">
			<th>COD.RUTA</th>
			<th>RUTA</th>
			<th></th>
			<th></th>
			<th></th>			
			<th></th>
		</tr>
';
$cuerpo_tabla='';
for ($i=0;$i<count($vecRutas);$i++)
{
	if ($vecRutas[$i]['cant_rec']>0)
	{
		$cuerpo_tabla=$cuerpo_tabla.'<tr style="color: #000033; font-weight:bold; background-color: #CCCCCC">
			<td>'.$vecRutas[$i]['ruta'].'</td>
			<td>'.$vecRutas[$i]['d_ruta'].'</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr style="color: #000033; font-weight:bold; background-color:#B2D0E4">
			<td>FECHA COMPROBANTE</td>
			<td>COD.CLIENTE</td>
			<td>CLIENTE</td>
			<td>MOTIVO RECHAZO</td>
			<td>VENDEDOR</td>		
			<td>SUPERVISOR</td>		
		</tr>';
		for ($j=0;$j<count($vecRechazos);$j++)
		{
			if (($vecRechazos[$j]['ruta']==$vecRutas[$i]['ruta'])&&($vecRechazos[$j]['c_perso']==$vecRutas[$i]['c_perso']))
			{
				$cuerpo_tabla=$cuerpo_tabla.'<tr style="color: #000033">
					<td>'.$vecRechazos[$j]['fechafac'].'</td>
					<td>'.$vecRechazos[$j]['idcliente'].'</td>
					<td>'.$vecRechazos[$j]['nomcli'].'</td>
					<td>'.$vecRechazos[$j]['dsrechazo'].'</td>
					<td>'.$vecRutas[$i]['d_perso'].'</td>
					<td>'.$vecRutas[$i]['d_super'].'</td>
				</tr>';
			}
		}
	}
}
$cuerpo_tabla=$cuerpo_tabla.'</table>';
$cabecera_tabla = '
<table style="width:100%; font-family: "ARIAL", serif;">
		<tr style="color: #FFFFFF; background-color: #000033;">
			<th>COD.RUTA</th>
			<th>RUTA</th>
			<th></th>
			<th></th>
			<th></th>			
			<th></th>
		</tr>
';

for ($i=0;$i<count($vecSupervisores);$i++)
{
	$cuerpo_tabla='';
	$correo='';
	for ($j=0;$j<count($vecRutas);$j++)
	{
		if (($vecRutas[$j]['cant_rec']>0)&&($vecRutas[$j]['c_super']==$vecSupervisores[$i]['c_super'])&&($vecSupervisores[$i]['idsucur']==$sucursal))
		{
			$cuerpo_tabla=$cuerpo_tabla.'<tr style="color: #000033; font-weight:bold; background-color: #CCCCCC">
				<td>'.$vecRutas[$j]['ruta'].'</td>
				<td>'.$vecRutas[$j]['d_ruta'].'</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr style="color: #000033; font-weight:bold; background-color:#B2D0E4">
				<td>FECHA COMPROBANTE</td>
				<td>COD.CLIENTE</td>
				<td>CLIENTE</td>
				<td>MOTIVO RECHAZO</td>
				<td>VENDEDOR</td>		
				<td>SUPERVISOR</td>		
			</tr>';
			for ($k=0;$k<count($vecRechazos);$k++)
			{
				if (($vecRechazos[$k]['ruta']==$vecRutas[$j]['ruta'])&&($vecRechazos[$k]['c_perso']==$vecRutas[$j]['c_perso']))
				{
					$cuerpo_tabla=$cuerpo_tabla.'<tr style="color: #000033">
						<td>'.$vecRechazos[$k]['fechafac'].'</td>
						<td>'.$vecRechazos[$k]['idcliente'].'</td>
						<td>'.$vecRechazos[$k]['nomcli'].'</td>
						<td>'.$vecRechazos[$k]['dsrechazo'].'</td>
						<td>'.$vecRutas[$j]['d_perso'].'</td>
						<td>'.$vecRutas[$j]['d_super'].'</td>
					</tr>';
				}
			}
		$correo=$vecSupervisores[$i]['correo'];
		$sucursal_correo=$vecSupervisores[$i]['idsucur'];
		$supervisor=$vecSupervisores[$i]['c_super'];
		}
	}

	$cuerpo_tabla=$cuerpo_tabla.'</table>';
	if (($sucursal==$sucursal_correo)&&($correo!=''))
	{  
		$mail->Subject = 'Rechazos';
		$mail->From = 'contacto@logexsrl.com.ar';
		$mail->FromName = 'Rechazos Logex';
		$mail->addAddress($correo, $supervisor);     // Add a recipient
		$mail->addAddress('martin@logexsrl.com.ar');		
		$mail->addAddress('carlos@logexsrl.com.ar');
		$mail->addAddress('marcos.holot@logexsrl.com.ar');		
		$mail->Body    = $cabecera_tabla.$cuerpo_tabla;
		if(!$mail->send()) {
		    echo 'Message could not be sent.';
		    echo 'Mailer Error: ' . $mail->ErrorInfo;
		} else {
		    echo 'Message has been sent';
		}		
	}
}

echo "<script> window.close() </script>";   
?>
