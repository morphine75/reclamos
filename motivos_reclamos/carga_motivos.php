<?php 
include ("../conexion.php");
conectar();
$id=$_REQUEST['ide'];
$sql="SELECT M.IDMOTIVO, A.DESCRIPCION, A.IDAREA, M.DESCRIPCION AS DESMOTIVO FROM MOTIVOS_RECLAMOS M, AREAS_RECLAMOS A WHERE A.IDAREA=M.IDAREA AND IDMOTIVO=".$id;
$res=mysql_query($sql);
$descripcion=mysql_result($res, 0, 'DESMOTIVO');
$idarea=mysql_result($res, 0, 'IDAREA');

$sqlArea="SELECT * FROM AREAS_RECLAMOS A";
$resArea=mysql_query($sqlArea);

?>
<input type="hidden" value="<?php echo $id?>" id="ide_modi">
<table class="table">
	<tr>
		<td class="letra_blanca">DESCRIPCION</td>
		<td align="left"><input type="text" id="modi_descripcion" size="80" value="<?php echo $descripcion?>"></td>
	</tr>
	<tr>
		<td class="letra_blanca">AREA RECLAMO</td>
		<td align="left"><select id="modi_sindicato"><?php while ($rowAreas=mysql_fetch_array($resArea)){?><option value="<?php echo $rowAreas['IDAREA']?>" <?php if ($rowAreas['IDAREA']==$idarea) { echo "selected"; }?>><?php echo $rowAreas['DESCRIPCION'];?></option><?php } ?></select></td>
	</tr>	
</table>	
<?php
desconectar();
?>