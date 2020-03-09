<?php 
include ("../conexion.php");
conectar();
$descripcion=$_REQUEST['descripcion'];
$sql="INSERT INTO AREAS_RECLAMOS (IDAREA, DESCRIPCION) VALUES (0, '".$descripcion."')";

if(mysql_query($sql))
{
	echo "Se han guardado los datos";
}
else
{
	echo "Ha existido un error ".$sql;
}

desconectar();
?>