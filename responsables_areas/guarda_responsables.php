<?php 
include ("../conexion.php");
conectar();
$nombre=$_REQUEST['nombre'];
$area_reclamo=$_REQUEST['area_reclamo'];
$superior=$_REQUEST['superior'];
$correo=$_REQUEST['correo'];
$sql="INSERT INTO RESPONSABLES_AREAS (IDRESPONSABLE_AREA, IDAREA, RES_IDRESPONSABLE_AREA, NOMBRE, CORREO) VALUES (0, ".$area_reclamo.", ".$superior.", '".$nombre."', '".$correo."')";

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