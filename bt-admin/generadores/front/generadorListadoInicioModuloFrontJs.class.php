<?php  

class GenerarListadoInicioModuloFrontJs 
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
		
		if($this->generarPaquete)
		{
			if (!is_dir($this->carpetaPaquete))
				mkdir($this->carpetaPaquete);
			if (!is_dir($this->carpetaPaquete."/js"))
				mkdir($this->carpetaPaquete."/js");
			if (!is_dir($this->carpetaPaquete."/js".$this->archivoModulo))
				mkdir($this->carpetaPaquete."/js".$this->archivoModulo);	
			
		}
		
	} 

	// Destructor de la clase
	public function __destruct() {	

    } 	


	public function GenerarArchivo($arregloCamposTabla,$arregloCampos,$arregloSp,$nombreClase)
	{
		$JsBusqueda = "function Buscar(nropagina){\n";
		$JsBusqueda .= "\tvar param = \$(\"#formbusqueda\").serialize();\n";
		$JsBusqueda .= "\tparam+=\"&pagina=\"+nropagina;\n";
		$JsBusqueda .= "\t\t\$.ajax({\n";
	    $JsBusqueda .= "\t\ttype: \"POST\",\n";
	    $JsBusqueda .= "\t\turl: \"/biblioteca_lst.php\",\n";
	    $JsBusqueda .= "\t\tdata: param,\n";
	    $JsBusqueda .= "\t\tdataType:\"html\",\n";
	    $JsBusqueda .= "\t\tsuccess: function(msg){\n";
		$JsBusqueda .= "\t\t\t\$(\"#resultadoslst\").html(msg);\n";
	    $JsBusqueda .= "\t\t}\n";
		$JsBusqueda .= "\t});\n\r";
		$JsBusqueda .= "}\n";

		if($this->generarSitio && FuncionesPHPLocal::GuardarArchivo(DOCUMENT_ROOT."js/",$JsBusqueda,$this->archivoModulo.".js"))
		{
			$msgactualizacion = "Se ha publicado correctamente.";
			$ret['IsSuccess'] = true;
		}
		if($this->generarPaquete && FuncionesPHPLocal::GuardarArchivo($this->carpetaPaquete."js/",$JsBusqueda,$this->archivoModulo.".js"))
		{
			$msgactualizacion = "Se ha publicado correctamente.";
			$ret['IsSuccess'] = true;
		}
	
	
	}
	
	

}
?>