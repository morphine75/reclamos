<?php
function conectar()
{
	global $con;
	$ret = false;
		$con = mysql_connect("localhost","root","");
		if ($con)
		{
				mysql_select_db("modulo_logex",$con);
				$ret = true;
		}
		
	return $ret;
}
function desconectar()
{
	global $con;
	mysql_close($con);
}

/*
r:109
g:133
B:168
#:6d85a8
*/

?>