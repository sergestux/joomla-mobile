<?php
	
	function EjecutaSql($Conn, $SQL)
	{
		$Result = mysqli_query($Conn, $SQL);
		return $Result;
	}
		
	
	//Se conecta a la base de datos	
	function Conectar()
	{
		include "config.php";	//en config.php se encuentran los datos de acceso
		$Conn=mysqli_connect($Server, $UserBd, $PassBd, $Bd);
	
		/* verificar la conexión */
		if (mysqli_connect_errno()) {
			die("Conexión fallida: %s\n" . mysqli_connect_error());
			exit();
		}
		else
			return $Conn;			
	}
	
	
	
		
?>