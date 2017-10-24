<?php  

class GenerarLstInicioModuloFront 
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
		
		
		$ClassPHP .= "include(\"".$this->archivoModulo."_lst_ajax.php\");\n\r";
		
		$ClassPHP .= "?>\n";

		if($this->generarSitio && FuncionesPHPLocal::GuardarArchivo(DOCUMENT_ROOT,$ClassPHP,$this->archivoModulo."_lst.php"))
		{
			$msgactualizacion = "Se ha publicado correctamente.";
			$ret['IsSuccess'] = true;
		}
		if($this->generarPaquete && FuncionesPHPLocal::GuardarArchivo($this->carpetaPaquete,$ClassPHP,$this->archivoModulo."_lst.php"))
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
		
		$ClassPHP .= "\$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);\n";
		$ClassPHP .= "\$conexion->SeleccionBD(BASEDATOS);\n\r";
		
		$ClassPHP .= "FuncionesPHPLocal::CargarConstantes(\$conexion,array(\"multimedia\"=>\"si\"));\n\r";
		
		$ClassPHP .= "\$oEncabezados = new cEncabezados(\$conexion);\n\r";
		
		return $ClassPHP;
	}
	
}
?>