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


$sqlTotal="SELECT idcliente, nomcli, SUM( total ) as total FROM cuentas_corrientes GROUP BY idcliente, nomcli";
$resTotal=mysql_query($sqlTotal);
$vecTotal=array();
$i=0;
while ($rowTotal=mysql_fetch_array($resTotal))
{
	$vecTotal[$i]['idcliente']=$rowTotal['idcliente'];
	$vecTotal[$i]['nomcli']=$rowTotal['nomcli'];
	$vecTotal[$i]['total']=$rowTotal['total'];
	$i++;	
}


$sqlCtaCte="select * from cuentas_corrientes order by idsucur, c_super, idcliente";

$resCtaCte=mysql_query($sqlCtaCte);
$vecCtaCte=array();
$i=0;
while ($rowCtaCte=mysql_fetch_array($resCtaCte))
{
	$vecCtaCte[$i]['idcliente']=$rowCtaCte['idcliente'];
	$vecCtaCte[$i]['nomcli']=$rowCtaCte['nomcli'];
	$vecCtaCte[$i]['fechafac']=$rowCtaCte['fecentre'];
	$vecCtaCte[$i]['fecha_vencimiento']=$rowCtaCte['fecha_vencimiento'];
	$vecCtaCte[$i]['dias_venc']=$rowCtaCte['dias_venc']*-1;	
	$vecCtaCte[$i]['factura']="000".$rowCtaCte['serie']."-".$rowCtaCte['nrodoc'];
	$vecCtaCte[$i]['idsucur']=$rowCtaCte['idsucur'];
	$vecCtaCte[$i]['c_vendedor']=$rowCtaCte['c_vendedor'];
	$vecCtaCte[$i]['d_vendedor']=$rowCtaCte['d_vendedor'];
	$vecCtaCte[$i]['c_super']=$rowCtaCte['c_super'];
	$vecCtaCte[$i]['d_super']=$rowCtaCte['d_super'];	
	$vecCtaCte[$i]['total']=$rowCtaCte['total'];	
	$i++;
}


$vecSupervisores=array();
$vecSupervisores[0]['c_super']=3;
$vecSupervisores[0]['d_super']='JULIO ALMADA';
$vecSupervisores[0]['correo']='julio.almada@logexsrl.com.ar';
$vecSupervisores[0]['cuerpo']='';
$vecSupervisores[0]['idsucur']=1;
$vecSupervisores[1]['c_super']=4;
$vecSupervisores[1]['d_super']='GONZALO ROLDAN';
$vecSupervisores[1]['correo']='gonzalo.roldan@logexsrl.com.ar';
$vecSupervisores[1]['cuerpo']='';
$vecSupervisores[1]['idsucur']=1;
$vecSupervisores[2]['c_super']=5;
$vecSupervisores[2]['d_super']='MARCOS HOLOT';
$vecSupervisores[2]['correo']='marcos.holot@logexsrl.com.ar';
$vecSupervisores[2]['cuerpo']='';
$vecSupervisores[2]['idsucur']=1;
$vecSupervisores[3]['c_super']=6;
$vecSupervisores[3]['d_super']='EDGAR VERA';
$vecSupervisores[3]['correo']='edgar@logexsrl.com.ar';
$vecSupervisores[3]['cuerpo']='';
$vecSupervisores[3]['idsucur']=1;
$vecSupervisores[4]['c_super']=7;
$vecSupervisores[4]['d_super']='NICOLAS ROJAS';
$vecSupervisores[4]['correo']='nicolas@logexsrl.com.ar';
$vecSupervisores[4]['cuerpo']='';
$vecSupervisores[4]['idsucur']=1;
$vecSupervisores[5]['c_super']=4;
$vecSupervisores[5]['d_super']='LEONARDO GODOY';
$vecSupervisores[5]['correo']='leonardo@logexsrl.com.ar';
$vecSupervisores[5]['cuerpo']='';
$vecSupervisores[5]['idsucur']=2;
$vecSupervisores[6]['c_super']=2;
$vecSupervisores[6]['d_super']='BRAIAN SOTELO';
$vecSupervisores[6]['correo']='braian@logexsrl.com.ar';
$vecSupervisores[6]['cuerpo']='';
$vecSupervisores[6]['idsucur']=3;
$vecSupervisores[7]['c_super']=3;
$vecSupervisores[7]['d_super']='JULIO ALMADA';
$vecSupervisores[7]['correo']='julio.almada@logexsrl.com.ar';
$vecSupervisores[7]['cuerpo']='';
$vecSupervisores[7]['idsucur']=4;
$vecSupervisores[8]['c_super']=4;
$vecSupervisores[8]['d_super']='BRAIAN SOTELO';
$vecSupervisores[8]['correo']='braian@logexsrl.com.ar';
$vecSupervisores[8]['cuerpo']='';
$vecSupervisores[8]['idsucur']=4;
$vecSupervisores[9]['c_super']=5;
$vecSupervisores[9]['d_super']='ESPECIALES';
$vecSupervisores[9]['correo']='marcos.holot@logexsrl.com.ar';
$vecSupervisores[9]['cuerpo']='';
$vecSupervisores[9]['idsucur']=4;

desconectar();

$cabecera_tabla = '
<table style="width:100%; font-family: "ARIAL", serif;">
		<tr style="color: #FFFFFF; background-color: #000033;">
			<th>SUPERVISOR</th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>			
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
';
$cuerpo_tabla='';
for ($i=0;$i<count($vecSupervisores);$i++)
{

		$cuerpo_tabla=$cuerpo_tabla.'<tr style="color: #000033; font-weight:bold; background-color: #CCCCCC">
			<td>'.$vecSupervisores[$i]['d_super'].'</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>	
		<tr style="color: #000033; font-weight:bold; background-color:#B2D0E4">
			<td>COD.CLIENTE</td>
			<td>CLIENTE</td>
			<td>FECHA ENTREGA</td>
			<td>FECHA VENCIMIENTO</td>
			<td>DIAS VENCIDA</td>		
			<td>FACTURA</td>		
			<td>COD.SUCUR</td>			
			<td>COD.VENDEDOR</td>
			<td>VENDEDOR</td>
			<td>TOTAL</td>
		</tr>';
		for ($j=0;$j<count($vecCtaCte);$j++)
		{
			if (($vecCtaCte[$j]['c_super']==$vecSupervisores[$i]['c_super']))
			{
				$cuerpo_tabla=$cuerpo_tabla.'<tr style="color: #000033">
					<td>'.$vecCtaCte[$j]['idcliente'].'</td>
					<td>'.$vecCtaCte[$j]['nomcli'].'</td>
					<td>'.$vecCtaCte[$j]['fechafac'].'</td>
					<td>'.$vecCtaCte[$j]['fecha_vencimiento'].'</td>
					<td>'.$vecCtaCte[$j]['dias_venc'].'</td>
					<td>'.$vecCtaCte[$j]['factura'].'</td>
					<td>'.$vecCtaCte[$j]['idsucur'].'</td>
					<td>'.$vecCtaCte[$j]['c_vendedor'].'</td>
					<td>'.$vecCtaCte[$j]['d_vendedor'].'</td>
					<td>'.$vecCtaCte[$j]['total'].'</td>
				</tr>';
			}
		}

}
$cuerpo_tabla=$cuerpo_tabla.'</table>';

$cabecera_tabla = '
<table style="width:100%; font-family: "ARIAL", serif;">
		<tr style="color: #FFFFFF; background-color: #000033;">
			<th>SUPERVISOR</th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>			
			<th></th>
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
	$cuerpo_tabla=$cuerpo_tabla.'<tr style="color: #000033; font-weight:bold; background-color: #CCCCCC">
			<td>'.$vecSupervisores[$i]['d_super'].'</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>	
		<tr style="color: #000033; font-weight:bold; background-color:#B2D0E4">
			<td>COD.CLIENTE</td>
			<td>CLIENTE</td>
			<td>FECHA ENTREGA</td>
			<td>FECHA VENCIMIENTO</td>
			<td>DIAS VENCIDA</td>		
			<td>FACTURA</td>		
			<td>COD.SUCUR</td>			
			<td>COD.VENDEDOR</td>
			<td>VENDEDOR</td>
			<td>TOTAL</td>
		</tr>';
		for ($j=0;$j<count($vecCtaCte);$j++)
		{
			if (($vecCtaCte[$j]['c_super']==$vecSupervisores[$i]['c_super'])&&($vecCtaCte[$j]['idsucur']==$vecSupervisores[$i]['idsucur']))
			{
				$cuerpo_tabla=$cuerpo_tabla.'<tr style="color: #000033">
					<td>'.$vecCtaCte[$j]['idcliente'].'</td>
					<td>'.$vecCtaCte[$j]['nomcli'].'</td>
					<td>'.$vecCtaCte[$j]['fechafac'].'</td>
					<td>'.$vecCtaCte[$j]['fecha_vencimiento'].'</td>
					<td>'.$vecCtaCte[$j]['dias_venc'].'</td>
					<td>'.$vecCtaCte[$j]['factura'].'</td>
					<td>'.$vecCtaCte[$j]['idsucur'].'</td>
					<td>'.$vecCtaCte[$j]['c_vendedor'].'</td>
					<td>'.$vecCtaCte[$j]['d_vendedor'].'</td>
					<td align="center"> $ '.$vecCtaCte[$j]['total'].'</td>
				</tr>';
			}
		}

	$cuerpo_tabla=$cuerpo_tabla.'</table>';
	$vecSupervisores[$i]['cuerpo']=$cabecera_tabla.$cuerpo_tabla;
		
}

for ($i=0;$i<count($vecSupervisores);$i++)
{
	$mail->Subject = 'Cuentas Corrientes';
	$mail->From = 'contacto@logexsrl.com.ar';
	$mail->FromName = 'Cuentas Corrientes Vencidas';
	$mail->addAddress($vecSupervisores[$i]['correo'], $vecSupervisores[$i]['d_super']);     // Add a recipient
	$mail->addAddress('martin@logexsrl.com.ar');		
	$mail->addAddress('carlos@logexsrl.com.ar');
	$mail->addAddress('francisco@logexsrl.com.ar');
	$mail->Body = $vecSupervisores[$i]['cuerpo'];
	if(!$mail->send()) {
	    echo 'Message could not be sent.';
	    echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
	    echo 'Message has been sent';
	    $mail->ClearAllRecipients( ); // clear all
	}	
	echo $vecSupervisores[$i]['cuerpo'];
	echo "<hr>";
}

echo "<script> window.close() </script>";

?>
