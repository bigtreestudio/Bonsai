<?php  

class GenerarAltaPdfInicioModulo 
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
		
		$ClassPHP = $this->Encabezado();
		
		
		$ClassPHP .= "if (!isset(\$_GET[\"".$arregloCampos['codigo']."\"]) || \$_GET[\"".$arregloCampos['codigo']."\"]==\"\")\n";
		$ClassPHP .= "\tdie();\n\r";
		
		$ClassPHP .= "\$oObjeto = new ".$nombreClase."(\$conexion,\"\");\n";
		$ClassPHP .= "\$oObjetoPDF = new ".$nombreClase."PDF(\$conexion,\"\");\n";
		
		
		
		$ClassPHP .= "if(!\$oObjeto->BuscarxCodigo (\$_GET,\$resultado,\$numfilas))\n";
		$ClassPHP .= "\tdie();\n\r";
		
		$ClassPHP .= "if (\$numfilas>0)\n";
		$ClassPHP .= "{\n\r";	
		$ClassPHP .= "\t\$fila = \$conexion->ObtenerSiguienteRegistro(\$resultado);\n";
		$ClassPHP .= "\t\$oObjetoPDF->GenerarPdf(\$fila,\"I\");\n\r";
		$ClassPHP .= "}\n\r";	

		$ClassPHP .= $this->Pie(false);
		
		if($this->generarSitio && FuncionesPHPLocal::GuardarArchivo(DIR_ROOT,$ClassPHP,$this->archivoModulo."_pdf.php"))
		{
			$msgactualizacion = "Se ha publicado correctamente.";
			$ret['IsSuccess'] = true;
		}
		
		if($this->generarPaquete && FuncionesPHPLocal::GuardarArchivo($this->carpetaPaquete,$ClassPHP,$this->archivoModulo."_pdf.php"))
		{
			$msgactualizacion = "Se ha publicado correctamente.";
			$ret['IsSuccess'] = true;
		}
	
	}
	
	public function Encabezado($cargaencabezado=true)
	{
		$ClassPHP = "<?php \n";	
		
		$ClassPHP .= "require(\"./config/include.php\");\n";
		$ClassPHP .= "require_once(DIR_LIBRERIAS.\"/MPDF60/mpdf.php\");\n\r";
		
		$ClassPHP .= "\$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);\n";
		$ClassPHP .= "\$conexion->SeleccionBD(BASEDATOS);\n\r";
		
		$ClassPHP .= "FuncionesPHPLocal::CargarConstantes(\$conexion,array(\"roles\"=>\"si\",\"sistema\"=>SISTEMA));\n";
		$ClassPHP .= "\$conexion->SetearAdmiGeneral(ADMISITE);\n\r";
		
		$ClassPHP .= "\$sesion = new Sesion(\$conexion,false);\n";
		$ClassPHP .= "\$sesion->TienePermisos(\$conexion,\$_SESSION['usuariocod'],\$_SESSION['rolcod'],\$_SERVER['PHP_SELF']);\n\r";
		
		$ClassPHP .= "\$oSistemaBloqueo = new SistemaBloqueo();\n";
		$ClassPHP .= "\$oSistemaBloqueo->VerificarBloqueo(\$conexion);\n\r";
		
		
		return $ClassPHP;
	}
	
	
	public function Pie($cargapie=true)
	{
		$ClassPHP = "\n";
		if ($cargapie)
			$ClassPHP .= "\$oEncabezados->PieMenuEmergente();\n\r";
		$ClassPHP .= "?>";
		
		return $ClassPHP;
	}
	
}
	
?>