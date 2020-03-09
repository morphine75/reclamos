<?php
	$dsn="logex"; 
	$usuario="sysprogress";
	$clave="chessdi446";
	set_time_limit(0);
	//realizamos la conexion mediante odbc
	$cid=odbc_connect($dsn,$usuario, $clave) or die(odbc_errormsg());	
	
	$cliente=$_REQUEST['cliente'];	
	if ($cliente!='')
	{
		$idcliente=explode("-", $cliente);


		$sql="select nomcli, numcuit, domicli, ycoord, xcoord, idsucur, k.descrip, t.descnego, cla.tipoiva from pub.clientes c, pub.clialias cla, pub.canales k, pub.tiponego t where cla.idcliente=".$idcliente[0]." and cla.idcliente=c.idcliente and c.codcanal=k.codcanal and c.codnego=t.codnego";

		$res=odbc_exec($cid, $sql);
		$nombre=odbc_result($res, 'nomcli');
		$tipoiva=odbc_result($res, 'tipoiva');
		$numcuit=odbc_result($res, 'numcuit');
		$domicli=odbc_result($res, 'domicli');
		$canal=odbc_result($res, 'descrip');
		$negocio=odbc_result($res, 'descnego');

		$sqlRV="select cl.idcliente, p.idsucur, pr.ruta, p.d_perso as vendedor, e.c_perso, e.d_perso as supervisor, diasvis from pub.persorut pr, pub.perscom p, pub.perscom e, pub.clirut cl where pr.c_perso=p.c_perso and pr.idsucur=p.idsucur and cl.idsucur=pr.idsucur and cl.ruta=pr.ruta and cl.idcliente=".$idcliente[0]." and cl.fecrutahasta='9999/12/31' and fecrelahasta='9999/12/31' and cl.idesq=pr.idesq and p.c_super=e.c_perso and p.idsucur=e.idsucur order by 1";

		$resRV=odbc_exec($cid, $sqlRV);
		$i=0;
		$vecRV=array();
		while ($rowRV=odbc_fetch_array($resRV))
		{
			$vecRV[$i]['idcliente']=$rowRV['idcliente'];		
			$vecRV[$i]['idsucur']=$rowRV['idSucur'];
			$vecRV[$i]['ruta']=$rowRV['ruta'];
			$vecRV[$i]['vendedor']=$rowRV['VENDEDOR'];
			$vecRV[$i]['supervisor']=$rowRV['SUPERVISOR'];
			$vecRV[$i]['diasvis']=$rowRV['diasvis'];
			$vecRV[$i]['diasvis']=str_replace(",", " - ", $vecRV[$i]['diasvis']);
			$vecRV[$i]['diasvis']=str_replace("2", "Lunes", $vecRV[$i]['diasvis']);
			$vecRV[$i]['diasvis']=str_replace("3", "Martes", $vecRV[$i]['diasvis']);
			$vecRV[$i]['diasvis']=str_replace("4", "Miercoles", $vecRV[$i]['diasvis']);
			$vecRV[$i]['diasvis']=str_replace("5", "Jueves", $vecRV[$i]['diasvis']);
			$vecRV[$i]['diasvis']=str_replace("6", "Viernes", $vecRV[$i]['diasvis']);
			$vecRV[$i]['diasvis']=str_replace("7", "Sabado", $vecRV[$i]['diasvis']);
			$i++;
		}
?>
	<hr>
		<p>
			<span>Canal: <?php echo $canal?></span>
		</p>
		<p>
			<span>Negocio: <?php echo $negocio?></span>
		</p>				
		<p>
			<span>Direccion: <?php echo $domicli?></span>
		</p>
		<p>
			<span>CUIT: <?php echo $numcuit?></span>
		</p>
		<p>
			<span>TIPO IVA: <?php echo $tipoiva?></span>
		</p>		
		<?php
		for ($i=0;$i<count($vecRV);$i++)
		{?>
			<p><span >Supervisor: <?php echo $vecRV[$i]['supervisor']?>  <input type="checkbox" name="supervisor[]" checked="checked"></span> </p> <p><span >Vendedor: <?php echo $vecRV[$i]['vendedor']?>. Dias de Visita: <?php echo $vecRV[$i]['diasvis']?></span></p>
		<?php
		}
	}
	odbc_close($cid);
	?>
	<hr>