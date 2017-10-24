<?php  
class cGeneradorJSAltaModificacion 
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
		$esFecha=false;
		$tinymce=false;
		$tinymceAvanzado=false;
		$JsBusqueda = "";
		$arregloCampoTiny = array();
		$tienemultimediasimple =false;
		if($arregloCampos['TieneMultimedia']=="1")
			$tienemultimediasimple =true;
		
		foreach($arregloCampos['otroscampos']['campoalta'] as $Campos)
		{
			if ($arregloCampos['otroscampos']['camposaltatipo_'.$Campos]==2 )
			{
				$tinymce=true;
				$arregloCampoTiny[$Campos] = $Campos;
			}
			if ($arregloCampos['otroscampos']['camposaltatipo_'.$Campos]==3 )
			{
				$tinymceAvanzado=true;
				$arregloCampoTiny[$Campos] = $Campos;
			}
			
			
			if ($arregloCampos['otroscampos']['camposaltatipo_'.$Campos]==3)
				$esFecha=true;
		}
		
		if ($esFecha || $tinymce)
		{
			$JsBusqueda = "jQuery(document).ready(function(){\n";
			if ($esFecha)
				$JsBusqueda .= "\t\$(\".fechacampo\").datepicker( {dateFormat:\"dd/mm/yy\"});\n";
			if ($tinymce)
				$JsBusqueda .= "\tinitTextEditors();\n";	
			if ($tinymceAvanzado)
				$JsBusqueda .= "\tinitTextEditorsAvanzado();\n";		
			$JsBusqueda .= "});\n\r";
		}
		
		
		
		if (count($arregloCampoTiny)>0)
		{
			$JsBusqueda .= "function InsertarTiny(){\n";
			foreach($arregloCampoTiny as $CampoTiny)
			{
				$JsBusqueda .= "\tvar ".$CampoTiny." = tinyMCE.get('".$CampoTiny."');\n";
				$JsBusqueda .= "\t\$(\"#".$CampoTiny."\").val(".$CampoTiny.".getContent());\n";
			}
			$JsBusqueda .= "}\n\r\r";
		}
		
		
		$JsBusqueda .= "function Insertar(){\n";
		$JsBusqueda .= "\tvar param;\n";
		if ($tinymce || $tinymceAvanzado)
			$JsBusqueda .= "\tInsertarTiny();\n";
		$JsBusqueda .= "\t\$.blockUI({ message: '<div style=\"font-size:20px; font-weight:bold\"><img src=\"./images/cargando.gif\" />Agregando...</h1>',baseZ: 9999999999 });\n";
		$JsBusqueda .= "\tparam = $(\"#formalta\").serialize();\n";
		if($tienemultimediasimple)
			$JsBusqueda .= "\tparam += '&'+$(\"#formaltamultimediasimple\").serialize();\n";
		$JsBusqueda .= "\tparam += \"&accion=1\";\n";
		$JsBusqueda .= "\tEnviarDatosInsertarModificar(param,1);\n";
		$JsBusqueda .= "\treturn true;\n";
		$JsBusqueda .= "}\n\r\r";
		
		$JsBusqueda .= "function Modificar(){\n";
		$JsBusqueda .= "\tvar param;\n";
		if ($tinymce || $tinymceAvanzado)
			$JsBusqueda .= "\tInsertarTiny();\n";
		$JsBusqueda .= "\t\$.blockUI({ message: '<div style=\"font-size:20px; font-weight:bold\"><img src=\"./images/cargando.gif\" />Actualizando...</h1>',baseZ: 9999999999 });\n";
		$JsBusqueda .= "\tparam = $(\"#formalta\").serialize();\n";
		if($tienemultimediasimple)
			$JsBusqueda .= "\tparam += '&'+$(\"#formaltamultimediasimple\").serialize();\n";
		$JsBusqueda .= "\tparam += \"&accion=2\";\n";
		$JsBusqueda .= "\tEnviarDatosInsertarModificar(param,2);\n";
		$JsBusqueda .= "\treturn true;\n";
		$JsBusqueda .= "}\n\r\r";
		
		
		$JsBusqueda .= "function EnviarDatosInsertarModificar(param,tipo){\n";
		$JsBusqueda .= "\t\$.ajax({\n";
		$JsBusqueda .= "\t\ttype: \"POST\",\n";
		$JsBusqueda .= "\t\turl: \"".$this->archivoModulo."_upd.php\",\n";
		$JsBusqueda .= "\t\tdata: param,\n";
		$JsBusqueda .= "\t\tdataType:\"json\",\n";
		$JsBusqueda .= "\t\tsuccess: function(msg){\n";
		$JsBusqueda .= "\t\t\tif (msg.IsSucceed==true)\n";
		$JsBusqueda .= "\t\t\t{\n";
		$JsBusqueda .= "\t\t\t\t\$(\".msgaccionupd\").html(msg.Msg);\n";
		$JsBusqueda .= "\t\t\t\tif (tipo==1)\n";
		$JsBusqueda .= "\t\t\t\t\twindow.location=msg.header;\n";
		$JsBusqueda .= "\t\t\t\t\$.unblockUI();\n";
		$JsBusqueda .= "\t\t\t}\n";
		$JsBusqueda .= "\t\t\telse\n";
		$JsBusqueda .= "\t\t\t{\n";
		$JsBusqueda .= "\t\t\t\talert(msg.Msg);\n";
		$JsBusqueda .= "\t\t\t\t\$.unblockUI();\n";
		$JsBusqueda .= "\t\t\t}\n";
		$JsBusqueda .= "\t\t}\n";
		$JsBusqueda .= "\t});\n";
		$JsBusqueda .= "}\n";
		
		
		
		if($this->generarSitio && FuncionesPHPLocal::GuardarArchivo(DIR_ROOT."modulos/".$this->archivoModulo."/js/",$JsBusqueda,$this->archivoModulo."_am.js"))
		{
			$msgactualizacion = "Se ha publicado correctamente.";
			$ret['IsSuccess'] = true;
		}
		
		if($this->generarPaquete && FuncionesPHPLocal::GuardarArchivo($this->carpetaPaquete.$this->archivoModulo."/js/",$JsBusqueda,$this->archivoModulo."_am.js"))
		{
			$msgactualizacion = "Se ha publicado correctamente.";
			$ret['IsSuccess'] = true;
		}


	}
	
	
}
?>