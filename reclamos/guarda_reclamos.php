<?php 
include ("../conexion.php");
conectar();

$cliente=$_REQUEST['cliente'];
$fecha=$_REQUEST['fecha'];
$fecha_vencimiento=$_REQUEST['fecha_vencimiento'];
$motivo=$_REQUEST['motivo'];
$observaciones=$_REQUEST['observaciones'];

$datos_clientes=explode("-", $cliente);

$sql="INSERT INTO RECLAMOS (RECLAMO, IDESTADO, IDMOTIVO, CLIENTE, NOMCLIENTE, FECHA, FECHA_VENCIMIENTO, OBSERVACIONES) VALUES (0, 3, ".$motivo.", ".$datos_clientes[0].", '".$datos_clientes[1]."', '".$fecha."', '".$fecha_vencimiento."', '".$observaciones."')";

if (mysql_query($sql))
{
	echo "Se han guardado los datos";
}
else
{
	echo "Ha existido un error ".$sql;
}

include ('../mailer_reclamo.php');

$sqlResp="SELECT NOMBRE, CORREO FROM MOTIVOS_RECLAMOS M, AREAS_RECLAMOS A, RESPONSABLES_AREAS R WHERE R.IDAREA = A.IDAREA AND M.IDAREA = A.IDAREA AND M.IDMOTIVO=".$motivo;
$resResp=mysql_query($sqlResp);
$vecResponsables=array();
$i=0;
while($rowResp=mysql_fetch_array($resResp))
{
	$vecResponsables[$i]['NOMBRE']=$rowResp['NOMBRE'];
	$vecResponsables[$i]['CORREO']=$rowResp['CORREO'];
	$i++;
}

enviar_reclamo($vecResponsables, $observaciones, $datos_clientes[0], $datos_clientes[1]);


desconectar();
?>