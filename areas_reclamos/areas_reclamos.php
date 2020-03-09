
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
			url: 'elimina_areas.php',
			success: function (a) {
				alert (a);
				location.reload();
			}
		});		
	}
	
	function guardar()
	{
		var descripcion=$('#descripcion').val();
		var dataString='descripcion='+descripcion;
		$.ajax({
			type: 'POST',
			data: dataString,
			url: 'guarda_areas.php',
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
			url: 'carga_areas.php',
			success: function (a) {
				$('#modal-body-modifica').html(a);
			}
		});
	}
	
	function modificar()
	{		
		var descripcion=$('#modi_descripcion').val();
		var ide=$('#ide_modi').val();		
		var dataString='ide='+ide+'&descripcion='+descripcion;
		//alert (dataString);
		$.ajax({
			type: 'POST',
			data: dataString,
			url: 'modifica_areas.php',
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
<h1>AREAS DE RECLAMOS</h1>
<a href="../../../../../Logex/mcompras.html" role="button"><button type="button" class="btn btn-lg btn-primary">Salir</button></a>
<a id="modal-abm" href="#modal-container-abm" role="button" data-toggle="modal"><button type="button" class="btn btn-lg btn-primary">Nuevo Registro</button></a>
<br>
<table class="table table-hover" style="padding:10px">
	<thead>
		<tr class="letra_blanca">
			<th>ID</th>
			<th>AREA</th>			
			<th>MODIFICAR</th>
			<th>ELIMINAR</th>			
		</tr>
	</thead>
	<tbody>
	<?php
	$sql="SELECT IDAREA, DESCRIPCION FROM AREAS_RECLAMOS";
	$res=mysql_query($sql);
	$i=0;
	while ($row=mysql_fetch_array($res))
	{
	?>				
		<tr>			
			<td><?php echo $row['IDAREA']?></td>
			<td><?php echo $row['DESCRIPCION']?></td>
			<td><a id="modal-modifica" href="#modal-container-modifica" role="button" class="btn" data-toggle="modal" onclick="cargar(<?php echo $row['IDAREA']?>);"><img src="pencil.png" title="Modificar"></a></td>
			<td><img src="del.png" title="Eliminar" style="cursor:pointer" onclick="eliminar(this.id)" id="<?php echo $row['IDAREA']?>"></td>
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
		<div class="modal-body">		
			<table class="table">
				<tr>
					<td class="letra_blanca">DESCRIPCION AREA</td>
					<td align="left"><input type="text" id="descripcion" size="80"></td>
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