<?php 
include ("../conexion.php");
conectar();
$id=$_REQUEST['ide'];
$descripcion=$_REQUEST['descripcion'];

$sql="UPDATE AREAS_RECLAMOS set DESCRIPCION='".$descripcion."' where IDAREA=".$id;


if(mysql_query($sql))
{
	echo "Se han guardado las modificaciones";
}
else
{
	echo "Ha existido un error ".$sql;
}


?>