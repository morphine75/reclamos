<?php 
include ("../conexion.php");
conectar();
$descripcion=$_REQUEST['descripcion'];
$area_reclamo=$_REQUEST['area_reclamo'];
$dias=$_REQUEST['dias'];

$sql="INSERT INTO MOTIVOS_RECLAMOS (IDMOTIVO, IDAREA, DESCRIPCION) VALUES (0, ".$area_reclamo.", '".$descripcion."')";

if (mysql_query($sql))
{
	echo "Se han guardado los datos";
}
else
{
	echo "Ha existido un error ".$sql;
}

desconectar();
?>