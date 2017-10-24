<?php  

class GenerarListadoInicioModuloFront 
{

	protected $conexion;
	protected $carpetaPaquete;
	protected $archivoModulo;
	protected $generarSitio;
	protected $generarPaquete;

	public function __construct($conexion,$carpetaPaquete,$archivoModulo,$generarSitio=true,$generarPaquete=true){
		$this->conexion = &$conexion;
		$this->carpetaPaquete = $carpetaPaquete;
		$this->archivoModulo = $archivoModulo;
		$this->generarSitio = $generarSitio;
		$this->generarPaquete = $generarPaquete;
		
	} 

	// Destructor de la clase
	public function __destruct() {	

    } 	


	public function GenerarArchivo($arregloCamposTabla,$arregloCampos,$arregloSp,$nombreClase)
	{

		
		
		$ClassPHP = $this->Encabezado(true,$arregloCampos,$nombreClase);
		
		$ClassPHP .= "\$oObjeto = new ".$nombreClase."(\$conexion);\n\r";
		foreach($arregloSp as $tableFk)
		{
			$tabla = $tableFk['tabla'];
			$fk = $tableFk['fk'];
			$desc = $tableFk['desc'];
			$NombreFuncion = $tabla."SP";
			$ClassPHP .= "if(!\$oObjeto->".$NombreFuncion."Result(\$result_".$tabla.",\$numfilas_".$tabla."))\n";
			$ClassPHP .= "\treturn false;\n";
		}
		
		$ClassPHP .= "?>\n";

		$ClassPHP .= FuncionesPHPLocal::RenderFile("generadores/templates/template_front_listado.php",$arregloCampos);
		//$tem = FuncionesPHPLocal::RenderFile("generadores/templates/templateform.php",$arregloCampos);
		$ClassPHP .= $this->Pie();
		
		
		if($this->generarSitio && FuncionesPHPLocal::GuardarArchivo(DOCUMENT_ROOT,$ClassPHP,$this->archivoModulo."_busqueda.php"))
		{
			$msgactualizacion = "Se ha publicado correctamente.";
			$ret['IsSuccess'] = true;
		}
		if($this->generarPaquete && FuncionesPHPLocal::GuardarArchivo($this->carpetaPaquete,$ClassPHP,$this->archivoModulo."_busqueda.php"))
		{
			$msgactualizacion = "Se ha publicado correctamente.";
			$ret['IsSuccess'] = true;
		}
	
	
	}
	
	public function Encabezado($cargaencabezado=true,$arregloCampos,$nombreClase)
	{
		
		$ClassPHP = "<?php \n";	
		$ClassPHP .= "ob_start();\n";
		$ClassPHP .= "require(\"./config/include.php\");\n";
		$ClassPHP .= "include(DIR_CLASES.\"".$nombreClase.".class.php\");\n\r";
		
		$ClassPHP .= "\$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);\n";
		$ClassPHP .= "\$conexion->SeleccionBD(BASEDATOS);\n\r";
		
		$ClassPHP .= "FuncionesPHPLocal::CargarConstantes(\$conexion,array(\"multimedia\"=>\"si\"));\n\r";
		
		$ClassPHP .= "\$oEncabezados = new cEncabezados(\$conexion);\n\r";
		
		$ClassPHP .= "\$oEncabezados->setTitle(\"".$arregloCampos['otroscampos']['titulopantalla']."\");\n";
		$ClassPHP .= "\$oEncabezados->setOgTitle(\"".$arregloCampos['otroscampos']['titulopantalla']."\");\n";
		$ClassPHP .= "\$oEncabezados->EncabezadoMenuEmergente();\n";
		
		return $ClassPHP;
	}
	
	
	public function Pie($cargapie=true)
	{
		$ClassPHP = "<?php \n";
		if ($cargapie)
		{
			$ClassPHP .= "\$oEncabezados->PieMenuEmergente();\n";
			$ClassPHP .= "ob_end_flush();\n";
		}
		$ClassPHP .= "?>";
		
		return $ClassPHP;
	}

}
?>