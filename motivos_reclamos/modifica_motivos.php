<?php 
include ("../conexion.php");
conectar();
$id=$_REQUEST['ide'];
$descripcion=$_REQUEST['descripcion'];
$sindicato=$_REQUEST['sindicato'];

$sql="update categorias set descripcion='".$descripcion."' , idsindicato=".$sindicato." where idcategoria=".$id;


if(mysql_query($sql))
{
	echo "Se han guardado las modificaciones";
}
else
{
	echo "Ha existido un error ".$sql;
}


?>