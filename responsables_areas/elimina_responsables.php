<?php 
include ("../conexion.php");
conectar();
$id=$_REQUEST['ide'];

$sql="delete FROM RESPONSABLES_AREAS where IDRESPONSABLE_AREA=".$id;
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