
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Bootstrap 3, from LayoutIt!</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link href="../css/bootstrap.min.css" rel="stylesheet">

<link rel="apple-touch-icon-precomposed" sizes="144x144" href="img/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="img/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="img/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="img/apple-touch-icon-57-precomposed.png">
  
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" src="../js/bootstrap.min.js"></script>
<script type="text/javascript" src="../js/scripts.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<style>
.ui-autocomplete{
    z-index:1050;
}
</style>
</head>
<?php
include ("../conexion.php");
conectar();

$sqlClientes="SELECT IDCLIENTE, NOMCLI, CALLE, ALTURA, RUTAV FROM CLIENTES WHERE IDSUCUR in (1,4)";

$resClientes=mysql_query($sqlClientes);

$clientes="[";

while ($rowClientes=mysql_fetch_array($resClientes))
{
	$clientes=$clientes."'".$rowClientes['IDCLIENTE']." - ".$rowClientes['NOMCLI']." - ".$rowClientes['CALLE']." ".$rowClientes['ALTURA']." - Ruta: ".$rowClientes['RUTAV']."',";
}

$clientes=substr($clientes, 0, -1);

$clientes=$clientes."]";

$dsn="logex"; 
$usuario="sysprogress";
$clave="chessdi446";
set_time_limit(0);
//realizamos la conexion mediante odbc
$cid=odbc_connect($dsn,$usuario, $clave) or die(odbc_errormsg());

/*$sqlEsquema="select * from pub.esquema where idesq<=3 and idsucur=1";
$resEsquema=odbc_exec($cid, $sqlEsquema);*/

?>
<script>

$( function() {
    var availableTags = <?php echo $clientes?>;
    $("#cliente").autocomplete({
      source: availableTags,
    });
	$("#cliente").autocomplete( "option", "appendTo", ".eventInsForm");
  } );

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
		var cliente=$('#cliente').val();
		var fecha=$('#fecha').val();
		var fecha_vencimiento=$('#fecha_vencimiento').val();
		var motivo=$('#motivo').val();
		var observaciones=$('#observaciones').val();
		var dataString='cliente='+cliente+'&fecha='+fecha+'&fecha_vencimiento='+fecha_vencimiento+'&motivo='+motivo+'&observaciones='+observaciones;
		alert (dataString);
		$.ajax({
			type: 'POST',
			data: dataString,
			url: 'guarda_reclamos.php',
			success: function (a) {
				alert (a);
				//location.reload();
			}
		});
	}
	
	function cargar(ide)
	{
		var dataString='ide='+ide;
		$.ajax({
			type: 'POST',
			data: dataString,
			url: 'carga_reclamos.php',
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
			url: 'modifica_reclamos.php',
			success: function (a) {
				alert (a);
				location.reload();
			}
		});
	}	

	function buscar_responsable(id)
	{
		var dataString="reclamo="+id;
		$.ajax({
			type:'POST',
			data: dataString,
			url: 'buscar_responsable.php',
			success: function(a){
				$('#responsables').html(a);
			}
		})
	}

	function busca_datos(cliente)
	{
		var dataString='cliente='+cliente;
		$.ajax({
			type:'POST',
			url:'busca_datos.php',
			data: dataString,
			success: function (a){
				$('#datos').html(a);
			}
		})
	}
	
</script>
<style>
	.letra_blanca {background-color: #000033; color: #FFFFFF};
</style>
<body>
<?php




?>				
<h1>RECLAMOS</h1>
<a href="../../../../../Logex/mcompras.html" role="button"><button type="button" class="btn btn-lg btn-primary">Salir</button></a>
<a id="modal-abm" href="#modal-container-abm" role="button" data-toggle="modal"><button type="button" class="btn btn-lg btn-primary">Nuevo Registro</button></a>
<br>
<table class="table table-hover" style="padding:10px">
	<thead>
		<tr class="letra_blanca">
			<th>ID</th>
			<th>CLIENTE</th>
			<th>FECHA</th>			
			<th>FECHA VENCIMIENTO</th>			
			<th>MOTIVO</th>
			<th align="center">CARGAR SOLUCION</th>			
			<th>MODIFICA</th>
			<th>ELIMINAR</th>			
		</tr>
	</thead>
	<tbody>
	<?php
	$sql="SELECT RECLAMO, R.CLIENTE, R.NOMCLIENTE, R.FECHA, R.FECHA_VENCIMIENTO, M.DESCRIPCION, R.IDESTADO, R.SOLUCION, R.OBSERVACIONES FROM RECLAMOS R, ESTADO_RECLAMO E, MOTIVOS_RECLAMOS M WHERE E.IDESTADO = R.IDESTADO AND M.IDMOTIVO=R.IDMOTIVO ";
	$res=mysql_query($sql);
	$i=0;
	$hoy=new DateTime(date('Y-m-d'));
	while ($row=mysql_fetch_array($res))
	{
		$fecha2 = new DateTime(date($row['FECHA_VENCIMIENTO']));
		$resultado = $hoy->diff($fecha2);	
		$dias=$resultado->format('%d');
		if (($dias>0)&&($row['IDESTADO']!=4))
		{		
		?>				
			<tr class="danger">
		<?php
		}
		else
		{
		?>
			<tr>
		<?php
		}
		?>
			<td><?php echo $row['RECLAMO']?></td>
			<td><?php echo $row['NOMCLIENTE']?></td>
			<td><?php echo $row['FECHA']?></td>
			<td><?php echo $row['FECHA_VENCIMIENTO']?></td>
			<td><?php echo $row['DESCRIPCION']?></td>
			<td align="center"><img src="../../imagenes/check.png" title="Cargar Solucion"></td>
			<td><a id="modal-modifica" href="#modal-container-modifica" role="button" class="btn" data-toggle="modal" onclick="cargar(<?php echo $row['RECLAMO']?>);"><img src="pencil.png" title="Modificar"></a></td>
			<td><img src="del.png" title="Eliminar" style="cursor:pointer" onclick="eliminar(this.id)" id="<?php echo $row['RECLAMO']?>"></td>
		</tr>
		<tr style="display: none">
			<td>OBSERVACIONES: </td>
			<?php
			if (($dias>0)&&($row['IDESTADO']!=4))
			{
			?>
				<td colspan="7" class="danger"><?php echo $row['OBSERVACIONES']?></td>				
			<?php
			}
			else
			{
			?>
				<td colspan="7" class="success"><?php echo $row['OBSERVACIONES']?></td>			
			<?php
			}
			?>
		</tr>
		<tr style="display: none">
			<td>SOLUCION: </td>
			<?php
			if (($dias>0)&&($row['IDESTADO']!=4))
			{
			?>
				<td colspan="7" class="danger"><?php echo $row['SOLUCION']; echo $dias; echo $row['IDESTADO'];?></td>				
			<?php
			}
			else
			{
			?>
				<td colspan="7" class="success"><?php echo $row['SOLUCION']; echo $dias; echo $row['IDESTADO'];?></td>			
			<?php
			}
			?>
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
		$sqlMotivosReclamos="SELECT * FROM MOTIVOS_RECLAMOS";
		$resMotivosReclamos=mysql_query($sqlMotivosReclamos);
		?>
		<div class="modal-body">		
			<div class="row">
				<div class="col-md-8">
					<table class="table">
						<tr>
							<td class="letra_blanca">CLIENTE</td>
							<td align="left"><input type="text" id="cliente" size="120" onblur="busca_datos(this.value)" onkeypress="if (event.keyCode==13){ busca_datos(this.value) }"></td>
						</tr>
						<tr>
							<td class="letra_blanca">FECHA</td>
							<td align="left"><input type="text" value="<?php echo date ('Y-m-d');?>" readonly="readonly" id="fecha"></td>
						</tr>
						<tr>
							<td class="letra_blanca">FECHA DE VENCIMIENTO</td>
							<td align="left"><input type="date" value="<?php echo date ('Y-m-d');?>" id="fecha_vencimiento"></td>
						</tr>				
						<tr>
							<td class="letra_blanca">MOTIVO</td>
							<td align="left"><select id="motivo" onchange="buscar_responsable(this.value)"><?php while ($rowMotivosReclamos=mysql_fetch_array($resMotivosReclamos)){?><option value="<?php echo $rowMotivosReclamos['IDMOTIVO']?>"><?php echo $rowMotivosReclamos['DESCRIPCION'];?></option><?php } ?></select></td>
						</tr>		
						<!--<tr>
							<td class="letra_blanca">ESQUEMA</td>
							<td align="left"><select id="motivo"><?php //while ($rowEsquema=odbc_fetch_array($resEsquema)){?><option value="<?php //echo $rowEsquema['idEsq']?>"><?php //echo $rowEsquema['desEsq'];?></option><?php //} ?></select></td>
						</tr>-->				
						<tr>
							<td class="letra_blanca">OBSERVACIONES</td>
							<td align="left"><textarea name="Text1" cols="120" rows="9" id="observaciones"></textarea></td>
						</tr>
						<tr>
							<td class="letra_blanca">RESPONSABLE/S DEL AREA</td>
							<?php
							$sqlResp="SELECT NOMBRE, CORREO FROM MOTIVOS_RECLAMOS M, AREAS_RECLAMOS A, RESPONSABLES_AREAS R WHERE R.IDAREA = A.IDAREA AND M.IDAREA = A.IDAREA AND M.IDMOTIVO IN (SELECT MIN(IDMOTIVO) FROM MOTIVOS_RECLAMOS)";
							$resResp=mysql_query($sqlResp);
							?>
							<td><label id="responsables"><?php while ($rowResp=mysql_fetch_array($resResp)) { echo $rowResp['NOMBRE']." - ".$rowResp['CORREO']."<br>"; }?></label></td>
						</tr>
					</table>
				</div>
				<div class="col-md-4" id="datos" style="background-color: #C4DBF6">

				</div>				
			</div>
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
<?php
odbc_close($cid);
desconectar();
?>
</body>		
</html>