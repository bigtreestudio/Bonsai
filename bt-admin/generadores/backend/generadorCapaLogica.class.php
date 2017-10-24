<?php 


class cGeneradorABMClases 
{
	protected $conexion;
	protected $carpetaPaquete;
	protected $generarSitio;
	protected $generarPaquete;

	public function __construct($conexion,$carpetaPaquete,$generarSitio=true,$generarPaquete=true){
		$this->conexion = &$conexion;
		$this->carpetaPaquete = $carpetaPaquete;
		$this->generarSitio = $generarSitio;
		$this->generarPaquete = $generarPaquete;
		
		if($this->generarPaquete)
		{
			if (!is_dir($this->carpetaPaquete))
				mkdir($this->carpetaPaquete);
		}
    } 
	
	// Destructor de la clase
	public function __destruct() {	

    } 	


	public function GenerarArchivo($arregloCamposTabla,$arregloCampos,$arregloSp,$nombreClase)
	{

		$ClassPHP = "<?php \n";	
		
		$ClassPHP .= "include(DIR_CLASES_DB.\"".$nombreClase.".db.php\");\n\r";
		$ClassPHP .= "class ".$nombreClase." extends ".$nombreClase."db\n";
		$ClassPHP .= "{\n\r";
		
		$ClassPHP .= "\tprotected \$conexion;\n";
		$ClassPHP .= "\tprotected \$formato;\n\r";
		
		$ClassPHP .= "\tfunction __construct(\$conexion,\$formato=FMT_TEXTO){\n";
		$ClassPHP .= "\t\t\$this->conexion = &\$conexion;\n";
		$ClassPHP .= "\t\t\$this->formato = &\$formato;\n";
		$ClassPHP .= "\t\tparent::__construct();\n";
		$ClassPHP .= "\t}\n\r";
		
		$ClassPHP .= "\tfunction __destruct(){parent::__destruct();}\n\r";
		
		$this->ClassBuscarxCodigo($arregloCampos,$ClassPHP);
		
		if ($arregloCampos['tieneBusquedaAvanzada']){
			$this->ClassBusquedaAvanzada($arregloCamposTabla,$arregloCampos,$ClassPHP);
		}
		
		
		$this->ClassSpTablaExterna($arregloSp,$ClassPHP);
		
		$this->ClassInsertar($arregloCamposTabla,$arregloCampos,$ClassPHP);
		$this->ClassModificar($arregloCamposTabla,$arregloCampos,$ClassPHP);
		$this->ClassEliminar($arregloCampos,$ClassPHP);
		
		
		if ($arregloCampos['tieneEstado']==1)
		{
			$this->ClassModificarEstado($arregloCampos,$ClassPHP);
		}
		
		if ($arregloCampos['tieneActivarDesactivar']==1 && $arregloCampos['tieneEstado']==1)
		{
			$this->ClassActivarEstado($arregloCampos,$ClassPHP);
			$this->ClassDesActivarEstado($arregloCampos,$ClassPHP);
		}
		
		if ((isset($arregloCampos['JsonListado']) && $arregloCampos['JsonListado']==1) || (isset($arregloCampos['JsonCodigo']) && $arregloCampos['JsonCodigo']==1))
		{
			$this->ClassPublicar($arregloCampos,$ClassPHP);
			$this->ClassGuardarDatosJson($arregloCampos,$ClassPHP);
			$this->ClassEliminarDatosJson($arregloCampos,$ClassPHP);
			if(isset($arregloCampos['JsonListado']) && $arregloCampos['JsonListado']==1) 
			{
				$this->ClassPublicarJsonListado($arregloCampos,$ClassPHP);	
				$this->ClassGerenarArrayDatosJsonListado($arregloCampos,$ClassPHP);	
			}
			if(isset($arregloCampos['JsonCodigo']) && $arregloCampos['JsonCodigo']==1)
			{
				$this->ClassPublicarJsonPorCodigo($arregloCampos,$ClassPHP);
				$this->ClassGerenarArrayDatosJsonxCodigo($arregloCampos,$ClassPHP);	
			}
		}
		if ($arregloCampos['TieneMultimedia'] || $arregloCampos['TieneClaseMultimedia'])
			$this->ClassGenerarDatosMultimedia($arregloCampos,$ClassPHP);
		
		if ($arregloCampos['tieneOrden']){
		
			$this->ClassModificarOrden($arregloCampos,$ClassPHP);
			$this->ClassObtenerProximoOrden($arregloCampos,$ClassPHP);
		}
		
		
		$ClassPHP .="\n\n//-----------------------------------------------------------------------------------------\n";
		$ClassPHP .="//FUNCIONES PRIVADAS\n";
		$ClassPHP .="//-----------------------------------------------------------------------------------------\n\n";
		
		
		$this->ClassValidarInsertar($arregloCampos,$ClassPHP);
		$this->ClassValidarModificar($arregloCampos,$ClassPHP);
		$this->ClassValidarEliminar($arregloCampos,$ClassPHP);
		$this->ClassSetearNull($arregloCamposTabla,$arregloCampos,$ClassPHP);
		$this->ClassValidarDatosVacios($arregloCamposTabla,$arregloCampos,$arregloSp,$ClassPHP);
		
		
		
		$ClassPHP .= "\n\r\n\r}\n?>";
		
		
		if($this->generarSitio && FuncionesPHPLocal::GuardarArchivo(DIR_CLASES_LOGICA,$ClassPHP,$nombreClase.".class.php"))
		{
			$msgactualizacion = "Se ha publicado correctamente.";
			$ret['IsSuccess'] = true;
		}
		
		
		if($this->generarPaquete && FuncionesPHPLocal::GuardarArchivo($this->carpetaPaquete,$ClassPHP,$nombreClase.".class.php"))
		{
			$msgactualizacion = "Se ha publicado correctamente.";
			$ret['IsSuccess'] = true;
		}

	}


	public function ClassBuscarxCodigo($arregloCampos,&$ClassPHP)
	{
		$ClassPHP.="\tpublic function BuscarxCodigo(\$datos,&\$resultado,&\$numfilas)\n";
		$ClassPHP.="\t{\n";
		$ClassPHP.="\t\tif (!parent::BuscarxCodigo(\$datos,\$resultado,\$numfilas))\n";
		$ClassPHP.="\t\t\treturn false;\n";
		$ClassPHP.="\t\treturn true;\n";
		$ClassPHP.="\t}\n\r\n\r\n\r";		
			
		return true;	
	}
	
	
	public function ClassSpTablaExterna($arregloSp,&$ClassPHP)
	{
		
		foreach($arregloSp as $tableFk)
		{
			$tabla = $tableFk['tabla'];
			$fk = $tableFk['fk'];
			$desc = $tableFk['desc'];
			$NombreFuncion = $tabla."SP";
			$ClassPHP.="\tpublic function ".$NombreFuncion."(&\$spnombre,&\$sparam)\n";
			$ClassPHP.="\t{\n";
			$ClassPHP.="\t\tif (!parent::".$NombreFuncion."(\$spnombre,\$sparam))\n";
			$ClassPHP.="\t\t\treturn false;\n";
			$ClassPHP.="\t\treturn true;\n";
			$ClassPHP.="\t}\n\r\n\r\n\r";		
			$ClassPHP.="\tpublic function ".$NombreFuncion."Result(&\$resultado,&\$numfilas)\n";
			$ClassPHP.="\t{\n";
			$ClassPHP.="\t\tif (!\$this->".$NombreFuncion."(\$spnombre,\$sparam))\n";
			$ClassPHP.="\t\t\treturn false;\n\n";
			$ClassPHP.="\t\tif(!\$this->conexion->ejecutarStoredProcedure(\$spnombre,\$sparam,\$resultado,\$numfilas,\$errno) )\n";
			$ClassPHP.="\t\t{\n";
			$ClassPHP.="\t\t\tFuncionesPHPLocal::MostrarMensaje(\$this->conexion,MSG_ERRGRAVE,\"Error al buscar por codigo. \",array(\"archivo\" => __FILE__,\"funcion\" => __FUNCTION__, \"linea\" => __LINE__),array(\"formato\"=>\$this->formato));\n";
			$ClassPHP.="\t\t\treturn false;\n";
			$ClassPHP.="\t\t}\n\r";
			$ClassPHP.="\t\treturn true;\n";
			$ClassPHP.="\t}\n\r\n\r\n\r";		
		}
	}
	
	public function ClassInsertar($arregloCamposTabla,$arregloCampos,&$ClassPHP)
	{
		$ClassPHP.="\tpublic function Insertar(\$datos,&\$codigoinsertado)\n";
		$ClassPHP.="\t{\n";
		$ClassPHP.="\t\tif (!\$this->_ValidarInsertar(\$datos))\n";
		$ClassPHP.="\t\t\treturn false;\n\r";
	
		
		$tinymceAvanzado=false;
		$arregloCampoTiny = array();
		foreach($arregloCampos['otroscampos']['campoalta'] as $Campos)
		{
			if ($arregloCampos['otroscampos']['camposaltatipo_'.$Campos]==3)
			{
				$tinymceAvanzado=true;
				$arregloCampoTiny[$Campos] = $Campos;
			}
		}
		
		if($tinymceAvanzado)
		{
			foreach($arregloCampoTiny as $CampoTiny)
			{
				$ClassPHP.="\t\t\$datos['".$CampoTiny."'] = preg_replace(\"/<p[^>]*><\\/p[^>]*>/\",'<div class=\"space\">&nbsp;</div>',\$datos['".$CampoTiny."']);\n\n";
		
				$ClassPHP.="\t\t\$oProcesarCuerpo = new cProcesarCuerpo(\$this->conexion,\$this->formato);\n\n";
				$ClassPHP.="\t\t\$cuerpo = \$datos['".$CampoTiny."'];\n";	
				$ClassPHP.="\t\t\$cuerpo = \$oProcesarCuerpo->reemplazarFrasesWide(\$cuerpo);\n";
				$ClassPHP.="\t\t\$datos['".$CampoTiny."procesado'] = \$cuerpo;\n\n";
			
			}
		}
		
		
		if ($arregloCampos['tieneOrden'])
		{
			$ClassPHP.="\t\t\$this->ObtenerProximoOrden(\$datos,\$proxorden);\n";
			$ClassPHP.="\t\t\$datos['".$arregloCampos['campoOrden']."'] = \$proxorden;\n";
		}
	
		foreach($arregloCamposTabla as $Campos)
		{
			if ($Campos['Field']!="ultmodfecha")
			{
				if (substr($Campos['Type'],0,4)=="date" || substr($Campos['Type'],0,8)=="datetime")
					$ClassPHP.="\t\t\$datos['".$Campos['Field']."']=FuncionesPHPLocal::ConvertirFecha( \$datos['".$Campos['Field']."'],'dd/mm/aaaa','aaaa-mm-dd');\n";
			}
		}
	
		if ($arregloCampos['tieneEstado'])
			$ClassPHP.="\t\t\$datos['".$arregloCampos['campoEstado']."'] = ACTIVO;\n";
	
		$ClassPHP.="\t\t\$this->_SetearNull(\$datos);\n";
		$ClassPHP.="\t\tif (!parent::Insertar(\$datos,\$codigoinsertado))\n";
		$ClassPHP.="\t\t\treturn false;\n";
		if ((isset($arregloCampos['JsonListado']) && $arregloCampos['JsonListado']==1) ||(isset($arregloCampos['JsonCodigo']) && $arregloCampos['JsonCodigo']==1))
		{
			$ClassPHP.="\t\t\$datos['".$arregloCampos['codigo']."'] =\$codigoinsertado;\n";
			$ClassPHP.="\t\tif (!\$this->Publicar(\$datos))\n";
			$ClassPHP.="\t\t\treturn false;\n\r";
		}
		$ClassPHP.="\t\treturn true;\n";
		$ClassPHP.="\t}\n\r\n\r\n\r";
			
		return true;	
	}
	
	
	
	public function ClassModificar($arregloCamposTabla,$arregloCampos,&$ClassPHP)
	{
		$ClassPHP.="\tpublic function Modificar(\$datos)\n";
		$ClassPHP.="\t{\n";
		$ClassPHP.="\t\tif (!\$this->_ValidarModificar(\$datos))\n";
		$ClassPHP.="\t\t\treturn false;\n\r";
		foreach($arregloCamposTabla as $Campos)
		{
			if ($Campos['Field']!="ultmodfecha")
			{
				if (substr($Campos['Type'],0,4)=="date" || substr($Campos['Type'],0,8)=="datetime")
					$ClassPHP.="\t\t\$datos['".$Campos['Field']."']=FuncionesPHPLocal::ConvertirFecha( \$datos['".$Campos['Field']."'],'dd/mm/aaaa','aaaa-mm-dd');\n";
			}
		}
		
		$tinymceAvanzado=false;
		$arregloCampoTiny = array();
		foreach($arregloCampos['otroscampos']['campoalta'] as $Campos)
		{
			if ($arregloCampos['otroscampos']['camposaltatipo_'.$Campos]==3)
			{
				$tinymceAvanzado=true;
				$arregloCampoTiny[$Campos] = $Campos;
			}
		}
		
		if($tinymceAvanzado)
		{
			foreach($arregloCampoTiny as $CampoTiny)
			{
				$ClassPHP.="\t\t\$datos['".$CampoTiny."'] = preg_replace(\"/<p[^>]*><\\/p[^>]*>/\",'<div class=\"space\">&nbsp;</div>',\$datos['".$CampoTiny."']);\n\n";
		
				$ClassPHP.="\t\t\$erroren = \"\";\n";
				$ClassPHP.="\t\t\$oProcesarCuerpo = new cProcesarCuerpo(\$this->conexion,\$this->formato);\n\n";
				if($arregloCampos['TieneClaseMultimedia'])
				{
					$ClassPHP.="\t\t\$datosTamanio = array();\n\n";
					
					$ClassPHP.="\t\t\$datosTamanio['fotoizq'] = TAMANIOPAGFOTOI;\n";
					$ClassPHP.="\t\t\$datosTamanio['fotoder'] = TAMANIOPAGFOTOD;\n";
					$ClassPHP.="\t\t\$datosTamanio['fotocen'] = TAMANIOPAGFOTOC;\n\n";
			
					$ClassPHP.="\t\t\$datosTamanio['videoizq'] = TAMANIOPAGVIDEOI;\n";
					$ClassPHP.="\t\t\$datosTamanio['videoder'] = TAMANIOPAGVIDEOD;\n";
					$ClassPHP.="\t\t\$datosTamanio['videocen'] = TAMANIOPAGVIDEOC;\n";
					$ClassPHP.="\t\t\$datosTamanio['videoalto'] = TAMANIOPAGVIDEOALTO;\n";
					$ClassPHP.="\t\t\$oProcesarCuerpo->SetearTamanios(\$datosTamanio);\n\n";
			
					$ClassPHP.="\t\t\$sql = \"SELECT a.multimediaconjuntocod, a.".$arregloCampos['PrefijoMultimedia']."multimediatitulo, b.*, c.multimediacatcarpeta FROM ".$arregloCampos['tabla']."_mul_multimedia AS a INNER JOIN mul_multimedia AS b ON a.multimediacod=b.multimediacod\n"; 
					$ClassPHP.="\t\tINNER JOIN mul_multimedia_categorias AS c ON b.multimediacatcod=c.multimediacatcod\n"; 
					$ClassPHP.="\t\tWHERE ".$arregloCampos['codigo']."=\".\$datos['".$arregloCampos['codigo']."'].\" AND (a.".$arregloCampos['PrefijoMultimedia']."multimediamuestrahome=0 OR a.".$arregloCampos['PrefijoMultimedia']."multimediamuestrahome IS NULL) ORDER BY a.multimediaconjuntocod ASC, ".$arregloCampos['PrefijoMultimedia']."multimediaorden ASC\";\n\n";		
					
					$ClassPHP.="\t\t\$this->conexion->_EjecutarQuery(\$sql,\$erroren,\$resultado,\$errno);\n";
					$ClassPHP.="\t\t\$numfilas = \$this->conexion->ObtenerCantidadDeRegistros(\$resultado);\n\n";
					$ClassPHP.="\t\t\$datosImagenes = array();\n";
					$ClassPHP.="\t\t\$datosVideos = array();\n";
					$ClassPHP.="\t\t\$datosAudios = array();\n";
					$ClassPHP.="\t\tif(\$numfilas>0)\n";
					$ClassPHP.="\t\t{\n";
					$ClassPHP.="\t\t\twhile(\$multimedia = \$this->conexion->ObtenerSiguienteRegistro(\$resultado))\n";
					$ClassPHP.="\t\t\t{\n";
					$ClassPHP.="\t\t\t\tif(\$multimedia['multimediaconjuntocod']==FOTOS)\n";
					$ClassPHP.="\t\t\t\t{\n";
					$ClassPHP.="\t\t\t\t\t\$urlImagen = \$multimedia['multimediacatcarpeta'].\"N/\".\$multimedia['multimediaubic'];\n";
					$ClassPHP.="\t\t\t\t\t\$datosCarga['ubicacion'] = \$urlImagen;\n";
					$ClassPHP.="\t\t\t\t\t\$datosCarga['epigrafe'] = \$multimedia['metodopagomultimediatitulo'];\n";
					$ClassPHP.="\t\t\t\t\t\$datosImagenes[] = \$datosCarga;\n";
					$ClassPHP.="\t\t\t\t}\n";
					$ClassPHP.="\t\t\t\tif(\$multimedia['multimediaconjuntocod']==VIDEOS)\n";
					$ClassPHP.="\t\t\t\t{\n";
					$ClassPHP.="\t\t\t\t\$datosCarga['multimediacod'] = \$multimedia['multimediacod'];\n";
					$ClassPHP.="\t\t\t\t\$datosCarga['epigrafe'] = \$multimedia['metodopagomultimediatitulo'];\n";
					$ClassPHP.="\t\t\t\t\$datosVideos[] = \$datosCarga;\n";
					$ClassPHP.="\t\t\t\t}\n\n";
							
					$ClassPHP.="\t\t\t\tif(\$multimedia['multimediaconjuntocod']==AUDIOS)\n";
					$ClassPHP.="\t\t\t\t\t\$datosAudios[] = \$multimedia;\n\n";
			
					$ClassPHP.="\t\t\t}\n";
					$ClassPHP.="\t\t}\n";
					$ClassPHP.="\t\t\$cuerpo = \$datos['".$CampoTiny."'];\n";
					$ClassPHP.="\t\t\$cuerpo = \$oProcesarCuerpo->ProcesarImagenesCuerpo(\$datosImagenes,\$cuerpo);\n";
					$ClassPHP.="\t\t\$cuerpo = \$oProcesarCuerpo->ProcesarVideosCuerpo(\$datosVideos,\$cuerpo);\n";
				}
				else
				{
					$ClassPHP.="\t\t\$cuerpo = \$datos['".$CampoTiny."'];\n";	
				}
				$ClassPHP.="\t\t\$cuerpo = \$oProcesarCuerpo->reemplazarFrasesWide(\$cuerpo);\n";
		
				$ClassPHP.="\t\t\$datos['".$CampoTiny."procesado'] = \$cuerpo;\n\n";
			}
		}
		
		$ClassPHP.="\t\t\$this->_SetearNull(\$datos);\n";
		$ClassPHP.="\t\tif (!parent::Modificar(\$datos))\n";
		$ClassPHP.="\t\t\treturn false;\n\r";
		if ((isset($arregloCampos['JsonListado']) && $arregloCampos['JsonListado']==1) || (isset($arregloCampos['JsonCodigo']) && $arregloCampos['JsonCodigo']==1))
		{
			$ClassPHP.="\t\tif (!\$this->Publicar(\$datos))\n";
			$ClassPHP.="\t\t\treturn false;\n\r";
		}
		$ClassPHP.="\t\treturn true;\n";
		$ClassPHP.="\t}\n\r\n\r\n\r";
			
		return true;	
	}
	
	function ClassEliminar($arregloCampos,&$ClassPHP)
	{
		$ClassPHP.="\tpublic function Eliminar(\$datos)\n";
		$ClassPHP.="\t{\n";
		$ClassPHP.="\t\tif (!\$this->_ValidarEliminar(\$datos))\n";
		$ClassPHP.="\t\t\treturn false;\n\r";
		if($arregloCampos['TipoEliminacion']==0)
		{
			$ClassPHP.="\t\tif (!parent::Eliminar(\$datos))\n";
			$ClassPHP.="\t\t\treturn false;\n\r";
			if ((isset($arregloCampos['JsonListado']) && $arregloCampos['JsonListado']==1) || (isset($arregloCampos['JsonCodigo']) && $arregloCampos['JsonCodigo']==1))
			{
				$ClassPHP.="\t\tif (!\$this->Publicar(\$datos))\n";
				$ClassPHP.="\t\t\treturn false;\n\r";
			}
		}
		else
		{
			$ClassPHP.="\t\t\$datosmodif['".$arregloCampos['codigo']."'] = \$datos['".$arregloCampos['codigo']."'];\n";
			$ClassPHP.="\t\t\$datosmodif['".$arregloCampos['campoEstado']."'] = ELIMINADO;\n";
			$ClassPHP.="\t\tif (!\$this->ModificarEstado(\$datosmodif))\n";
			$ClassPHP.="\t\t\treturn false;\n";
	
		}
		
		$ClassPHP.="\t\treturn true;\n";
		$ClassPHP.="\t}\n\r\n\r\n\r";
			
		return true;	
	}
	
	
	public function ClassValidarInsertar($arregloCampos,&$ClassPHP)
	{
		$ClassPHP.="\tprivate function _ValidarInsertar(\$datos)\n";
		$ClassPHP.="\t{\n";
		$ClassPHP.="\t\tif (!\$this->_ValidarDatosVacios(\$datos))\n";
		$ClassPHP.="\t\t\treturn false;\n\r";
	
		$ClassPHP.="\t\treturn true;\n";
		$ClassPHP.="\t}\n\r\n\r\n\r";
			
		return true;	
	}
	
	public function ClassValidarModificar($arregloCampos,&$ClassPHP)
	{
		$ClassPHP.="\tprivate function _ValidarModificar(\$datos)\n";
		$ClassPHP.="\t{\n";
		$ClassPHP.="\t\tif (!\$this->BuscarxCodigo(\$datos,\$resultado,\$numfilas))\n";
		$ClassPHP.="\t\t\treturn false;\n\r";
		$ClassPHP.="\n\t\tif (\$numfilas!=1)\n";
		$ClassPHP.="\t\t{\n";
		$ClassPHP.="\t\t\tFuncionesPHPLocal::MostrarMensaje(\$this->conexion,MSG_ERRGRAVE,\"Error debe ingresar un codigo valido.\",array(\"archivo\" => __FILE__,\"funcion\" => __FUNCTION__, \"linea\" => __LINE__),array(\"formato\"=>\$this->formato));\n";
		$ClassPHP.="\t\t\treturn false;\n";
		$ClassPHP.="\t\t}\n";	
		$ClassPHP.="\t\tif (!\$this->_ValidarDatosVacios(\$datos))\n";
		$ClassPHP.="\t\t\treturn false;\n\r";
		$ClassPHP.="\t\treturn true;\n";
		$ClassPHP.="\t}\n\r\n\r\n\r";
			
		return true;	
	}
	
	
	public function ClassValidarEliminar($arregloCampos,&$ClassPHP)
	{
		$ClassPHP.="\tprivate function _ValidarEliminar(\$datos)\n";
		$ClassPHP.="\t{\n";
		$ClassPHP.="\t\tif (!\$this->BuscarxCodigo(\$datos,\$resultado,\$numfilas))\n";
		$ClassPHP.="\t\t\treturn false;\n\r";
		$ClassPHP.="\n\t\tif (\$numfilas!=1)\n";
		$ClassPHP.="\t\t{\n";
		$ClassPHP.="\t\t\tFuncionesPHPLocal::MostrarMensaje(\$this->conexion,MSG_ERRGRAVE,\"Error debe ingresar un c&oacute;digo valido.\",array(\"archivo\" => __FILE__,\"funcion\" => __FUNCTION__, \"linea\" => __LINE__),array(\"formato\"=>\$this->formato));\n";
		$ClassPHP.="\t\t\treturn false;\n";
		$ClassPHP.="\t\t}\n";	
		$ClassPHP.="\t\treturn true;\n";
		$ClassPHP.="\t}\n\r\n\r\n\r";
			
		return true;	
	}
	
	
	
	public function ClassValidarDatosVacios($arregloCamposTabla,$arregloCampos,$arregloSp, &$ClassPHP)
	{
		$ClassPHP.="\tprivate function _ValidarDatosVacios(\$datos)\n";
		$ClassPHP.="\t{\n\n";
		
		$tinymceAvanzado = false;
		$arregloCampoTiny = array();
		foreach($arregloCampos['otroscampos']['campoalta'] as $Campos)
		{
			
			if ($arregloCampos['otroscampos']['camposaltatipo_'.$Campos]==3 )
			{
				$tinymceAvanzado=true;
				$arregloCampoTiny[$Campos.'procesado'] = $Campos.'procesado';
			}
			
		}
		
		foreach ($arregloCamposTabla as $Campo)
		{
			if ($Campo['Field']!=$arregloCampos['codigo'] && $Campo['Field']!="ultmodfecha" && $Campo['Field']!="ultmodusuario" && $Campo['Field']!=$arregloCampos['campoOrden'] && $Campo['Field']!=$arregloCampos['campoEstado'])
			{
				if(isset($arregloCampos['otroscampos']['camposerrores_'.$Campo['Field']]))
				{
				
					if($tinymceAvanzado && isset($arregloCampoTiny[$Campo['Field']]))
					{	
						
					}
					else
					{
						$ClassPHP.="\n\t\tif (!isset(\$datos['".$Campo['Field']."']) || \$datos['".$Campo['Field']."']==\"\")\n";
						$ClassPHP.="\t\t{\n";
						$ClassPHP.="\t\t\tFuncionesPHPLocal::MostrarMensaje(\$this->conexion,MSG_ERRGRAVE,\"".$arregloCampos['otroscampos']['camposerrores_'.$Campo['Field']]."\",array(\"archivo\" => __FILE__,\"funcion\" => __FUNCTION__, \"linea\" => __LINE__),array(\"formato\"=>\$this->formato));\n";
						$ClassPHP.="\t\t\treturn false;\n";
						$ClassPHP.="\t\t}\n";
			
						if (substr($Campo['Type'],0,7)=="varchar")
						{
							/*
							$cantidadCampo = substr($Campo['Type'],8,strpos($Campo['Type'],")")-8);
							$ClassPHP.="\t\tif (strlen(\$datos['".$Campo['Field']."'])>".$cantidadCampo.")\n";
							$ClassPHP.="\t\t{\n";
							$ClassPHP.="\t\t\tFuncionesPHPLocal::MostrarMensaje(\$this->conexion,MSG_ERRGRAVE,\"Error debe ingresar un .\",array(\"archivo\" => __FILE__,\"funcion\" => __FUNCTION__, \"linea\" => __LINE__),array(\"formato\"=>\$this->formato));\n";
							$ClassPHP.="\t\t\treturn false;\n";
							$ClassPHP.="\t\t}\n";*/
						}
					
						if (substr($Campo['Type'],0,8)=="smallint" || substr($Campo['Type'],0,3)=="int")
						{
							/*
							if (substr($Campo['Type'],0,8)=="smallint")
								$cantidadCampo = substr($Campo['Type'],9,strpos($Campo['Type'],")")-9);
							
							if (substr($Campo['Type'],0,3)=="int")	
								$cantidadCampo = substr($Campo['Type'],4,strpos($Campo['Type'],")")-4);
			
							$ClassPHP.="\t\tif (strlen(\$datos['".$Campo['Field']."'])>".$cantidadCampo.")\n";
							$ClassPHP.="\t\t{\n";
							$ClassPHP.="\t\t\tFuncionesPHPLocal::MostrarMensaje(\$this->conexion,MSG_ERRGRAVE,\"Error debe ingresar un .\",array(\"archivo\" => __FILE__,\"funcion\" => __FUNCTION__, \"linea\" => __LINE__),array(\"formato\"=>\$this->formato));\n";
							$ClassPHP.="\t\t\treturn false;\n";
							$ClassPHP.="\t\t}\n";*/
			
							$ClassPHP.="\t\tif (!FuncionesPHPLocal::ValidarContenido(\$this->conexion,\$datos['".$Campo['Field']."'],\"NumericoEntero\"))\n";
							$ClassPHP.="\t\t{\n";
							$ClassPHP.="\t\t\tFuncionesPHPLocal::MostrarMensaje(\$this->conexion,MSG_ERRGRAVE,\"Error debe ingresar un campo numerico.\",array(\"archivo\" => __FILE__,\"funcion\" => __FUNCTION__, \"linea\" => __LINE__),array(\"formato\"=>\$this->formato));\n";
							$ClassPHP.="\t\t\treturn false;\n";
							$ClassPHP.="\t\t}\n";
						}
			
						if (substr($Campo['Type'],0,4)=="date" || substr($Campo['Type'],0,8)=="datetime")
						{
							$ClassPHP.="\t\tif (!FuncionesPHPLocal::ValidarContenido(\$this->conexion,\$datos['".$Campo['Field']."'],\"FechaDDMMAAAA\"))\n";
							$ClassPHP.="\t\t{\n";
							$ClassPHP.="\t\t\tFuncionesPHPLocal::MostrarMensaje(\$this->conexion,MSG_ERRGRAVE,\"Error debe ingresar una fecha valida.\",array(\"archivo\" => __FILE__,\"funcion\" => __FUNCTION__, \"linea\" => __LINE__),array(\"formato\"=>\$this->formato));\n";
							$ClassPHP.="\t\t\treturn false;\n";
							$ClassPHP.="\t\t}\n";
						}
					}
			
				}
	
			}



			
		}
		
		foreach ($arregloCampos['otroscampos']['campoalta'] as $camposAlta){
			$campotipo = $arregloCampos['otroscampos']['camposaltatipo_'.$camposAlta];
			if ($campotipo==12)
			{
				$tabla = $arregloCampos['otroscampos']['tabla_'.$camposAlta];
				$fk = $arregloCampos['otroscampos']['campofk_'.$camposAlta];
				$desc = $arregloCampos['otroscampos']['campodesc_'.$camposAlta];
				$ClassPHP.="\n\t\tif (!\$this->conexion->TraerCampo('".$tabla."','".$fk."',array('".$fk."='.\$datos['".$camposAlta."']),\$dato,\$numfilas,\$errno))\n";
				$ClassPHP.="\t\t\treturn false;\n\n";
				$ClassPHP.="\n\t\tif (\$numfilas!=1)\n";
				$ClassPHP.="\t\t{\n";
				$ClassPHP.="\t\t\tFuncionesPHPLocal::MostrarMensaje(\$this->conexion,MSG_ERRGRAVE,\"Error debe ingresar un campo valido.\",array(\"archivo\" => __FILE__,\"funcion\" => __FUNCTION__, \"linea\" => __LINE__),array(\"formato\"=>\$this->formato));\n";
				$ClassPHP.="\t\t\treturn false;\n";
				$ClassPHP.="\t\t}\n";
			}
		}

		$ClassPHP.="\t\treturn true;\n";
		$ClassPHP.="\t}\n\r\n\r\n\r";
			
		return true;	
	}
	
	
	public function ClassSetearNull($arregloCamposTabla,$arregloCampos,&$ClassPHP)
	{
		$ClassPHP.="\tprivate function _SetearNull(&\$datos)\n";
		$ClassPHP.="\t{\n\n";
	
		foreach ($arregloCamposTabla as $Campo)
		{
			if ($Campo['Field']!=$arregloCampos['codigo'] && $Campo['Field']!="ultmodfecha" && $Campo['Field']!="ultmodusuario" && $Campo['Field']!=$arregloCampos['campoOrden'] && $Campo['Field']!=$arregloCampos['campoEstado'])
			{
				$ClassPHP.="\n\t\tif (!isset(\$datos['".$Campo['Field']."']) || \$datos['".$Campo['Field']."']==\"\")\n";
				$ClassPHP.="\t\t\t\$datos['".$Campo['Field']."']=\"NULL\";\n";
			}
			
		}
		$ClassPHP.="\t\treturn true;\n";
		$ClassPHP.="\t}\n\r\n\r\n\r";
			
		return true;	
	}
	
	
	
	
	public function ClassObtenerProximoOrden($arregloCampos,&$ClassPHP)
	{
		$ClassPHP.="\tprivate function ObtenerProximoOrden(\$datos,&\$proxorden)\n";
		$ClassPHP.="\t{\n";
		$ClassPHP.="\t\t\$proxorden = 0;\n";
		$ClassPHP.="\t\tif (!parent::BuscarUltimoOrden(\$datos,\$resultado,\$numfilas))\n";
		$ClassPHP.="\t\t\treturn false;\n\r";
		$ClassPHP.="\t\tif (\$numfilas!=0){\n";
		$ClassPHP.="\t\t\t\$datos = \$this->conexion->ObtenerSiguienteRegistro(\$resultado);\n";
		$ClassPHP.="\t\t\t\$proxorden = \$datos['maximo'] + 1;\n";
		$ClassPHP.="\t\t}\n";
		$ClassPHP.="\t\treturn true;\n";
		$ClassPHP.="\t}\n\r\n\r\n\r";
			
		return true;	
	}
	
	
	public function ClassModificarOrden($arregloCampos,&$ClassPHP)
	{
		$ClassPHP.="\tpublic function ModificarOrdenCompleto(\$datos)\n";
		$ClassPHP.="\t{\n";
		$ClassPHP.="\t\t\$datosmodif['".$arregloCampos['campoOrden']."'] = 1;\n";
		$ClassPHP.="\t\t\$arregloOrden = explode(\",\",\$datos['orden']);\n";
		$ClassPHP.="\t\tforeach (\$arregloOrden as \$".$arregloCampos['codigo']."){\n";
		$ClassPHP.="\t\t\t\$datosmodif['".$arregloCampos['codigo']."'] = \$".$arregloCampos['codigo'].";\n";
		$ClassPHP.="\t\t\tif (!parent::ModificarOrden(\$datosmodif))\n";
		$ClassPHP.="\t\t\t\treturn false;\n";
		if (isset($arregloCampos['JsonCodigo']) && $arregloCampos['JsonCodigo']==1)
		{
			$ClassPHP.="\t\t\tif (!\$this->PublicarJsonxCodigo(\$datosmodif))\n";
			$ClassPHP.="\t\t\t\treturn false;\n\r";
		}
		$ClassPHP.="\t\t\t\$datosmodif['".$arregloCampos['campoOrden']."']++;\n";
		$ClassPHP.="\t\t}\n";
		if ((isset($arregloCampos['JsonListado']) && $arregloCampos['JsonListado']==1))
		{
			$ClassPHP.="\t\t\tif (!\$this->Publicar(\$datosmodif))\n";
			$ClassPHP.="\t\t\t\treturn false;\n\r";
		}
		$ClassPHP.="\t\treturn true;\n";
		$ClassPHP.="\t}\n\r\n\r\n\r";
			
		return true;	
	}
	
	
	public function ClassModificarEstado($arregloCampos,&$ClassPHP)
	{
		$ClassPHP.="\tpublic function ModificarEstado(\$datos)\n";
		$ClassPHP.="\t{\n";
		$ClassPHP.="\t\tif (!parent::ModificarEstado(\$datos))\n";
		$ClassPHP.="\t\t\treturn false;\n";
		if ((isset($arregloCampos['JsonListado']) && $arregloCampos['JsonListado']==1) || (isset($arregloCampos['JsonCodigo']) && $arregloCampos['JsonCodigo']==1))
		{
			$ClassPHP.="\t\tif (!\$this->Publicar(\$datos))\n";
			$ClassPHP.="\t\t\treturn false;\n\r";
		}
		$ClassPHP.="\t\treturn true;\n";
		$ClassPHP.="\t}\n\r\n\r\n\r";
			
		return true;	
	}
	
	
	public function ClassActivarEstado($arregloCampos,&$ClassPHP)
	{
		$ClassPHP.="\tpublic function Activar(\$datos)\n";
		$ClassPHP.="\t{\n";
		$ClassPHP.="\t\t\$datosmodif['".$arregloCampos['codigo']."'] = \$datos['".$arregloCampos['codigo']."'];\n";
		$ClassPHP.="\t\t\$datosmodif['".$arregloCampos['campoEstado']."'] = ACTIVO;\n";
		$ClassPHP.="\t\tif (!\$this->ModificarEstado(\$datosmodif))\n";
		$ClassPHP.="\t\t\treturn false;\n";
		$ClassPHP.="\t\treturn true;\n";
		$ClassPHP.="\t}\n\r\n\r\n\r";
			
		return true;	
	}
	
	
	public function ClassDesActivarEstado($arregloCampos,&$ClassPHP)
	{
		$ClassPHP.="\tpublic function DesActivar(\$datos)\n";
		$ClassPHP.="\t{\n";
		$ClassPHP.="\t\t\$datosmodif['".$arregloCampos['codigo']."'] = \$datos['".$arregloCampos['codigo']."'];\n";
		$ClassPHP.="\t\t\$datosmodif['".$arregloCampos['campoEstado']."'] = NOACTIVO;\n";
		$ClassPHP.="\t\tif (!\$this->ModificarEstado(\$datosmodif))\n";
		$ClassPHP.="\t\t\treturn false;\n";
		$ClassPHP.="\t\treturn true;\n";
		$ClassPHP.="\t}\n\r\n\r\n\r";
			
		return true;	
	}
	
	
	
	public function ClassBusquedaAvanzada($arregloCamposTabla,$arregloCampos,&$ClassPHP)
	{
		
		$ClassPHP.="\tpublic function BusquedaAvanzada(\$datos,&\$resultado,&\$numfilas)\n";
		$ClassPHP.="\t{\n";
		
		$ClassPHP.="\t\t\$sparam=array(\n";
		foreach($arregloCampos['camposBusquedaAvanzada'] as $Campos)
		{
			$buscacampoestado = false;
			if($Campos==$arregloCampos['campoEstado'])
				$buscacampoestado = true;	
			
			$ClassPHP.="\t\t\t'x".$Campos."'=> 0,\n";
			if ($arregloCampos['otroscampos']['busquedaavanzada_'.$Campos]==2)
				$ClassPHP.="\t\t\t'".$Campos."'=> \"-1\",\n";
			else
				$ClassPHP.="\t\t\t'".$Campos."'=> \"\",\n";
		}
		if($arregloCampos['tieneEstado']==1 && !$buscacampoestado)
		{
			$ClassPHP.="\t\t\t'x".$arregloCampos['campoEstado']."'=> 0,\n";
			$ClassPHP.="\t\t\t'".$arregloCampos['campoEstado']."'=> \"-1\",\n";
			
		}
		$ClassPHP.="\t\t\t'limit'=> '',\n";
		if($arregloCampos['tieneOrden']==1)
		{
			$ClassPHP.="\t\t\t'orderby'=> \"".$arregloCampos['campoOrden']." ASC\"\n";
		}
		else
		{
			$ClassPHP.="\t\t\t'orderby'=> \"".$arregloCampos['codigo']." DESC\"\n";
		}
		$ClassPHP.="\t\t);\n\r";			
	
		foreach($arregloCampos['camposBusquedaAvanzada'] as $Campos)
		{
			$esFecha=false;
			if (array_key_exists($Campos,$arregloCamposTabla))
			{
				if (substr($arregloCamposTabla[$Campos]['Type'],0,4)=="date" || substr($arregloCamposTabla[$Campos]['Type'],0,8)=="datetime")
					$esFecha=true;
	
				$ClassPHP.="\t\tif(isset(\$datos['".$Campos."']) && \$datos['".$Campos."']!=\"\")\n";
				$ClassPHP.="\t\t{\n";
				if (!$esFecha)
					$ClassPHP.="\t\t\t\$sparam['".$Campos."']= \$datos['".$Campos."'];\n";
				else
					$ClassPHP.="\t\t\t\$sparam['".$Campos."']=FuncionesPHPLocal::ConvertirFecha( \$datos['".$Campos."'],'dd/mm/aaaa','aaaa-mm-dd');\n";
						
				$ClassPHP.="\t\t\t\$sparam['x".$Campos."']= 1;\n";
				$ClassPHP.="\t\t}\n";
			}
		}
		
		
		if($arregloCampos['tieneEstado']==1 && !$buscacampoestado)
		{
			$ClassPHP.="\t\tif(isset(\$datos['".$arregloCampos['campoEstado']."']) && \$datos['".$arregloCampos['campoEstado']."']!=\"\")\n";
			$ClassPHP.="\t\t{\n";
				$ClassPHP.="\t\t\t\$sparam['".$arregloCampos['campoEstado']."']= \$datos['".$arregloCampos['campoEstado']."'];\n";
				$ClassPHP.="\t\t\t\$sparam['x".$arregloCampos['campoEstado']."']= 1;\n";
			$ClassPHP.="\t\t}\n";
			
			
		}
	
		$ClassPHP.="\n\r\t\tif(isset(\$datos['orderby']) && \$datos['orderby']!=\"\")\n";
		$ClassPHP.="\t\t\t\$sparam['orderby']= \$datos['orderby'];\n\r";
	
		$ClassPHP.="\t\tif(isset(\$datos['limit']) && \$datos['limit']!=\"\")\n";
		$ClassPHP.="\t\t\t\$sparam['limit']= \$datos['limit'];\n\r";
	
		$ClassPHP.="\t\tif (!parent::BusquedaAvanzada(\$sparam,\$resultado,\$numfilas))\n";
		$ClassPHP.="\t\t\treturn false;\n";
		$ClassPHP.="\t\treturn true;\n";
		$ClassPHP.="\t}\n\r\n\r\n\r";
		
	}
	
	
	public function ClassPublicar($arregloCampos,&$ClassPHP)
	{
		$ClassPHP.="\tpublic function Publicar(\$datos)\n";
		$ClassPHP.="\t{\n";
		if (isset($arregloCampos['JsonListado']) && $arregloCampos['JsonListado']==1)
		{
			$ClassPHP.="\t\tif (!\$this->PublicarListadoJson())\n";
			$ClassPHP.="\t\t\treturn false;\n";
		}
		if (isset($arregloCampos['JsonCodigo']) && $arregloCampos['JsonCodigo']==1)
		{
			$ClassPHP.="\t\tif (!\$this->PublicarJsonxCodigo(\$datos))\n";
			$ClassPHP.="\t\t\treturn false;\n";
		}
		$ClassPHP.="\t\treturn true;\n";
		$ClassPHP.="\t}\n\r\n\r\n\r";		
			
	}
	
	
	public function ClassPublicarJsonListado($arregloCampos,&$ClassPHP)
	{
		$carpeta = $arregloCampos['CarpetaJson'];
		if($carpeta!="")
		{
			$rest = substr($arregloCampos['CarpetaJson'], -1);
			if($rest!="/")
				$carpeta .= "/";	
		}		
		$ClassPHP.="\tpublic function PublicarListadoJson()\n";
		$ClassPHP.="\t{\n";
		$ClassPHP.="\t\t\$nombrearchivo = \"".$arregloCampos['archivo']."\";\n";
		$ClassPHP.="\t\t\$carpeta = PUBLICA.\"json/".$carpeta."\";\n";
		$ClassPHP.="\t\tif(!\$this->GerenarArrayDatosJsonListado(\$array))\n";
		$ClassPHP.="\t\t\treturn false;\n";		
		$ClassPHP.="\t\tif(count(\$array)>0)\n";
		$ClassPHP.="\t\t{\n";
		$ClassPHP.="\t\t\tif(!\$this->GuardarDatosJson(\$nombrearchivo,\$carpeta,\$array))\n";
		$ClassPHP.="\t\t\t\treturn false;\n";	
		$ClassPHP.="\t\t}\n";
		$ClassPHP.="\t\telse\n";
		$ClassPHP.="\t\t{\n";
		$ClassPHP.="\t\t\tif(!\$this->EliminarDatosJson(\$nombrearchivo,\$carpeta))\n";
		$ClassPHP.="\t\t\t\treturn false;\n";	
		$ClassPHP.="\t\t}\n";
		$ClassPHP.="\t\treturn true;\n";
		$ClassPHP.="\t}\n\r\n\r\n\r";	
			
	}
	
	
	public function ClassPublicarJsonPorCodigo($arregloCampos,&$ClassPHP)
	{
		$carpeta = $arregloCampos['CarpetaJson'];
		if($carpeta!="")
		{
			$rest = substr($arregloCampos['CarpetaJson'], -1);
			if($rest!="/")
				$carpeta .= "/";	
		}	
		$ClassPHP.="\tpublic function PublicarJsonxCodigo(\$datos)\n";
		$ClassPHP.="\t{\n";
		$ClassPHP.="\t\t\$nombrearchivo = \"".$arregloCampos['archivo']."_\".\$datos['".$arregloCampos['codigo']."'];\n";
		$ClassPHP.="\t\t\$carpeta = PUBLICA.\"json/".$carpeta."\";\n";
		$ClassPHP.="\t\tif(!\$this->GerenarArrayDatosJsonxCodigo(\$datos,\$array))\n";
		$ClassPHP.="\t\t\treturn false;\n";		
		$ClassPHP.="\t\tif(count(\$array)>0)\n";
		$ClassPHP.="\t\t{\n";
		$ClassPHP.="\t\t\tif(!\$this->GuardarDatosJson(\$nombrearchivo,\$carpeta,\$array))\n";
		$ClassPHP.="\t\t\t\treturn false;\n";	
		$ClassPHP.="\t\t}\n";
		$ClassPHP.="\t\telse\n";
		$ClassPHP.="\t\t{\n";
		$ClassPHP.="\t\t\tif(!\$this->EliminarDatosJson(\$nombrearchivo,\$carpeta))\n";
		$ClassPHP.="\t\t\t\treturn false;\n";	
		$ClassPHP.="\t\t}\n";
		$ClassPHP.="\t\treturn true;\n";
		$ClassPHP.="\t}\n\r\n\r\n\r";	
			
	}
	
		
	public function ClassGuardarDatosJson($arregloCampos,&$ClassPHP)
	{
		$ClassPHP.="\tpublic function GuardarDatosJson(\$nombrearchivo,\$carpeta,\$array)\n";
		$ClassPHP.="\t{\n";
		$ClassPHP.="\t\t\$datosJson = FuncionesPHPLocal::DecodificarUtf8(\$array);\n";
		$ClassPHP.="\t\t\$jsonData = json_encode(\$datosJson);\n";
		$ClassPHP.="\t\tif(!is_dir(\$carpeta)){\n"; 
		$ClassPHP.="\t\t\t@mkdir(\$carpeta);\n";
		$ClassPHP.="\t\t}\n";
		$ClassPHP.="\t\tif(!FuncionesPHPLocal::GuardarArchivo(\$carpeta,\$jsonData,\$nombrearchivo.\".json\"))\n";
		$ClassPHP.="\t\t{\n";
		$ClassPHP.="\t\t\tFuncionesPHPLocal::MostrarMensaje(\$this->conexion,MSG_INF,\"Error, al generar el archivo json. \",array(\"archivo\" => __FILE__,\"funcion\" => __FUNCTION__, \"linea\" => __LINE__),array(\"formato\"=>\$this->formato));\n";
		$ClassPHP.="\t\t\treturn false;\n";
		$ClassPHP.="\t\t}\n";
		$ClassPHP.="\t\treturn true;\n";
		$ClassPHP.="\t}\n\r\n\r\n\r";	
	}
		
	public function ClassEliminarDatosJson($arregloCampos,&$ClassPHP)
	{
		$ClassPHP.="\tpublic function EliminarDatosJson(\$nombrearchivo,\$carpeta)\n";
		$ClassPHP.="\t{\n";
		$ClassPHP.="\t\tif(file_exists(\$carpeta.\$nombrearchivo.\".json\"))\n";
		$ClassPHP.="\t\t{\n";
		$ClassPHP.="\t\t\tunlink(\$carpeta.\$nombrearchivo.\".json\");\n";
		$ClassPHP.="\t\t}\n";
		$ClassPHP.="\t\treturn true;\n";
		$ClassPHP.="\t}\n\r\n\r\n\r";
	}
		
		
		
	public function ClassGerenarArrayDatosJsonListado($arregloCampos,&$ClassPHP)
	{
		$ClassPHP.="\tpublic function GerenarArrayDatosJsonListado(&\$array)\n";
		$ClassPHP.="\t{\n";
		if ($arregloCampos['TieneMultimedia'])
		{
			$ClassPHP.="\t\t\$oMultimedia = new cMultimedia(\$this->conexion,\$this->formato);\n";
		}
		$ClassPHP.="\t\t\$array = array();\n";
		if ($arregloCampos['tieneEstado']==1)
		{
			$ClassPHP.="\t\t\$datos['".$arregloCampos['campoEstado']."'] = ACTIVO;\n";
		}
		$ClassPHP.="\t\tif(!\$this->BusquedaAvanzada(\$datos,\$resultados,\$numfilas))\n";
		$ClassPHP.="\t\t\treturn false;\n";
		$ClassPHP.="\t\tif(\$numfilas>0)\n";
		$ClassPHP.="\t\t{\n";
		$ClassPHP.="\t\t\twhile(\$fila = \$this->conexion->ObtenerSiguienteRegistro(\$resultados))\n";
		$ClassPHP.="\t\t\t{\n";
		$ClassPHP.="\t\t\t\t\$array[\$fila['".$arregloCampos['codigo']."']] = \$fila;\n";	
		if ($arregloCampos['TieneMultimedia'])
		{
			
			foreach ($arregloCampos['otroscampos']['campoalta'] as $camposAlta)
			{
					if ($camposAlta!=$arregloCampos['codigo'])
					{ 
						$campotipo = $arregloCampos['otroscampos']['camposaltatipo_'.$camposAlta];
						if($campotipo==8 || $campotipo==9 || $campotipo==10 || $campotipo==11)
						{
							switch($campotipo)
							{
								case "8":
									$ClassPHP.="\t\t\t\t\$datosmultimedia['multimediaconjuntocod'] = FOTOS;\n";
									$ClassPHP.="\t\t\t\t\$datosmultimedia['multimediacod'] = \$fila['".$camposAlta."'];\n";
									$ClassPHP.="\t\t\t\tif(!\$oMultimedia->BuscarMultimediaxCodigo(\$datosmultimedia,\$resultadoFotos,\$numfilasFotos))\n";
									$ClassPHP.="\t\t\t\t\treturn false;\n";
									$ClassPHP.="\t\t\t\tif(\$numfilasFotos>0)\n";
									$ClassPHP.="\t\t\t\t{\n";
									$ClassPHP.="\t\t\t\t\t\$filaFotos= \$this->conexion->ObtenerSiguienteRegistro(\$resultadoFotos);\n";
									$ClassPHP.="\t\t\t\t\t\$array[\$fila['".$arregloCampos['codigo']."']]['".$camposAlta."_url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.\$filaFotos['multimediaubic'];\n";
									$ClassPHP.="\t\t\t\t}\n";
									
								break;
								case "9":
									$ClassPHP.="\t\t\t\t\$datosmultimedia['multimediaconjuntocod'] = VIDEOS;\n";
									$ClassPHP.="\t\t\t\t\$datosmultimedia['multimediacod'] = \$fila['".$camposAlta."'];\n";
									$ClassPHP.="\t\t\t\tif(!\$oMultimedia->BuscarMultimediaxCodigo(\$datosmultimedia,\$resultadoVideos,\$numfilasVideos))\n";
									$ClassPHP.="\t\t\t\t\treturn false;\n";
									$ClassPHP.="\t\t\t\tif(\$numfilasVideos>0)\n";
									$ClassPHP.="\t\t\t\t{\n";
									$ClassPHP.="\t\t\t\t\t\$filaVideos= \$this->conexion->ObtenerSiguienteRegistro(\$resultadoVideos);\n";
									$ClassPHP.="\t\t\t\t\t\$array[\$fila['".$arregloCampos['codigo']."']]['".$camposAlta."_url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.\"videos/\".\$filaVideos['multimediaubic'];\n";
									$ClassPHP.="\t\t\t\t}\n";
								break;
								case "10":
									$ClassPHP.="\t\t\t\t\$datosmultimedia['multimediaconjuntocod'] = AUDIOS;\n";
									$ClassPHP.="\t\t\t\t\$datosmultimedia['multimediacod'] = \$fila['".$camposAlta."'];\n";
									$ClassPHP.="\t\t\t\tif(!\$oMultimedia->BuscarMultimediaxCodigo(\$datosmultimedia,\$resultadoAudios,\$numfilasAudios))\n";
									$ClassPHP.="\t\t\t\t\treturn false;\n";
									$ClassPHP.="\t\t\t\tif(\$numfilasAudios>0)\n";
									$ClassPHP.="\t\t\t\t{\n";
									$ClassPHP.="\t\t\t\t\t\$filaAudios= \$this->conexion->ObtenerSiguienteRegistro(\$resultadoAudios);\n";
									$ClassPHP.="\t\t\t\t\t\$array[\$fila['".$arregloCampos['codigo']."']]['".$camposAlta."_url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.\"audios/\".\$filaAudios['multimediaubic'];\n";	
									$ClassPHP.="\t\t\t\t}\n";
								break;
								case "11":
									$ClassPHP.="\t\t\t\t\$datosmultimedia['multimediaconjuntocod'] = FILES;\n";
									$ClassPHP.="\t\t\t\t\$datosmultimedia['multimediacod'] = \$fila['".$camposAlta."'];\n";
									$ClassPHP.="\t\t\t\tif(!\$oMultimedia->BuscarMultimediaxCodigo(\$datosmultimedia,\$resultadoArchivos,\$numfilasArchivos))\n";
									$ClassPHP.="\t\t\t\t\treturn false;\n";
									$ClassPHP.="\t\t\t\tif(\$numfilasArchivos>0)\n";
									$ClassPHP.="\t\t\t\t{\n";
									$ClassPHP.="\t\t\t\t\t\$filaArchivos= \$this->conexion->ObtenerSiguienteRegistro(\$resultadoArchivos);\n";
									$ClassPHP.="\t\t\t\t\t\$array[\$fila['".$arregloCampos['codigo']."']]['".$camposAlta."_url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.\"audios/\".\$filaArchivos['multimediaubic'];\n";	
									$ClassPHP.="\t\t\t\t}\n";
								break;
							}//fin  swich	
						}
					}
			  }
		}
		if($arregloCampos['TieneClaseMultimedia'])
		{
			$ClassPHP.="\t\t\t\t\$o".$arregloCampos['ClaseMultimedia']." = new c".$arregloCampos['ClaseMultimedia']."(\$this->conexion,\$this->formato);\n";			
			$ClassPHP.="\t\t\t\t\$array[\$fila['".$arregloCampos['codigo']."']]['multimedias']['fotos'] = array();\n";
			$ClassPHP.="\t\t\t\t\$datosmultimedia['multimediaconjuntocod'] = FOTOS;\n";
			$ClassPHP.="\t\t\t\t\$datosmultimedia['".$arregloCampos['codigo']."'] = \$fila['".$arregloCampos['codigo']."'];\n";
			$ClassPHP.="\t\t\t\tif(!\$o".$arregloCampos['ClaseMultimedia']."->BuscarMultimediaxCodigoxMultimediaConjunto(\$datosmultimedia,\$resultadoFotos,\$numfilasFotos))\n";
			$ClassPHP.="\t\t\t\t\treturn false;\n";
			$ClassPHP.="\t\t\t\tif(\$numfilasFotos>0)\n";
			$ClassPHP.="\t\t\t\t{\n";
			$ClassPHP.="\t\t\t\t\twhile(\$filaFotos= \$this->conexion->ObtenerSiguienteRegistro(\$resultadoFotos))\n";
			$ClassPHP.="\t\t\t\t\t{\n";
			$ClassPHP.="\t\t\t\t\t\tif(!\$this->GenerarDatosMultimedia(\$filaFotos,\$array[\$fila['".$arregloCampos['codigo']."']]['multimedias'],'multimediacod','".$arregloCampos['PrefijoMultimedia']."'))\n";
			$ClassPHP.="\t\t\t\t\t\t\treturn false;\n";
			$ClassPHP.="\t\t\t\t\t}\n";	
			$ClassPHP.="\t\t\t\t}\n\n";
			
			
			$ClassPHP.="\t\t\t\t\$array[\$fila['".$arregloCampos['codigo']."']]['multimedias']['audios'] = array();\n";
			$ClassPHP.="\t\t\t\t\$datosmultimedia['multimediaconjuntocod'] = AUDIOS;\n";
			$ClassPHP.="\t\t\t\t\$datosmultimedia['".$arregloCampos['codigo']."'] = \$fila['".$arregloCampos['codigo']."'];\n";
			$ClassPHP.="\t\t\t\tif(!\$o".$arregloCampos['ClaseMultimedia']."->BuscarMultimediaxCodigoxMultimediaConjunto(\$datosmultimedia,\$resultadoAudios,\$numfilasAudios))\n";
			$ClassPHP.="\t\t\t\t\treturn false;\n";
			$ClassPHP.="\t\t\t\tif(\$numfilasAudios>0)\n";
			$ClassPHP.="\t\t\t\t{\n";
			$ClassPHP.="\t\t\t\t\twhile(\$filaAudios= \$this->conexion->ObtenerSiguienteRegistro(\$resultadoAudios))\n";
			$ClassPHP.="\t\t\t\t\t{\n";
			$ClassPHP.="\t\t\t\t\t\tif(!\$this->GenerarDatosMultimedia(\$filaAudios,\$array[\$fila['".$arregloCampos['codigo']."']]['multimedias'],'multimediacod','".$arregloCampos['PrefijoMultimedia']."'))\n";
			$ClassPHP.="\t\t\t\t\t\t\treturn false;\n";
			$ClassPHP.="\t\t\t\t\t}\n";	
			$ClassPHP.="\t\t\t\t}\n\n";
			
					
			$ClassPHP.="\t\t\t\t\$array[\$fila['".$arregloCampos['codigo']."']]['multimedias']['videos'] = array();\n";
			$ClassPHP.="\t\t\t\t\$datosmultimedia['multimediaconjuntocod'] = VIDEOS;\n";
			$ClassPHP.="\t\t\t\t\$datosmultimedia['".$arregloCampos['codigo']."'] = \$fila['".$arregloCampos['codigo']."'];\n";
			$ClassPHP.="\t\t\t\tif(!\$o".$arregloCampos['ClaseMultimedia']."->BuscarMultimediaxCodigoxMultimediaConjunto(\$datosmultimedia,\$resultadoVideos,\$numfilasVideos))\n";
			$ClassPHP.="\t\t\t\t\treturn false;\n";
			$ClassPHP.="\t\t\t\tif(\$numfilasVideos>0)\n";
			$ClassPHP.="\t\t\t\t{\n";
			$ClassPHP.="\t\t\t\t\twhile(\$filaVideos= \$this->conexion->ObtenerSiguienteRegistro(\$resultadoVideos))\n";
			$ClassPHP.="\t\t\t\t\t{\n";
			$ClassPHP.="\t\t\t\t\t\tif(!\$this->GenerarDatosMultimedia(\$filaVideos,\$array[\$fila['".$arregloCampos['codigo']."']]['multimedias'],'multimediacod','".$arregloCampos['PrefijoMultimedia']."'))\n";
			$ClassPHP.="\t\t\t\t\t\t\treturn false;\n";
			$ClassPHP.="\t\t\t\t\t}\n";	
			$ClassPHP.="\t\t\t\t}\n\n";
			
			$ClassPHP.="\t\t\t\t\$array[\$fila['".$arregloCampos['codigo']."']]['multimedias']['archivos'] = array();\n";
			$ClassPHP.="\t\t\t\t\$datosmultimedia['multimediaconjuntocod'] = FILES;\n";
			$ClassPHP.="\t\t\t\t\$datosmultimedia['".$arregloCampos['codigo']."'] = \$fila['".$arregloCampos['codigo']."'];\n";
			$ClassPHP.="\t\t\t\tif(!\$o".$arregloCampos['ClaseMultimedia']."->BuscarMultimediaxCodigoxMultimediaConjunto(\$datosmultimedia,\$resultadoFiles,\$numfilasFiles))\n";
			$ClassPHP.="\t\t\t\t\treturn false;\n";
			$ClassPHP.="\t\t\t\tif(\$numfilasFiles>0)\n";
			$ClassPHP.="\t\t\t\t{\n";
			$ClassPHP.="\t\t\t\t\twhile(\$filaFiles= \$this->conexion->ObtenerSiguienteRegistro(\$resultadoFiles))\n";
			$ClassPHP.="\t\t\t\t\t{\n";
			$ClassPHP.="\t\t\t\t\t\tif(!\$this->GenerarDatosMultimedia(\$filaFiles,\$array[\$fila['".$arregloCampos['codigo']."']]['multimedias'],'multimediacod','".$arregloCampos['PrefijoMultimedia']."'))\n";
			$ClassPHP.="\t\t\t\t\t\t\treturn false;\n";
			$ClassPHP.="\t\t\t\t\t}\n";	
			$ClassPHP.="\t\t\t\t}\n\n";
			
		}
		$ClassPHP.="\t\t\t}\n";			
		$ClassPHP.="\t\t}\n";	
		$ClassPHP.="\t\treturn true;\n";
		$ClassPHP.="\t}\n\r\n\r\n\r";	
				
	}
	
	
	public function ClassGerenarArrayDatosJsonxCodigo($arregloCampos,&$ClassPHP)
	{
		$ClassPHP.="\tpublic function GerenarArrayDatosJsonxCodigo(\$datos,&\$array)\n";
		$ClassPHP.="\t{\n";
		if ($arregloCampos['TieneMultimedia'])
		{
			$ClassPHP.="\t\t\$oMultimedia = new cMultimedia(\$this->conexion,\$this->formato);\n";
		}
		$ClassPHP.="\t\t\$array = array();\n";
		$ClassPHP.="\t\tif(!\$this->BuscarxCodigo(\$datos,\$resultados,\$numfilas))\n";
		$ClassPHP.="\t\t\treturn false;\n";
		$ClassPHP.="\t\tif(\$numfilas==1)\n";
		$ClassPHP.="\t\t{\n";
		$ClassPHP.="\t\t\twhile(\$fila = \$this->conexion->ObtenerSiguienteRegistro(\$resultados))\n";
		$ClassPHP.="\t\t\t{\n";
		$ClassPHP.="\t\t\t\t\$array[\$fila['".$arregloCampos['codigo']."']] = \$fila;\n";	
		if ($arregloCampos['TieneMultimedia'])
		{
			
			foreach ($arregloCampos['otroscampos']['campoalta'] as $camposAlta)
			{
					if ($camposAlta!=$arregloCampos['codigo'])
					{ 
						$campotipo = $arregloCampos['otroscampos']['camposaltatipo_'.$camposAlta];
						if($campotipo==8 || $campotipo==9 || $campotipo==10 || $campotipo==11)
						{
							switch($campotipo)
							{
								case "8":
									$ClassPHP.="\t\t\t\t\$datosmultimedia['multimediaconjuntocod'] = FOTOS;\n";
									$ClassPHP.="\t\t\t\t\$datosmultimedia['multimediacod'] = \$fila['".$camposAlta."'];\n";
									$ClassPHP.="\t\t\t\tif(!\$oMultimedia->BuscarMultimediaxCodigo(\$datosmultimedia,\$resultadoFotos,\$numfilasFotos))\n";
									$ClassPHP.="\t\t\t\t\treturn false;\n";
									$ClassPHP.="\t\t\t\tif(\$numfilasFotos>0)\n";
									$ClassPHP.="\t\t\t\t{\n";
									$ClassPHP.="\t\t\t\t\t\$filaFotos= \$this->conexion->ObtenerSiguienteRegistro(\$resultadoFotos);\n";
									$ClassPHP.="\t\t\t\t\t\$array[\$fila['".$arregloCampos['codigo']."']]['".$camposAlta."_url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.\$filaFotos['multimediaubic'];\n";
									$ClassPHP.="\t\t\t\t}\n";
									
								break;
								case "9":
									$ClassPHP.="\t\t\t\t\$datosmultimedia['multimediaconjuntocod'] = VIDEOS;\n";
									$ClassPHP.="\t\t\t\t\$datosmultimedia['multimediacod'] = \$fila['".$camposAlta."'];\n";
									$ClassPHP.="\t\t\t\tif(!\$oMultimedia->BuscarMultimediaxCodigo(\$datosmultimedia,\$resultadoVideos,\$numfilasVideos))\n";
									$ClassPHP.="\t\t\t\t\treturn false;\n";
									$ClassPHP.="\t\t\t\tif(\$numfilasVideos>0)\n";
									$ClassPHP.="\t\t\t\t{\n";
									$ClassPHP.="\t\t\t\t\t\$filaVideos= \$this->conexion->ObtenerSiguienteRegistro(\$resultadoVideos);\n";
									$ClassPHP.="\t\t\t\t\t\$array[\$fila['".$arregloCampos['codigo']."']]['".$camposAlta."_url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.\"videos/\".\$filaVideos['multimediaubic'];\n";
									$ClassPHP.="\t\t\t\t}\n";
								break;
								case "10":
									$ClassPHP.="\t\t\t\t\$datosmultimedia['multimediaconjuntocod'] = AUDIOS;\n";
									$ClassPHP.="\t\t\t\t\$datosmultimedia['multimediacod'] = \$fila['".$camposAlta."'];\n";
									$ClassPHP.="\t\t\t\tif(!\$oMultimedia->BuscarMultimediaxCodigo(\$datosmultimedia,\$resultadoAudios,\$numfilasAudios))\n";
									$ClassPHP.="\t\t\t\t\treturn false;\n";
									$ClassPHP.="\t\t\t\tif(\$numfilasAudios>0)\n";
									$ClassPHP.="\t\t\t\t{\n";
									$ClassPHP.="\t\t\t\t\t\$filaAudios= \$this->conexion->ObtenerSiguienteRegistro(\$resultadoAudios);\n";
									$ClassPHP.="\t\t\t\t\t\$array[\$fila['".$arregloCampos['codigo']."']]['".$camposAlta."_url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.\"audios/\".\$filaAudios['multimediaubic'];\n";	
									$ClassPHP.="\t\t\t\t}\n";
								break;
								case "11":
									$ClassPHP.="\t\t\t\t\$datosmultimedia['multimediaconjuntocod'] = FILES;\n";
									$ClassPHP.="\t\t\t\t\$datosmultimedia['multimediacod'] = \$fila['".$camposAlta."'];\n";
									$ClassPHP.="\t\t\t\tif(!\$oMultimedia->BuscarMultimediaxCodigo(\$datosmultimedia,\$resultadoArchivos,\$numfilasArchivos))\n";
									$ClassPHP.="\t\t\t\t\treturn false;\n";
									$ClassPHP.="\t\t\t\tif(\$numfilasArchivos>0)\n";
									$ClassPHP.="\t\t\t\t{\n";
									$ClassPHP.="\t\t\t\t\t\$filaArchivos= \$this->conexion->ObtenerSiguienteRegistro(\$resultadoArchivos);\n";
									$ClassPHP.="\t\t\t\t\t\$array[\$fila['".$arregloCampos['codigo']."']]['".$camposAlta."_url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.\"audios/\".\$filaArchivos['multimediaubic'];\n";	
									$ClassPHP.="\t\t\t\t}\n";
								break;
							}//fin  swich	
						}
					}
			  }
		}
		if($arregloCampos['TieneClaseMultimedia'])
		{
			$ClassPHP.="\t\t\t\t\$o".$arregloCampos['ClaseMultimedia']." = new c".$arregloCampos['ClaseMultimedia']."(\$this->conexion,\$this->formato);\n";			
			$ClassPHP.="\t\t\t\t\$array[\$fila['".$arregloCampos['codigo']."']]['multimedias']['fotos'] = array();\n";
			$ClassPHP.="\t\t\t\t\$datosmultimedia['multimediaconjuntocod'] = FOTOS;\n";
			$ClassPHP.="\t\t\t\t\$datosmultimedia['".$arregloCampos['codigo']."'] = \$fila['".$arregloCampos['codigo']."'];\n";
			$ClassPHP.="\t\t\t\tif(!\$o".$arregloCampos['ClaseMultimedia']."->BuscarMultimediaxCodigoxMultimediaConjunto(\$datosmultimedia,\$resultadoFotos,\$numfilasFotos))\n";
			$ClassPHP.="\t\t\t\t\treturn false;\n";
			$ClassPHP.="\t\t\t\tif(\$numfilasFotos>0)\n";
			$ClassPHP.="\t\t\t\t{\n";
			$ClassPHP.="\t\t\t\t\twhile(\$filaFotos= \$this->conexion->ObtenerSiguienteRegistro(\$resultadoFotos))\n";
			$ClassPHP.="\t\t\t\t\t{\n";
			$ClassPHP.="\t\t\t\t\t\tif(!\$this->GenerarDatosMultimedia(\$filaFotos,\$array[\$fila['".$arregloCampos['codigo']."']]['multimedias'],'multimediacod','".$arregloCampos['PrefijoMultimedia']."'))\n";
			$ClassPHP.="\t\t\t\t\t\t\treturn false;\n";
			$ClassPHP.="\t\t\t\t\t}\n";	
			$ClassPHP.="\t\t\t\t}\n\n";
			
			
			$ClassPHP.="\t\t\t\t\$array[\$fila['".$arregloCampos['codigo']."']]['multimedias']['audios'] = array();\n";
			$ClassPHP.="\t\t\t\t\$datosmultimedia['multimediaconjuntocod'] = AUDIOS;\n";
			$ClassPHP.="\t\t\t\t\$datosmultimedia['".$arregloCampos['codigo']."'] = \$fila['".$arregloCampos['codigo']."'];\n";
			$ClassPHP.="\t\t\t\tif(!\$o".$arregloCampos['ClaseMultimedia']."->BuscarMultimediaxCodigoxMultimediaConjunto(\$datosmultimedia,\$resultadoAudios,\$numfilasAudios))\n";
			$ClassPHP.="\t\t\t\t\treturn false;\n";
			$ClassPHP.="\t\t\t\tif(\$numfilasAudios>0)\n";
			$ClassPHP.="\t\t\t\t{\n";
			$ClassPHP.="\t\t\t\t\twhile(\$filaAudios= \$this->conexion->ObtenerSiguienteRegistro(\$resultadoAudios))\n";
			$ClassPHP.="\t\t\t\t\t{\n";
			$ClassPHP.="\t\t\t\t\t\tif(!\$this->GenerarDatosMultimedia(\$filaAudios,\$array[\$fila['".$arregloCampos['codigo']."']]['multimedias'],'multimediacod','".$arregloCampos['PrefijoMultimedia']."'))\n";
			$ClassPHP.="\t\t\t\t\t\t\treturn false;\n";
			$ClassPHP.="\t\t\t\t\t}\n";	
			$ClassPHP.="\t\t\t\t}\n\n";
			
					
			$ClassPHP.="\t\t\t\t\$array[\$fila['".$arregloCampos['codigo']."']]['multimedias']['videos'] = array();\n";
			$ClassPHP.="\t\t\t\t\$datosmultimedia['multimediaconjuntocod'] = VIDEOS;\n";
			$ClassPHP.="\t\t\t\t\$datosmultimedia['".$arregloCampos['codigo']."'] = \$fila['".$arregloCampos['codigo']."'];\n";
			$ClassPHP.="\t\t\t\tif(!\$o".$arregloCampos['ClaseMultimedia']."->BuscarMultimediaxCodigoxMultimediaConjunto(\$datosmultimedia,\$resultadoVideos,\$numfilasVideos))\n";
			$ClassPHP.="\t\t\t\t\treturn false;\n";
			$ClassPHP.="\t\t\t\tif(\$numfilasVideos>0)\n";
			$ClassPHP.="\t\t\t\t{\n";
			$ClassPHP.="\t\t\t\t\twhile(\$filaVideos= \$this->conexion->ObtenerSiguienteRegistro(\$resultadoVideos))\n";
			$ClassPHP.="\t\t\t\t\t{\n";
			$ClassPHP.="\t\t\t\t\t\tif(!\$this->GenerarDatosMultimedia(\$filaVideos,\$array[\$fila['".$arregloCampos['codigo']."']]['multimedias'],'multimediacod','".$arregloCampos['PrefijoMultimedia']."'))\n";
			$ClassPHP.="\t\t\t\t\t\t\treturn false;\n";
			$ClassPHP.="\t\t\t\t\t}\n";	
			$ClassPHP.="\t\t\t\t}\n\n";
			
			$ClassPHP.="\t\t\t\t\$array[\$fila['".$arregloCampos['codigo']."']]['multimedias']['archivos'] = array();\n";
			$ClassPHP.="\t\t\t\t\$datosmultimedia['multimediaconjuntocod'] = FILES;\n";
			$ClassPHP.="\t\t\t\t\$datosmultimedia['".$arregloCampos['codigo']."'] = \$fila['".$arregloCampos['codigo']."'];\n";
			$ClassPHP.="\t\t\t\tif(!\$o".$arregloCampos['ClaseMultimedia']."->BuscarMultimediaxCodigoxMultimediaConjunto(\$datosmultimedia,\$resultadoFiles,\$numfilasFiles))\n";
			$ClassPHP.="\t\t\t\t\treturn false;\n";
			$ClassPHP.="\t\t\t\tif(\$numfilasFiles>0)\n";
			$ClassPHP.="\t\t\t\t{\n";
			$ClassPHP.="\t\t\t\t\twhile(\$filaFiles= \$this->conexion->ObtenerSiguienteRegistro(\$resultadoFiles))\n";
			$ClassPHP.="\t\t\t\t\t{\n";
			$ClassPHP.="\t\t\t\t\t\tif(!\$this->GenerarDatosMultimedia(\$filaFiles,\$array[\$fila['".$arregloCampos['codigo']."']]['multimedias'],'multimediacod','".$arregloCampos['PrefijoMultimedia']."'))\n";
			$ClassPHP.="\t\t\t\t\t\t\treturn false;\n";
			$ClassPHP.="\t\t\t\t\t}\n";	
			$ClassPHP.="\t\t\t\t}\n\n";
			
		}
		$ClassPHP.="\t\t\t}\n";			
		$ClassPHP.="\t\t}\n";	
		$ClassPHP.="\t\treturn true;\n";
		$ClassPHP.="\t}\n\r\n\r\n\r";	
				
	}
	
	public function ClassGenerarDatosMultimedia($arregloCampos,&$ClassPHP)
	{
		
		$ClassPHP.="\tpublic function GenerarDatosMultimedia(\$fila,&\$arraymultimedia,\$id,\$prefijo)\n";
		$ClassPHP.="\t{\n";
		$ClassPHP.="\t\t\$arraytemp = array();\n";
		$ClassPHP.="\t\tswitch (\$fila['multimediaconjuntocod'])\n";
		$ClassPHP.="\t\t{\n";
		$ClassPHP.="\t\t\tcase FOTOS:\n";
		$ClassPHP.="\t\t\t\t\$arraytemp['codigo'] = \$fila[\$id];\n";
		$ClassPHP.="\t\t\t\t\$arraytemp['conjunto'] = \$fila['multimediaconjuntocod'];\n";
		$ClassPHP.="\t\t\t\t\$arraytemp['tipo'] = \$fila['multimediatipocod'];\n";
		$ClassPHP.="\t\t\t\t\$arraytemp['titulo'] = \$fila[\$prefijo.'multimediatitulo'];\n";
		$ClassPHP.="\t\t\t\t\$arraytemp['descripcion'] = \$fila[\$prefijo.'multimediadesc'];\n";
		$ClassPHP.="\t\t\t\t\$arraytemp['nombre_archivo'] = \$fila['multimedianombre'];\n";
		$ClassPHP.="\t\t\t\tif(isset(\$fila[\$prefijo.'multimediaorden']))\n";
		$ClassPHP.="\t\t\t\t\t\$arraytemp['orden'] = \$fila[\$prefijo.'multimediaorden'];\n";
		$ClassPHP.="\t\t\t\tif(isset(\$fila[\$prefijo.'multimediamuestrahome']))\n";
		$ClassPHP.="\t\t\t\t\t\$arraytemp['muestrahome'] = \$fila[\$prefijo.'multimediamuestrahome'];\n";
		$ClassPHP.="\t\t\t\t\$arraytemp['idexterno'] = \$fila['multimediaidexterno'];\n";
		$ClassPHP.="\t\t\t\t\$arraytemp['url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.\$fila['multimediaubic'];\n";
		$ClassPHP.="\t\t\t\tif(isset(\$fila['previewubic']) && \$fila['previewubic']!=\"\")\n";
		$ClassPHP.="\t\t\t\t\t\$arraytemp['url_preview'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.\$fila['previewubic'];\n";
		$ClassPHP.="\t\t\t\t\$arraymultimedia['fotos'][\$fila[\$id]] = \$arraytemp;\n";
		$ClassPHP.="\t\t\tbreak;\n";	
		$ClassPHP.="\t\t\tcase VIDEOS:\n";
		$ClassPHP.="\t\t\t\t\$arraytemp['codigo'] = \$fila[\$id];\n";
		$ClassPHP.="\t\t\t\t\$arraytemp['conjunto'] = \$fila['multimediaconjuntocod'];\n";
		$ClassPHP.="\t\t\t\t\$arraytemp['tipo'] = \$fila['multimediatipocod'];\n";
		$ClassPHP.="\t\t\t\t\$arraytemp['titulo'] = \$fila[\$prefijo.'multimediatitulo'];\n";
		$ClassPHP.="\t\t\t\t\$arraytemp['descripcion'] = \$fila[\$prefijo.'multimediadesc'];\n";
		$ClassPHP.="\t\t\t\t\$arraytemp['nombre_archivo'] = \$fila['multimedianombre'];\n";
		$ClassPHP.="\t\t\t\tif(isset(\$fila[\$prefijo.'multimediaorden']))\n";
		$ClassPHP.="\t\t\t\t\t\$arraytemp['orden'] = \$fila[\$prefijo.'multimediaorden'];\n";
		$ClassPHP.="\t\t\t\tif(isset(\$fila[\$prefijo.'multimediamuestrahome']))\n";
		$ClassPHP.="\t\t\t\t\t\$arraytemp['muestrahome'] = \$fila[\$prefijo.'multimediamuestrahome'];\n";
		$ClassPHP.="\t\t\t\t\$arraytemp['idexterno'] = \$fila['multimediaidexterno'];\n";
		$ClassPHP.="\t\t\t\tif(isset(\$fila['multimediaidexterno']) && \$fila['multimediaidexterno']!=\"\")\n";
		$ClassPHP.="\t\t\t\t\t\$arraytemp['url'] = \"\";\n";	
		$ClassPHP.="\t\t\t\telse\n";
		$ClassPHP.="\t\t\t\t\t\$arraytemp['url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.\"videos/\".\$fila['multimediaubic'];\n";
		$ClassPHP.="\t\t\t\tif(isset(\$fila['previewubic']) && \$fila['previewubic']!=\"\")\n";
		$ClassPHP.="\t\t\t\t\t\$arraytemp['url_preview'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.\$fila['previewubic'];\n";
		$ClassPHP.="\t\t\t\t\$arraymultimedia['videos'][\$fila[\$id]] = \$arraytemp;\n";
		$ClassPHP.="\t\t\tbreak;\n";	
		$ClassPHP.="\t\t\tcase AUDIOS:\n";
		$ClassPHP.="\t\t\t\t\$arraytemp['codigo'] = \$fila[\$id];\n";
		$ClassPHP.="\t\t\t\t\$arraytemp['conjunto'] = \$fila['multimediaconjuntocod'];\n";
		$ClassPHP.="\t\t\t\t\$arraytemp['tipo'] = \$fila['multimediatipocod'];\n";
		$ClassPHP.="\t\t\t\t\$arraytemp['titulo'] = \$fila[\$prefijo.'multimediatitulo'];\n";
		$ClassPHP.="\t\t\t\t\$arraytemp['descripcion'] = \$fila[\$prefijo.'multimediadesc'];\n";
		$ClassPHP.="\t\t\t\t\$arraytemp['nombre_archivo'] = \$fila['multimedianombre'];\n";
		$ClassPHP.="\t\t\t\tif(isset(\$fila[\$prefijo.'multimediaorden']))\n";
		$ClassPHP.="\t\t\t\t\t\$arraytemp['orden'] = \$fila[\$prefijo.'multimediaorden'];\n";
		$ClassPHP.="\t\t\t\tif(isset(\$fila[\$prefijo.'multimediamuestrahome']))\n";
		$ClassPHP.="\t\t\t\t\t\$arraytemp['muestrahome'] = \$fila[\$prefijo.'multimediamuestrahome'];\n";
		$ClassPHP.="\t\t\t\t\$arraytemp['idexterno'] = \$fila['multimediaidexterno'];\n";
		$ClassPHP.="\t\t\t\tif(isset(\$fila['multimediaidexterno']) && \$fila['multimediaidexterno']!=\"\")\n";
		$ClassPHP.="\t\t\t\t\t\$arraytemp['url'] = \"\";\n";	
		$ClassPHP.="\t\t\t\telse\n";
		$ClassPHP.="\t\t\t\t\t\$arraytemp['url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.\"audios/\".\$fila['multimediaubic'];\n";	
		$ClassPHP.="\t\t\t\tif(isset(\$fila['previewubic']) && \$fila['previewubic']!=\"\")\n";
		$ClassPHP.="\t\t\t\t\t\$arraytemp['url_preview'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.\$fila['previewubic'];\n";
		$ClassPHP.="\t\t\t\t\$arraymultimedia['audios'][\$fila[\$id]] = \$arraytemp;\n";
		$ClassPHP.="\t\t\tbreak;\n";	
		$ClassPHP.="\t\t\tcase FILES:\n";
		$ClassPHP.="\t\t\t\t\$arraytemp['codigo'] = \$fila[\$id];\n";
		$ClassPHP.="\t\t\t\t\$arraytemp['conjunto'] = \$fila['multimediaconjuntocod'];\n";
		$ClassPHP.="\t\t\t\t\$arraytemp['tipo'] = \$fila['multimediatipocod'];\n";
		$ClassPHP.="\t\t\t\t\$arraytemp['titulo'] = \$fila[\$prefijo.'multimediatitulo'];\n";
		$ClassPHP.="\t\t\t\t\$arraytemp['descripcion'] = \$fila[\$prefijo.'multimediadesc'];\n";
		$ClassPHP.="\t\t\t\t\$arraytemp['nombre_archivo'] = \$fila['multimedianombre'];\n";
		$ClassPHP.="\t\t\t\tif(isset(\$fila[\$prefijo.'multimediaorden']))\n";
		$ClassPHP.="\t\t\t\t\t\$arraytemp['orden'] = \$fila[\$prefijo.'multimediaorden'];\n";
		$ClassPHP.="\t\t\t\tif(isset(\$fila[\$prefijo.'multimediamuestrahome']))\n";
		$ClassPHP.="\t\t\t\t\t\$arraytemp['muestrahome'] = \$fila[\$prefijo.'multimediamuestrahome'];\n";
		$ClassPHP.="\t\t\t\t\$arraytemp['idexterno'] = \$fila['multimediaidexterno'];\n";
		$ClassPHP.="\t\t\t\tif(isset(\$fila['multimediaidexterno']) && \$fila['multimediaidexterno']!=\"\")\n";
		$ClassPHP.="\t\t\t\t\t\$arraytemp['url'] = \"\";\n";	
		$ClassPHP.="\t\t\t\telse\n";
		$ClassPHP.="\t\t\t\t\t\$arraytemp['url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ARCHIVOS.\$fila['multimediaubic'];\n";
		$ClassPHP.="\t\t\t\tif(isset(\$fila['previewubic']) && \$fila['previewubic']!=\"\")\n";
		$ClassPHP.="\t\t\t\t\t\$arraytemp['url_preview'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.\$fila['previewubic'];\n";
		$ClassPHP.="\t\t\t\t\$arraymultimedia['archivos'][\$fila[\$id]] = \$arraytemp;\n";
		$ClassPHP.="\t\t\tbreak;\n";	
		$ClassPHP.="\t\t}\n";
		$ClassPHP.="\t\treturn true;\n";
		$ClassPHP.="\t}\n\r\n\r\n\r";	
	}

}
?>