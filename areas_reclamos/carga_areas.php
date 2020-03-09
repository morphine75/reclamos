<?php 
include ("../conexion.php");
conectar();
$id=$_REQUEST['ide'];
$sql="select * from AREAS_RECLAMOS where IDAREA=".$id;
$res=mysql_query($sql);
$descripcion=mysql_result($res, 0, 'DESCRIPCION');

?>
<input type="hidden" value="<?php echo $id?>" id="ide_modi">
<table class="table">
	<tr>
		<td class="letra_blanca">DESCRIPCION</td>
		<td align="left"><input type="text" id="modi_descripcion" size="80" value="<?php echo $descripcion?>"></td>
	</tr>
</table>	
<?php
desconectar();
?>