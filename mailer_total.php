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

$dsn="logex"; 
$usuario="sysprogress";
$clave="chessdi446";
set_time_limit(0);
//realizamos la conexion mediante odbc
$cid=odbc_connect($dsn,$usuario, $clave) or die(odbc_errormsg());

include ('conexion.php');
conectar();

require_once ("../proyeccion_ventas/cuenta_domingos.php");
require_once ("../proyeccion_ventas/cuenta_feriados.php");


$fecha=date('Y-m-d');

$nuevafecha=strtotime('+1 day',strtotime($fecha));
$titulo    = 'Rechazos Logex Rutas '.date('Y-m-d',$nuevafecha);

$datos=domingos($fecha,$nuevafecha);
$cuenta_domingos=0;
foreach($datos as $nombre_campo => $valor){
     $cuenta_domingos++;
}


$nuevafecha=date('Y/m/d');

$feriados=feriados($nuevafecha, $nuevafecha);
$rutas="(";

if (($cuenta_domingos==0)&&($feriados==0))
{
	$fecha_convertir=date("w", strtotime($nuevafecha)); 
	$dias = array("1","2","3","4","5","6","7");
	$dia_semana=$dias[date($fecha_convertir)];
	if ($dia_semana==7)
		$dia_semana=1;
	$dia_semana=$dia_semana+1;
	$sqlRutas="select pr.ruta, r.d_ruta, p.c_perso, p.d_perso, e.d_perso as d_super, e.c_perso as c_super from pub.persorut pr, pub.perscom p, pub.perscom e, pub.rutasv r where pr.fecrelahasta='9999/12/31' and pr.diasvis like '%".$dia_semana."%' and pr.c_perso=p.c_perso and p.c_super=e.c_perso and p.idSucur=e.idSucur and pr.idsucur=p.idSucur and r.ruta=pr.ruta and r.idsucur=pr.idsucur and r.idesq=pr.idesq order by 6, 1";
	$resRutas=odbc_exec($cid,$sqlRutas) or die(exit("Error en odbc_exec"));
}
else
{
	$dias_adelantar=$cuenta_domingos+$feriados;
	$nuevafecha=strtotime('+'.$dias_adelantar.' day',strtotime($nuevafecha));

	$fecha_convertir=date("w", strtotime($nuevafecha)); 
	$dias = array("1","2","3","4","5","6","7");
	$dia_semana=$dias[date($fecha_convertir)];
	$dia_semana=$dia_semana+$dias_adelantar;
	$sqlRutas="select pr.ruta, r.d_ruta, p.c_perso, p.d_perso, e.d_perso as d_super, e.c_perso as c_super from pub.persorut pr, pub.perscom p, pub.perscom e, pub.rutasv r where pr.fecrelahasta='9999/12/31' and pr.diasvis like '%".$dia_semana."%' and pr.c_perso=p.c_perso and p.c_super=e.c_perso and p.idSucur=e.idSucur and pr.idsucur=p.idSucur and r.ruta=pr.ruta and r.idsucur=pr.idsucur and r.idesq=pr.idesq order by 6, 1";

	$resRutas=odbc_exec($cid,$sqlRutas) or die(exit("Error en odbc_exec"));
}

$i=0;
$vecRutas=array();
while ($rowRutas=odbc_fetch_array($resRutas))
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

$sqlRechazos="select k.fechafac, cli.idcliente, cli.nomcli, clr.ruta, r.dsrechazo, k.c_perso from pub.motivos m, pub.mascara k, pub.rechazo r, pub.clialias cli, pub.clirut clr where k.fechafac between '".$fecha_antes."' and '".$nuevafecha."' and clr.idcliente=cli.idcliente and cli.idcliente=k.idcliente and k.idesq=clr.idesq and m.nrodoc=k.nrodoc and k.letra=m.letra and k.idsucur=".$sucursal." and m.serie=k.serie and m.nrodoc=k.nrodoc and r.idrechazo=m.idrechazo and clr.fecrutahasta='9999/12/31' and k.iddocumento in (select iddocumento from pub.tipodocs where estadisticas='-') and clr.ruta in ".$rutas." order by 4";

$resRechazos=odbc_exec($cid, $sqlRechazos);
$vecRechazos=array();
$i=0;
while ($rowRechazos=odbc_fetch_array($resRechazos))
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
odbc_close($cid);

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
