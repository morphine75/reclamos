
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Bootstrap 3, from LayoutIt!</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link href="../css/bootstrap.min.css" rel="stylesheet">

<link rel="apple-touch-icon-precomposed" sizes="144x144" href="img/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="img/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="img/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="img/apple-touch-icon-57-precomposed.png">
  
<script type="text/javascript" src="../js/jquery.min.js"></script>
<script type="text/javascript" src="../js/bootstrap.min.js"></script>
<script type="text/javascript" src="../js/scripts.js"></script>
</head>
<script>

	function eliminar(ide)
	{
		var dataString='ide='+ide;
		$.ajax({
			type: 'POST',
			data: dataString,
			url: 'elimina_motivos.php',
			success: function (a) {
				alert (a);
				location.reload();
			}
		});		
	}
	
	function guardar()
	{
		var descripcion=$('#descripcion').val();
		var area_reclamo=$('#area_reclamo').val();
		var dias=$('#dias').val();
		var dataString='descripcion='+descripcion+'&area_reclamo='+area_reclamo+'&dias='+dias;
		$.ajax({
			type: 'POST',
			data: dataString,
			url: 'guarda_motivos.php',
			success: function (a) {
				alert (a);
				location.reload();
			}
		});
	}
	
	function cargar(ide)
	{
		var dataString='ide='+ide;
		$.ajax({
			type: 'POST',
			data: dataString,
			url: 'carga_motivos.php',
			success: function (a) {
				$('#modal-body-modifica').html(a);
			}
		});
	}
	
	function modificar()
	{		
		var descripcion=$('#modi_descripcion').val();
		var sindicato=$('#modi_sindicato').val();
		var ide=$('#ide_modi').val();		
		var dataString='ide='+ide+'&descripcion='+descripcion+'&sindicato='+sindicato;
		//alert (dataString);
		$.ajax({
			type: 'POST',
			data: dataString,
			url: 'modifica_motivos.php',
			success: function (a) {
				alert (a);
				location.reload();
			}
		});
	}	
	
</script>
<style>
	.letra_blanca {background-color: #000033; color: #FFFFFF};
</style>
<body>
<?php
include ("../conexion.php");
conectar();
?>				
<h1>MOTIVOS DE RECLAMOS</h1>
<a href="../../../../../Logex/mcompras.html" role="button"><button type="button" class="btn btn-lg btn-primary">Salir</button></a>
<a id="modal-abm" href="#modal-container-abm" role="button" data-toggle="modal"><button type="button" class="btn btn-lg btn-primary">Nuevo Registro</button></a>
<br>
<table class="table table-hover" style="padding:10px">
	<thead>
		<tr class="letra_blanca">
			<th>ID</th>
			<th>DESCRIPCION MOTIVO</th>
			<th>AREA</th>			
			<th>DIAS MAX.RESOLUCION</th>	
			<th>MODIFICA</th>
			<th>ELIMINAR</th>			
		</tr>
	</thead>
	<tbody>
	<?php
	$sql="SELECT M.IDMOTIVO, A.DESCRIPCION, M.DESCRIPCION AS DESMOTIVO, DIAS_RESOLUCION FROM MOTIVOS_RECLAMOS M, AREAS_RECLAMOS A WHERE A.IDAREA=M.IDAREA";
	$res=mysql_query($sql);
	$i=0;
	while ($row=mysql_fetch_array($res))
	{
	?>				
		<tr>			
			<td><?php echo $row['IDMOTIVO']?></td>
			<td><?php echo $row['DESMOTIVO']?></td>
			<td><?php echo $row['DESCRIPCION']?></td>
			<td><?php echo $row['DIAS_RESOLUCION']?></td>
			<td><a id="modal-modifica" href="#modal-container-modifica" role="button" class="btn" data-toggle="modal" onclick="cargar(<?php echo $row['IDMOTIVO']?>);"><img src="pencil.png" title="Modificar"></a></td>
			<td><img src="del.png" title="Eliminar" style="cursor:pointer" onclick="eliminar(this.id)" id="<?php echo $row['IDMOTIVO']?>"></td>
		</tr>
	<?php
	}		
	?>
	</tbody>
</table>


<!--ALTA DE roles-->	
<div class="modal fade" id="modal-container-abm" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h4 class="modal-title" id="myModalLabel">
				NUEVO REGISTRO
			</h4>
		</div>
		<?php 
		$sqlAreasReclamos="SELECT * FROM AREAS_RECLAMOS";
		$resAreasReclamos=mysql_query($sqlAreasReclamos);
		?>
		<div class="modal-body">		
			<table class="table">
				<tr>
					<td class="letra_blanca">DESCRIPCION</td>
					<td align="left"><input type="text" id="descripcion" size="80"></td>
				</tr>
				<tr>
					<td class="letra_blanca">AREA RECLAMO</td>
					<td align="left"><select id="area_reclamo"><?php while ($rowAreasReclamos=mysql_fetch_array($resAreasReclamos)){?><option value="<?php echo $rowAreasReclamos['IDAREA']?>"><?php echo $rowAreasReclamos['DESCRIPCION'];?></option><?php } ?></select></td>
				</tr>	
				<tr>
					<td class="letra_blanca">DIAS MAX.RESOLUCION</td>
					<td align="left"><input type="text" id="dias" size="5"></td>
				</tr>
			</table>	
		</div>
		<div class="modal-footer">
			 <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button><button type="button" class="btn btn-primary" onclick="guardar()" data-dismiss="modal">Guardar</button>
		</div>
	</div>
</div>
</div>	

<!--MODIFICACION DE GUIAS A FLETEROS-->	
<div class="modal fade" id="modal-container-modifica" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h4 class="modal-title" id="myModalLabel">
				MODIFICAR REGISTRO
			</h4>
		</div>
		<div class="modal-body" id="modal-body-modifica">

		</div>	
		<div class="modal-footer">
			 <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button><button type="button" class="btn btn-primary" onclick="modificar()" data-dismiss="modal">Guardar</button>
		</div>
	</div>
</div>
</div>		

</body>		