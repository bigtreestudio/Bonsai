<?php 

if ($borrarCrearTabla)
{
	$erroren="";
	$query = "DROP TABLE IF EXISTS `".$arregloCampos['tabla']."`;";	
	$conexion->_EjecutarQuery($query,$erroren,$resultado,$errno);
	
	
	$query = "CREATE TABLE IF NOT EXISTS `".$arregloCampos['tabla']."` (";	
	$query .= "`".$arregloCampos['codigo']."` int(11) unsigned NOT NULL,";	
	$query .= "`multimediaconjuntocod` smallint(2) unsigned NOT NULL,";	
	$query .= "`multimediacod` int(11) unsigned NOT NULL,";	
	$query .= "`".$arregloCampos['titulo']."` varchar(255) DEFAULT NULL,";	
	$query .= "`".$arregloCampos['descripcion']."` text,";	
	$query .= "`".$arregloCampos['home']."` tinyint(1) DEFAULT NULL,";	
	$query .= "`".$arregloCampos['orden']."` int(10) NOT NULL,";	
	$query .= "`multimediacodpreview` int(11) NULL,";	
	$query .= "`usuariodioalta` int(11) NOT NULL,";	
	$query .= "`".$arregloCampos['fAlta']."` datetime NOT NULL,";	
	$query .= "`ultmodusuario` int(11) NOT NULL,";	
	$query .= "`ultmodfecha` datetime NOT NULL,";	
	$query .= "PRIMARY KEY (`".$arregloCampos['codigo']."`,`multimediaconjuntocod`,`multimediacod`),";	
	$query .= "KEY `multimediacod` (`multimediacod`),";	
	$query .= "KEY `FK_not_conjunto` (`multimediaconjuntocod`)";	
	$query .= ") ENGINE=InnoDB DEFAULT CHARSET=latin1;";	
	$erroren="";
	
	$conexion->_EjecutarQuery($query,$erroren,$resultado,$errno);
	
	$query = "ALTER TABLE `".$arregloCampos['tabla']."` ";	
	$query .= "ADD CONSTRAINT `FK_multimedia_".$arregloCampos['tabla']."` FOREIGN KEY (`multimediacod`) REFERENCES `mul_multimedia` (`multimediacod`),";	
	$query .= "ADD CONSTRAINT `FK_conjunto_".$arregloCampos['tabla']."` FOREIGN KEY (`multimediaconjuntocod`) REFERENCES `mul_multimedia_conjuntos` (`multimediaconjuntocod`),";	
	$query .= "ADD CONSTRAINT `FK_".$tablaRelacion."` FOREIGN KEY (`".$arregloCampos['codigo']."`) REFERENCES `".$tablaRelacion."` (`".$arregloCampos['codigo']."`);";	
	$erroren="";
	$conexion->_EjecutarQuery($query,$erroren,$resultado,$errno);

}

?>