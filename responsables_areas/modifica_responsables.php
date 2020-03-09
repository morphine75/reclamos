<?php 
include ("../conexion.php");
conectar();
$id=$_REQUEST['ide'];
$nombre=$_REQUEST['nombre'];
$area_reclamo=$_REQUEST['area_reclamo'];
$superior=$_REQUEST['superior'];
$correo=$_REQUEST['correo'];

$sql="UPDATE RESPONSABLES_AREAS set NOMBRE='".$nombre."' , IDAREA=".$area_reclamo.", RES_IDRESPONSABLE_AREA=".$superior.", CORREO='".$correo."' where IDRESPONSABLE_AREA=".$id;


if(mysql_query($sql))
{
	echo "Se han guardado las modificaciones";
}
else
{
	echo "Ha existido un error ".$sql;
}


?>