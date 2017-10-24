<?php 
class generadorLogicaFront 
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
		$ClassPHP = "<?php\n";	
		if($arregloCampos['FrontListado']=="1")
			$ClassPHP .= "include(DIR_CLASES_DB.\"".$nombreClase.".db.php\");\n\n";

		$ClassPHP .= "class ".$nombreClase;
		if($arregloCampos['FrontListado']=="1")
			$ClassPHP .= " extends ".$nombreClase."db";
		$ClassPHP .= "\n";;
		
		$ClassPHP .= "{\n\r";
		
		$ClassPHP .= "\tprotected \$conexion;\n";
		
		$ClassPHP .= "\tfunction __construct(\$conexion){\n";
		$ClassPHP .= "\t\t\$this->conexion = &\$conexion;\n";
		$ClassPHP .= "\t}\n\r";
		
		
		$this->ClassBuscarxCodigoFront($arregloCampos,$ClassPHP);
		if (isset($arregloCampos['JsonListado']) && $arregloCampos['JsonListado']==1)
			$this->ClassBusquedaListadoFront($arregloCampos,$ClassPHP);
		
		if($arregloCampos['FrontListado']=="1")	
			$this->ClassBusquedaAvanzadaFront($arregloCamposTabla,$arregloCampos,$ClassPHP);
		
			$this->ClassSpTablaExterna($arregloSp,$ClassPHP);
		$ClassPHP .= "\n\r\n\r}\n?>";
		
		
		
		if($this->generarSitio && FuncionesPHPLocal::GuardarArchivo(DOCUMENT_ROOT."/Clases/",$ClassPHP,$nombreClase.".class.php"))
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


	private function ClassBuscarxCodigoFront($arregloCampos,&$ClassPHP)
	{
		$ClassPHP.="\tpublic function BuscarxCodigo(\$datos)\n";
		$ClassPHP.="\t{\n";
		
		$carpeta = $arregloCampos['CarpetaJson'];
		if($carpeta!="")
		{
			$rest = substr($arregloCampos['CarpetaJson'], -1);
			if($rest!="/")
				$carpeta .= "/";	
		}		
		
		if (isset($arregloCampos['JsonCodigo']) && $arregloCampos['JsonCodigo']==1)
		{
			$ClassPHP.="\t\t\$archivo = \"".$arregloCampos['archivo']."_\".\$datos['".$arregloCampos['codigo']."'].\".json\";\n";
			$ClassPHP.="\t\tif(file_exists(PUBLICA.\"json/".$carpeta."\".\$archivo))\n";
			$ClassPHP.="\t\t{\n";
			$ClassPHP.="\t\t\t\$string = file_get_contents(PUBLICA.\"json/".$carpeta."\".\$archivo);\n";
			$ClassPHP.="\t\t\t\$arrayJson = json_decode(\$string,true);\n";
			$ClassPHP.="\t\t\t\$array = FuncionesPHPLocal::ConvertiraUtf8(\$arrayJson[\$datos['".$arregloCampos['codigo']."']]);\n";
			$ClassPHP.="\t\t\treturn \$array;\n";
			$ClassPHP.="\t\t}\n";
			$ClassPHP.="\t\telse\n";
			$ClassPHP.="\t\t\treturn false;\n";
		}
		else
		{
			$ClassPHP.="\t\t\$archivo = \"".$arregloCampos['archivo'].".json\";\n";
			$ClassPHP.="\t\tif(isset(\$datos['".$arregloCampos['codigo']."']) && \$datos['".$arregloCampos['codigo']."']!=\"\" && file_exists(PUBLICA.\"json/".$carpeta."\".\$archivo))\n";
			$ClassPHP.="\t\t{\n";
			$ClassPHP.="\t\t\t\$string = file_get_contents(PUBLICA.\"json/".$carpeta."\".\$archivo);\n";
			$ClassPHP.="\t\t\t\$arrayJSON = json_decode(\$string,true);\n";
			$ClassPHP.="\t\t\t\$array = FuncionesPHPLocal::ConvertiraUtf8(\$arrayJson);\n";
			$ClassPHP.="\t\t\tif (isset(\$array[\$datos['".$arregloCampos['codigo']."']]))\n";
			$ClassPHP.="\t\t\t\treturn \$array[\$datos['".$arregloCampos['codigo']."']];\n";
			$ClassPHP.="\t\t\telse\n";
			$ClassPHP.="\t\t\t\treturn false;\n";
			$ClassPHP.="\t\t}\n";
			$ClassPHP.="\t\telse\n";
			$ClassPHP.="\t\t{\n";
			$ClassPHP.="\t\t\treturn false;\n";
			$ClassPHP.="\t\t}\n";
		}
		
		$ClassPHP.="\t}\n\r\n\r\n\r";		
			
		return true;	
	}
	
	private function ClassBusquedaListadoFront($arregloCampos,&$ClassPHP)
	{
		
		$ClassPHP.="\tpublic function BusquedaListado()\n";
		$ClassPHP.="\t{\n";
		
		$carpeta = $arregloCampos['CarpetaJson'];
		if($carpeta!="")
		{
			$rest = substr($arregloCampos['CarpetaJson'], -1);
			if($rest!="/")
				$carpeta .= "/";	
		}	
		
		$ClassPHP.="\t\t\$archivo = \"".$arregloCampos['archivo'].".json\";\n";
		$ClassPHP.="\t\tif(file_exists(PUBLICA.\"json/".$carpeta."\".\$archivo))\n";
		$ClassPHP.="\t\t{\n";
		$ClassPHP.="\t\t\t\$string = file_get_contents(PUBLICA.\"json/".$carpeta."\".\$archivo);\n";
		$ClassPHP.="\t\t\t\$arrayJson = json_decode(\$string,true);\n";
		$ClassPHP.="\t\t\t\$array = FuncionesPHPLocal::ConvertiraUtf8(\$arrayJson);\n";
		$ClassPHP.="\t\t\treturn \$array;\n";
		$ClassPHP.="\t\t}\n";
		$ClassPHP.="\t\telse\n";
		$ClassPHP.="\t\t\treturn false;\n";
		$ClassPHP.="\t}\n\r\n\r\n\r";
	
	}
	
	
	public function ClassBusquedaAvanzadaFront($arregloCamposTabla,$arregloCampos,&$ClassPHP)
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
			$ClassPHP.="\t\t\t'".$arregloCampos['campoEstado']."'=> \"10\",\n";
			
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
			$ClassPHP.="\t\t\tFuncionesPHPLocal::MostrarMensaje(\$this->conexion,\"Error al buscar el archivo multimedia por codigo y multimedia. \",array(\"archivo\" => __FILE__,\"funcion\" => __FUNCTION__, \"linea\" => __LINE__),array(\"formato\"=>\"\"));\n";
			$ClassPHP.="\t\t\treturn false;\n";
			$ClassPHP.="\t\t}\n\r";
			$ClassPHP.="\t\treturn true;\n";
			$ClassPHP.="\t}\n\r\n\r\n\r";		
		}
	}
	
}

?>