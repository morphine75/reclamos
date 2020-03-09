<?php 
include ("../conexion.php");
conectar();
$id=$_REQUEST['ide'];
$sql="select * from reclamos where reclamo=".$id;
$res=mysql_query($sql);
$descripcion=mysql_result($res, 0, 'descripcion');
$idsindicato=mysql_result($res, 0, 'idsindicato');

$sqlSindicato="select * from sindicatos";
$resSindicato=mysql_query($sqlSindicato);

?>
<input type="hidden" value="<?php echo $id?>" id="ide_modi">
<table class="table">
	<tr>
		<td class="letra_blanca">DESCRIPCION CATEGORIA</td>
		<td align="left"><input type="text" id="modi_descripcion" size="80" value="<?php echo $descripcion?>"></td>
	</tr>
	<tr>
		<td class="letra_blanca">SINDICATO</td>
		<td align="left"><select id="modi_sindicato"><?php while ($rowSindicato=mysql_fetch_array($resSindicato)){?><option value="<?php echo $rowSindicato['IDSINDICATO']?>" <?php if ($rowSindicato['IDSINDICATO']==$idsindicato) { echo "selected"; }?>><?php echo $rowSindicato['DESCRIPCION'];?></option><?php } ?></select></td>
	</tr>	
</table>	
<?php
desconectar();
?>