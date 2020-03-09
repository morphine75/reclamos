<?php 
include ("../conexion.php");
conectar();
$id=$_REQUEST['ide'];

$sql="delete from MOTIVOS_RECLAMOS where IDMOTIVO=".$id;
if (mysql_query($sql))
{
	echo "Registro eliminado";
}
else
{
	echo "Ha existido un error ".$sql;
}
desconectar();
?>