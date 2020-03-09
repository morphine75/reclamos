<?php 
include ("../conexion.php");
conectar();
$id=$_REQUEST['ide'];

$sql="delete from categorias where idcategoria=".$id;
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