<?php 
include ("../conexion.php");
conectar();
$id=$_REQUEST['ide'];
$sql="SELECT E.IDRESPONSABLE_AREA, R.IDRESPONSABLE_AREA AS IDSUPERIOR, E.NOMBRE AS NOMSUBDITO, E.IDAREA, E.CORREO AS MAILSUBDITO, R.NOMBRE AS NOMSUPERIOR, R.CORREO FROM RESPONSABLES_AREAS R, RESPONSABLES_AREAS E WHERE R.IDRESPONSABLE_AREA=E.RES_IDRESPONSABLE_AREA AND E.IDRESPONSABLE_AREA=".$id;
$res=mysql_query($sql);
$idresponsable=mysql_result($res, 0, 'IDRESPONSABLE_AREA');
$idsuperior=mysql_result($res, 0, 'IDSUPERIOR');
$idarea=mysql_result($res, 0, 'IDAREA');
$nomsubdito=mysql_result($res, 0, 'NOMSUBDITO');
$mailsubdito=mysql_result($res, 0, 'MAILSUBDITO');
$nomsuperior=mysql_result($res, 0, 'NOMSUPERIOR');
$correo=mysql_result($res, 0, 'CORREO');

$sqlAreasReclamos="SELECT * FROM AREAS_RECLAMOS";
$resAreasReclamos=mysql_query($sqlAreasReclamos);

$sqlSuperiores="SELECT IDRESPONSABLE_AREA, NOMBRE FROM RESPONSABLES_AREAS";
$resSuperiores=mysql_query($sqlSuperiores);

?>
<input type="hidden" value="<?php echo $id?>" id="ide_modi">
<table class="table">
	<tr>
		<td class="letra_blanca">NOMBRE</td>
		<td align="left"><input type="text" id="modi_nombre" size="80" value="<?php echo $nomsubdito?>"></td>
	</tr>
	<tr>
		<td class="letra_blanca">SUPERIOR</td>
		<td align="left"><select id="modi_superiores"><?php while ($rowSuperiores=mysql_fetch_array($resSuperiores)){?><option value="<?php echo $rowSuperiores['IDRESPONSABLE_AREA']?>" <?php if ($rowSuperiores['IDRESPONSABLE_AREA']==$idsuperior) { echo "selected"; } ?> ><?php echo $rowSuperiores['NOMBRE'];?></option><?php } ?></select></td>
	</tr>				
	<tr>
		<td class="letra_blanca">AREA RECLAMO</td>
		<td align="left"><select id="modi_area_reclamo"><?php while ($rowAreasReclamos=mysql_fetch_array($resAreasReclamos)){?><option value="<?php echo $rowAreasReclamos['IDAREA']?>" <?php if ($rowAreasReclamos['IDAREA']==$idarea) { echo "selected"; } ?>><?php echo $rowAreasReclamos['DESCRIPCION'];?></option><?php } ?></select></td>
	</tr>			
	<tr>
		<td class="letra_blanca">CORREO ELECTRONICO</td>
		<td align="left"><input type="text" id="modi_email" size="80" value="<?php echo $mailsubdito?>"></td>
	</tr>
</table>	
<?php
desconectar();
?>