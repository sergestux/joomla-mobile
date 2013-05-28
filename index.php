<!DOCTYPE html> 
<html>
<head>
<meta charset="utf-8">
<title>jQuery Mobile Web App</title>
<link href="jquery-mobile/jquery.mobile.theme-1.0.min.css" rel="stylesheet" type="text/css"/>
<link href="jquery-mobile/jquery.mobile.structure-1.0.min.css" rel="stylesheet" type="text/css"/>
<script src="jquery-mobile/jquery-1.6.4.min.js" type="text/javascript"></script>
<script src="jquery-mobile/jquery.mobile-1.0.min.js" type="text/javascript"></script>
</head> 
<body> 

<div data-role="page" id="page">
	<div data-role="header">
		<h1>Mi sitio.com</h1>
	</div>
	<div data-role="content">	
		<ul data-role="listview" data-filter="true" data-filter-placeholder="Buscar...">
<?php
	include "config.php";
	include "funciones.php";
	$Conn=Conectar();

	//Saco las categorias padre
	$SQL="SELECT id, title, description FROM news_categories"; 
	$SQL .= " WHERE parent_id=1 AND published=1 AND extension='com_content'";
	$SQL .= " ORDER BY title";	
	$Res=EjecutaSql($Conn, $SQL);

	while ($DATOS=  mysqli_fetch_assoc($Res))
	{		
		$CatId=$DATOS['id'];		
		
		if($CatId==2)
			$CatTitulo="Sin categoria";	
		else
			$CatTitulo=$DATOS['title'];
		$CatDescripcion=$DATOS['description'];			
		echo "\t\t\t<li>";
			echo "<a href='#Categoria$CatId'>";
			//echo "<img src='/images/joomlaspanish-joomlacode.jpg'>";			
			echo "<h2>$CatTitulo</h2>";
			//echo "<p>$CatDescripcion</p>";
			echo "</a>";
		echo "</li>\n";
	}
	echo "\t\t</ul>";
	?>       
				
	</div>	
	<div data-role="footer">
		<h4>Page Footer</h4>
	</div>
</div>

<?php
	$CantCat=0;
	mysqli_data_seek($Res,0);	//Regreso el puntero a la pocision 0
	while ($DATOS= mysqli_fetch_assoc($Res))
	{		
		$CatId=$DATOS['id'];
	
		if($CatId==2)
			$CatTitulo="Sin categoria";			
		else
			$CatTitulo=$DATOS['title'];
		$CatDescripcion=$DATOS['description'];			
?> 	
<div data-role="page" id="Categoria<?=$CatId;?>"">
	<div data-role="header">
		<h1><?=$CatTitulo;?></h1>
	</div>
	<div data-role="content">	
<?php            
	echo "\t\t" . $CatDescripcion;		//Imprimo la descripcion e la categoria

	$SQL="SELECT id, title AS 'Titulo', introtext AS 'Introduccion',";
	$SQL .= " `fulltext` AS 'Texto', publish_up AS 'Fecha'";
	$SQL .= " FROM news_content WHERE catid=$CatId";
	$Articulos=EjecutaSql($Conn, $SQL);

	if (mysqli_num_rows($Articulos) >0)
		$CATEGORIAS[$CantCat++]=$CatId;	//Armo el arreglo de categorias
	
	//CREO UNA LISTA DE LINKS A CADA ARTICULO... de la ultima cateogoria:(
		while ($DATOS=  mysqli_fetch_assoc($Articulos))
		{		
			$CATEGORIAS[$CantCat]=$CatId;	//Armo el arreglo de categorias
			
			$ArtId=$DATOS['id'];
			$ArtTitulo=$DATOS['Titulo'];
			$ArtIntroduccion=$DATOS['Introduccion'];
			$ArtTexto=$DATOS['Texto'];
			$ArtFecha=$DATOS['Fecha'];
	?>         
		<ul data-role="listview" data-inset="true">
			<li data-role="list-divider"><?=$ArtTitulo;?></li>
			<li>
            	<a href="#Articulo<?=$ArtId;?>">                        
           			<p><?=$ArtIntroduccion;?></p>
					<p class="ui-li-aside"><strong><?=$ArtFecha;?></strong></p>
                  </a>
			</li>
			</ul>            
	<?php		
		}
	?> 
	</div>
	<div data-role="footer">
		<h4>Page Footer</h4>
	</div>
</div>		
<?php		
	}
?>   


<?php
	$Categorias="";
	for ($Cont=0; $Cont<$CantCat; $Cont++)
	{
		if($Cont>0)
			$Categorias .= ", ";
		$Categorias .= $CATEGORIAS[$Cont];
	}

	
	$SQL="SELECT id, title AS 'Titulo', introtext AS 'Introduccion',";
	$SQL .= " `fulltext` AS 'Texto', publish_up AS 'Fecha'";
	$SQL .= " FROM news_content WHERE catid IN ($Categorias)";
//	echo $SQL;
	$Articulos=EjecutaSql($Conn, $SQL);


	mysqli_data_seek($Articulos,0);	//Regreso el puntero a la pocision 0
/*CREO UNA PAGINA POR CADA ARTICULO ENCONTRADO*/
	
	while ($DATOS= mysqli_fetch_assoc($Articulos))
	{		
		$ArtId=$DATOS['id'];
		$ArtTitulo=$DATOS['Titulo'];
		$ArtIntroduccion=$DATOS['Introduccion'];
		if(!$ArtTexto=$DATOS['Texto'])
			$ArtTexto=$ArtIntroduccion;
		$ArtFecha=$DATOS['Fecha'];
?> 	
<div data-role="page" id="Articulo<?=$ArtId;?>">
	<div data-role="header">
		<h1><?=$ArtTitulo;?></h1>
	</div>
	<div data-role="content">	
		<?=$ArtTexto;?>	 
	</div>
	<div data-role="footer">
		<h4>Page Footer</h4>
	</div>
</div>		
<?php		
	}
?>   



</body>
</html>