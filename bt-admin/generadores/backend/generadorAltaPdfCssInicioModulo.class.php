<?php  

class GenerarAltaPdfCssInicioModulo
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
			if (!is_dir($this->carpetaPaquete.$this->archivoModulo))
				mkdir($this->carpetaPaquete.$this->archivoModulo);
			if (!is_dir($this->carpetaPaquete.$this->archivoModulo."/js"))
				mkdir($this->carpetaPaquete.$this->archivoModulo."/js");
			if (!is_dir($this->carpetaPaquete.$this->archivoModulo."/css"))
				mkdir($this->carpetaPaquete.$this->archivoModulo."/css");
		}
	} 
	
	// Destructor de la clase
	public function __destruct() {	

    } 	


	

	public function GenerarArchivo($arregloCamposTabla,$arregloCampos,$arregloSp,$nombreClase)
	{
		
		/* CSS Document */
		$CSS = "body{font-family:Arial, Helvetica, sans-serif}\n";
		$CSS .= ".ancho_01, .ancho_05, .ancho_1, .ancho_2, .ancho_3, .ancho_4, .ancho_5, .ancho_6, .ancho_7, .ancho_8, .ancho_9, .ancho_10, .ancho_11, .ancho_12, .ancho_13, .ancho_14, .ancho_15, .ancho_16, .ancho_17, .ancho_18, .ancho_19, .ancho_20 {display:inline; float:left; margin-left: 0px; margin-right: 0px; }\n";
		$CSS .= ".concursonombre{text-align:center; font-size:18px; margin-bottom:50px; margin-top:20px; }\n";
		$CSS .= "h1{ font-size:16px; text-align:center;}\n";
		$CSS .= ".logo{border-bottom:1px solid #29abe2; padding-bottom:10px;}\n";
		$CSS .= ".footer{border-top:1px solid #29abe2; padding-top:10px; font-family:Arial, Helvetica, sans-serif}\n";
		$CSS .= ".titulofondo{background-color:#29abe2; color:#FFF; padding:5px 10px; text-align:center}\n";
		
		$CSS .= ".firmaaclaracion{ text-align:center; border-top:1px dashed #333333; font-size:12px; padding-top:5px;}\n";
		$CSS .= ".separacionfirma{ height:120px;}\n";
		
		$CSS .= ".barcode{float:right; text-align:right; width:130px;}\n";
		$CSS .= ".marginTop{margin-top:20px;}\n";
		$CSS .= ".barcode .numero{ text-align:center; font-size:10px;}\n";
		$CSS .= ".ancho_01 { width: 1%;}\n";
		$CSS .= ".ancho_05 { width: 5%;}\n";
		$CSS .= ".ancho_1 { width: 10%;}\n";
		$CSS .= ".ancho_2 { width: 20%;}\n";
		$CSS .= ".ancho_3 { width: 30%;}\n";
		$CSS .= ".ancho_4 { width: 40%;}\n";
		$CSS .= ".ancho_5 { width: 50%;}\n";
		$CSS .= ".ancho_6 { width: 60%;}\n";
		$CSS .= ".ancho_7 { width: 70%;}\n";
		$CSS .= ".ancho_8 { width: 80%;}\n";
		$CSS .= ".ancho_9 { width: 90%;}\n";
		$CSS .= ".ancho_10 { width: 100%;}\n";
		
		$CSS .= ".textocentrado{text-align:center !important;}\n";
		$CSS .= ".tituloizq{ font-size:1.000em;}\n";
		$CSS .= ".txtrespuesta{color:#333; font-size:1.000em;}\n";
		$CSS .= ".cajatexto{ width:100% !important; border:1px solid; border-radius: 5px 5px 5px 5px; padding:2px 2px 2px 2px;}\n";
		$CSS .= ".cajatexarea{ width:100% !important; border:1px solid; border-radius: 5px 5px 5px 5px; height:100px; min-height:100px !important; padding:2px 2px 2px 2px;}\n";
		$CSS .= ".subrayado{ text-decoration:underline;}\n";
		$CSS .= ".clearboth{clear:both}\n";
		$CSS .= ".aire { padding:5px; }\n";
		$CSS .= ".aire_vertical { padding:5px 0; }\n";
		$CSS .= ".brisa_vertical{height:4px; font-size:4px;}\n";
		$CSS .= ".aire_menor{padding:1px 0;}\n";
		$CSS .= ".brisa_horizontal{padding:0 1px;}\n";
		
		$CSS .= ".tituloseparador {padding: 5px 0px; font-size:1.500em; border-bottom:1px solid #CCC; margin-right:200px; margin-top:10px;}\n";
		
		$CSS .= ".table{width: 100%;max-width: 100%;}\n";
		$CSS .= "table{border-collapse: collapse;}\n";
		$CSS .= "table, th, td {border: 1px solid black;}\n";
		$CSS .= ".list{ color:#666; font-size:1em}\n";
		
		$CSS .= ".negrita { font-weight:bold}\n";
		$CSS .= ".margen_sup{ margin-top:20px}\n";
		$CSS .= ".caja{ border:1px solid #999; padding:0px 10px 10px 10px;-moz-border-radius: 15px 15px 15px 15px;-webkit-border-radius: 15px 15px 15px 15px;border-radius: 15px 15px 15px 15px;}\n";
		
		$CSS .= ".hr{ width:450px; text-align:center;  margin:20px 0 20px 0 }\n";
		
		
		$CSS .= "table { font-size: 12px;margin: 45px;text-align: left; border:none }\n";
		$CSS .= "th { font-size: 13px;font-weight: normal;padding: 8px; background: #b9c9fe;   border-bottom: 1px solid #fff; color: #039;border-right:none; border-left:none }\n";
		$CSS .= "td {padding: 8px;background: #e8edff; border-bottom: 1px solid #fff;color: #669; border-top: 1px solid transparent;  border-right:none; border-left:none }\n";
		$CSS .= "tr:hover td { background: #d0dafd; color: #339; }\n";
		$CSS .= "tr{border-right:none; border-left:none}\n";
		
		$CSS .= ".oscuro{background: #e8edff !important; }\n";
		$CSS .= ".claro{background:#F4F8FB   !important;}\n";
		
		$CSS .= ".seleccion:after{content: '\f046'; color:#FFFFFF; font-size:22px; float:right}\n";
		$CSS .= ".deseleccion:after{content: '\f096'; color:#169ECE; font-size:22px; float:right}\n";
		$CSS .= ".Total{ font-weight:bold; color:#000}\n";
		$CSS .= ".SubTotal{ font-weight:bold; color:#000; text-align:right}\n";
		
		if($this->generarSitio && FuncionesPHPLocal::GuardarArchivo(DIR_ROOT."modulos/".$this->archivoModulo."/css/",$CSS,$this->archivoModulo.".css"))
		{
			$msgactualizacion = "Se ha publicado correctamente.";
			$ret['IsSuccess'] = true;
		}
		
		if($this->generarPaquete && FuncionesPHPLocal::GuardarArchivo($this->carpetaPaquete.$this->archivoModulo."/css/",$CSS,$this->archivoModulo.".css"))
		{
			$msgactualizacion = "Se ha publicado correctamente.";
			$ret['IsSuccess'] = true;
		}
	
	}
	
}
	
?>