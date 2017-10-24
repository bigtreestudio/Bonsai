<?php 
class generadorDatosFront 
{

	protected $conexion;
	protected $carpetaPaquete;
	protected $CarpetaSql;
	protected $generarSitio;
	protected $generarPaquete;
	protected $nameFileSql = "query_front.sql";
	protected $fileWp;

	public function __construct($conexion,$carpetaPaquete,$CarpetaSql,$generarSitio=true,$generarPaquete=true){
		$this->conexion = &$conexion;
		$this->carpetaPaquete = $carpetaPaquete;
		$this->CarpetaSql = $CarpetaSql;
		$this->generarSitio = $generarSitio;
		$this->generarPaquete = $generarPaquete;

		if($this->generarPaquete)
		{
			if (!is_dir($this->CarpetaSql))
				mkdir($this->CarpetaSql);
			if (!is_dir($this->carpetaPaquete))
				mkdir($this->carpetaPaquete);
		}
		
    } 
	
	// Destructor de la clase
	public function __destruct() {	

    } 	


	public function GenerarArchivo($arregloCamposTabla,$arregloCampos,$arregloSp,$nombreClase)
	{
		$oStored=new cStoredFront($this->conexion);
		$ClassPHP = "<?php \n";	
		$ClassPHP .= "abstract class ".$nombreClase."db\n";
		$ClassPHP .= "{\n\r\n\r";
		
		$ClassPHP .= "\tfunction __construct(){}\n\r";
		$ClassPHP .= "\tfunction __destruct(){}\n\r";
		$this->RemoveAndOpenFile();
		$this->GenerarSqlSpTablaExternaBD($this->conexion,$oStored,$arregloSp);
		$this->SpTablaExternaBD($arregloSp,$ClassPHP);


		
		//$this->GenerarSqlBuscarxCodigo($this->conexion,$oStored,$arregloCampos);
		//$this->BuscarxCodigo($arregloCampos,$ClassPHP);
		if($arregloCampos['FrontListado']=="1"){
			$this->GenerarSqlBusquedaAvanzada($this->conexion,$oStored,$arregloCampos);
			$this->BusquedaAvanzada($arregloCampos,$ClassPHP);
		}
		
		$ClassPHP .= "\n\r\n\r}\n?>";
		
		
		if($this->generarSitio && FuncionesPHPLocal::GuardarArchivo(DOCUMENT_ROOT."Datos/",$ClassPHP,$nombreClase.".db.php"))
		{
			$msgactualizacion = "Se ha publicado correctamente.";
			$ret['IsSuccess'] = true;
		}
		if($this->generarPaquete && FuncionesPHPLocal::GuardarArchivo($this->carpetaPaquete,$ClassPHP,$nombreClase.".db.php"))
		{
			$msgactualizacion = "Se ha publicado correctamente.";
			$ret['IsSuccess'] = true;
		}
		$this->CloseFile();
	}


	public function GenerarSqlSpTablaExternaBD($conexion,$oStored,$arregloSp)
	{
	
		foreach($arregloSp as $tableFk)
		{
			$tabla = $tableFk['tabla'];
			$fk = $tableFk['fk'];
			$desc = $tableFk['desc'];
			$estado = $tableFk['estado'];
	
			$spnombre="sel_".$tabla."_combo_".$desc;
			$datos['spnombre'] = $spnombre;
			if(!$oStored->Buscar ($datos,$numfilas,$resultado))
				return false;
			if ($numfilas==1)
			{
				$datosStored = $conexion->ObtenerSiguienteRegistro($resultado);
				if(!$oStored->Eliminar ($datosStored))
					return false;
			}	
		
			$sql = "SELECT * FROM ".$tabla;
			if($estado!="")
				$sql .= " WHERE ".$estado." =10";
			
			$datos['spoperacion'] = "SEL";
			$datos['sptabla'] = strtoupper($tabla);
			$datos['spsqlstring'] = $sql;
			$datos['spobserv'] = "NULL";
			if(!$oStored->Insertar ($datos,$codigoinsertado))
				return false;
			$this->InsertGenerateArchivo($datos['spnombre'],$datos['spoperacion'],$datos['sptabla'],$datos['spsqlstring']);
		}
		
	}
	
	public function SpTablaExternaBD($arregloSp,&$ClassPHP)
	{
		
		foreach($arregloSp as $tableFk)
		{
			$tabla = $tableFk['tabla'];
			$fk = $tableFk['fk'];
			$desc = $tableFk['desc'];
			$NombreFuncion = $tabla."SP";
			$ClassPHP.="\tprotected function ".$NombreFuncion."(&\$spnombre,&\$sparam)\n";
			$ClassPHP.="\t{\n";
			$ClassPHP.="\t\t\$spnombre=\"sel_".$tabla."_combo_".$desc."\";\n";
			$ClassPHP.="\t\t\$sparam=array(\n";
			$ClassPHP.="\t\t);\n";			
			$ClassPHP.="\t\treturn true;\n";
			$ClassPHP.="\t}\n\r\n\r\n\r";
		}
	}
	
	
	public function BuscarxCodigo($arregloCampos,&$ClassPHP)
	{
		$ClassPHP.="\tprotected function BuscarxCodigo(\$datos,&\$resultado,&\$numfilas)\n";
		$ClassPHP.="\t{\n";
		$ClassPHP.="\t\t\$spnombre=\"sel_".$arregloCampos['tabla']."_x".$arregloCampos['codigo']."\";\n";
		$ClassPHP.="\t\t\$sparam=array(\n";
		$ClassPHP.="\t\t\t'p".$arregloCampos['codigo']."'=> \$datos['".$arregloCampos['codigo']."']\n";
		$ClassPHP.="\t\t);\n";			
		$ClassPHP.="\t\tif(!\$this->conexion->ejecutarStoredProcedure(\$spnombre,\$sparam,\$resultado,\$numfilas,\$errno) )\n";
		$ClassPHP.="\t\t{\n";
		$ClassPHP.="\t\t\tFuncionesPHPLocal::MostrarMensaje(\$this->conexion,MSG_ERRGRAVE,\"Error al buscar al buscar por codigo. \",array(\"archivo\" => __FILE__,\"funcion\" => __FUNCTION__, \"linea\" => __LINE__),array(\"formato\"=>\$this->formato));\n";
		$ClassPHP.="\t\t\treturn false;\n";
		$ClassPHP.="\t\t}\n\r";
		$ClassPHP.="\t\treturn true;\n";
		$ClassPHP.="\t}\n\r\n\r\n\r";
			
		return true;	
	}
	
	
	public function GenerarSqlBusquedaAvanzada($conexion,$oStored,$arregloCampos)
	{
		$spnombre="sel_".$arregloCampos['tabla']."_busqueda_avanzada";
		$datos['spnombre'] = $spnombre;
		if(!$oStored->Buscar ($datos,$numfilas,$resultado))
			return false;
		if ($numfilas==1)
		{
			$datosStored = $conexion->ObtenerSiguienteRegistro($resultado);
			if(!$oStored->Eliminar ($datosStored))
				return false;
		}	
		$letra = "a";
		$sql = "SELECT a.*";
		foreach($arregloCampos['camposconCombo'] as $clave=>$tabla)
		{
			$letra++;
			$sql .= ", ".$letra.".".$tabla['desc']." as ".$clave."desc";

		}
		$sql .= " FROM ".$arregloCampos['tabla']." AS a \n";
		$cantidad = count($arregloCampos['camposBusquedaAvanzada']);
		$letra = "a";
		
		foreach($arregloCampos['camposconCombo'] as $clave=>$tabla)
		{
			$letra++;
			$sql .= "LEFT JOIN ".$tabla['tabla']." AS ".$letra." ON a.".$clave." = ".$letra.".".$tabla['fk']." \n";
		}
		if ($cantidad>0)
			$sql .= "WHERE \n";
		$i = 1;	
		
	
		foreach($arregloCampos['camposBusquedaAvanzada'] as $Campos)
		{
			
			$buscacampoestado = false;
			if($Campos==$arregloCampos['campoEstado'])
				$buscacampoestado = true;	
			
			if ($arregloCampos['otroscampos']['busquedaavanzada_'.$Campos]==0)
				$sql.="IF(\"#px".$Campos."#\",a.".$Campos."=\"#p".$Campos."#\",1)";
			elseif($arregloCampos['otroscampos']['busquedaavanzada_'.$Campos]==1)
				$sql.="IF(\"#px".$Campos."#\", LCASE(a.".$Campos.") LIKE LCASE(\"%#p".$Campos."#%\"),1)";		
			elseif($arregloCampos['otroscampos']['busquedaavanzada_'.$Campos]==2)	
				$sql.="IF(\"#px".$Campos."#\",a.".$Campos." IN (#p".$Campos."#),1)";
			$sql .= " \n";	
			if ($i<$cantidad)
				$sql .= "AND \n";
			$i++;	
		}
		if($arregloCampos['tieneEstado']==1 && !$buscacampoestado)
		{
			$sql .= "AND \n";
			$sql.="IF(\"#px".$arregloCampos['campoEstado']."#\",a.".$arregloCampos['campoEstado']." IN (#p".$arregloCampos['campoEstado']."#),1)";
			$sql .= " \n";
			
		}
		
		
		$sql .= "ORDER BY #porderby# #plimit#";
	
	
		$datos['spoperacion'] = "SEL";
		$datos['sptabla'] = strtoupper($arregloCampos['tabla']);
		$datos['spsqlstring'] = $sql;
		$datos['spobserv'] = "NULL";
		if(!$oStored->Insertar ($datos,$codigoinsertado))
			return false;
			
		$this->InsertGenerateArchivo($datos['spnombre'],$datos['spoperacion'],$datos['sptabla'],$datos['spsqlstring']);
	}
	
	public function BusquedaAvanzada($arregloCampos,&$ClassPHP)
	{
		$ClassPHP.="\tprotected function BusquedaAvanzada(\$datos,&\$resultado,&\$numfilas)\n";
		$ClassPHP.="\t{\n";
		$ClassPHP.="\t\t\$spnombre=\"sel_".$arregloCampos['tabla']."_busqueda_avanzada\";\n";
		
		$ClassPHP.="\t\t\$sparam=array(\n";
		foreach($arregloCampos['camposBusquedaAvanzada'] as $Campos)
		{
			$buscacampoestado = false;
			if($Campos==$arregloCampos['campoEstado'])
				$buscacampoestado = true;	
			
			$ClassPHP.="\t\t\t'px".$Campos."'=> \$datos['x".$Campos."'],\n";
			$ClassPHP.="\t\t\t'p".$Campos."'=> \$datos['".$Campos."'],\n";
		}
		if($arregloCampos['tieneEstado']==1 && !$buscacampoestado)
		{
			$ClassPHP.="\t\t\t'px".$arregloCampos['campoEstado']."'=> \$datos['x".$arregloCampos['campoEstado']."'],\n";
			$ClassPHP.="\t\t\t'p".$arregloCampos['campoEstado']."'=> \$datos['".$arregloCampos['campoEstado']."'],\n";
		}
		$ClassPHP.="\t\t\t'plimit'=> \$datos['limit'],\n";
		$ClassPHP.="\t\t\t'porderby'=> \$datos['orderby']\n";
		$ClassPHP.="\t\t);\n\r";			
	
		$ClassPHP.="\t\tif(!\$this->conexion->ejecutarStoredProcedure(\$spnombre,\$sparam,\$resultado,\$numfilas,\$errno) )\n";
		$ClassPHP.="\t\t{\n";
		$ClassPHP.="\t\t\tFuncionesPHPLocal::MostrarMensaje(\$this->conexion,MSG_ERRGRAVE,\"Error al realizar la búsqueda avanzada. \",array(\"archivo\" => __FILE__,\"funcion\" => __FUNCTION__, \"linea\" => __LINE__),array(\"formato\"=>\$this->formato));\n";
		$ClassPHP.="\t\t\treturn false;\n";
		$ClassPHP.="\t\t}\n\r";
		$ClassPHP.="\t\treturn true;\n";
		$ClassPHP.="\t}\n\r\n\r\n\r";
			
		return true;	
	}
	
	
	
	public function RemoveAndOpenFile()
	{
		if($this->generarPaquete)
		{
			if (file_exists($this->CarpetaSql.$this->nameFileSql))
				unlink($this->CarpetaSql.$this->nameFileSql);
			 $this->fileWp = fopen($this->CarpetaSql.$this->nameFileSql,"ab+");
			 $this->InicioSql();
		}
		
	}
	
	public function InsertGenerateArchivo($spnombre,$spoperacion,$sptabla,$spsqlstring)
	{
  	  	if($this->generarPaquete)
		{
	   		$txtInsert = "INSERT INTO stored_procedures (spnombre, spoperacion, sptabla, spsqlstring, spobserv, ultmodusuario, ultmodfecha) VALUES ('".$spnombre."','".$spoperacion."','".$sptabla."','".$spsqlstring."','','1','2011-11-07 16:35:43');\n";
	   		fwrite($this->fileWp,$txtInsert);
		}
	}
	public function CloseFile()
	{
		if($this->generarPaquete)
		{
			$this->FinSql();
		 	fclose($this->fileWp);
		}
	}


	public function InicioSql()
	{
  	  if($this->generarPaquete)
		{
		   $txtInsert = "SET FOREIGN_KEY_CHECKS=0;\n";
		   $txtInsert .= "SET AUTOCOMMIT = 0;\n";
		   $txtInsert .= "START TRANSACTION;\n\n\n";
		   fwrite($this->fileWp,$txtInsert);
		}
	}
	
	public function FinSql()
	{
  	  if($this->generarPaquete)
		{
		   $txtInsert = "\n\n\nSET FOREIGN_KEY_CHECKS=1;\n";
		   $txtInsert .= "COMMIT;\n";
		   fwrite($this->fileWp,$txtInsert);
		}
	}
	
}

?>