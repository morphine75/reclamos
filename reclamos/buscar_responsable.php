<?php

include ("../conexion.php");
conectar();

$reclamo=$_REQUEST['reclamo'];

$sqlResp="SELECT NOMBRE, CORREO FROM MOTIVOS_RECLAMOS M, AREAS_RECLAMOS A, RESPONSABLES_AREAS R WHERE R.IDAREA = A.IDAREA AND M.IDAREA = A.IDAREA AND M.IDMOTIVO=".$reclamo;
$resResp=mysql_query($sqlResp);

while ($rowResp=mysql_fetch_array($resResp)) 
{ 
	echo $rowResp['NOMBRE']." - ".$rowResp['CORREO']."<br>";
}

desconectar();
?>