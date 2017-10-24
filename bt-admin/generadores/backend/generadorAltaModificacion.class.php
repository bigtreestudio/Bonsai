<?php  
class cGeneradorAltaModificacion 
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
    } 

	// Destructor de la clase
	public function __destruct() {	

    } 	


	public function GenerarArchivo($arregloCamposTabla,$arregloCampos,$arregloSp,$nombreClase,$archivo)
	{

		$ClassPHP = $this->EncabezadoAM();
		$ClassPHP .= "\$oObjeto = new ".$nombreClase."(\$conexion);\n\r";
		if ($arregloCampos['otroscampos']['TieneMultimedia']==1)
		{
			$ClassPHP .= "\$oMultimedia = new cMultimedia(\$conexion,\"noticias/\");\n\r";
		}
		
		$ClassPHP .= "\$esmodif = false;\n";
		$ClassPHP .= "\$botonejecuta = \"BtAlta\";\n";
		$ClassPHP .= "\$boton = \"Alta\";\n";
		$ClassPHP .= "\$onclick = \"return Insertar();\";\n";
		foreach ($arregloCampos['camposTabla'] as $Campos)
		{	
			if ($Campos['Field']!="ultmodusuario" && $Campos['Field']!="ultmodfecha")
				$ClassPHP .= "\$".$Campos['Field']." = \"\";\n";
		}
		
		$ClassPHP .= "if (isset(\$_GET['".$arregloCampos['codigo']."']) && \$_GET['".$arregloCampos['codigo']."']!=\"\")\n";
		$ClassPHP .= "{\n";
		$ClassPHP .= "\t\$esmodif = true;\n";
		$ClassPHP .= "\t\$datos = \$_GET;\n";
		$ClassPHP .= "\tif(!\$oObjeto->BuscarxCodigo(\$datos,\$resultado,\$numfilas))\n";
		$ClassPHP .= "\t\treturn false;\n";
		$ClassPHP .= "\tif(\$numfilas!=1){\n";
		$ClassPHP .= "\t\tFuncionesPHPLocal::MostrarMensaje(\$conexion,MSG_ERRGRAVE,\"Codigo inexistente.\",array(\"archivo\" => __FILE__,\"funcion\" => __FUNCTION__, \"linea\" => __LINE__),array(\"formato\"=>FMT_TEXTO));\n";
		$ClassPHP .= "\t\treturn false;\n";
		$ClassPHP .= "\t}\n";
		$ClassPHP .= "\t\$datosregistro = \$conexion->ObtenerSiguienteRegistro(\$resultado);\n";
		$ClassPHP .= "\t\$onclick = \"return Modificar();\";\n";
		foreach ($arregloCampos['camposTabla'] as $Campos)
		{	
			if ($Campos['Field']!="ultmodusuario" && $Campos['Field']!="ultmodfecha")
			{
				if (substr($Campos['Type'],0,4)=="date" || substr($Campos['Type'],0,8)=="datetime")
					$ClassPHP .= "\t\$".$Campos['Field']." = FuncionesPHPLocal::ConvertirFecha(\$datosregistro[\"".$Campos['Field']."\"],'aaaa-mm-dd','dd/mm/aaaa');\n";
				else
					$ClassPHP .= "\t\$".$Campos['Field']." = \$datosregistro[\"".$Campos['Field']."\"];\n";
			}
		}
		$ClassPHP .= "}\n";
		
		
		
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
		
		
		
		$ClassPHP .= FuncionesPHPLocal::RenderFile("generadores/templates/template_alta_una.php",$arregloCampos);
		$ClassPHP .= "\n\r<?php \n";
		
		$ClassPHP .= $this->PieAM();
		
		
		if($this->generarSitio && FuncionesPHPLocal::GuardarArchivo(DIR_ROOT,$ClassPHP,$archivo."_am.php"))
		{
			$msgactualizacion = "Se ha publicado correctamente.";
			$ret['IsSuccess'] = true;
		}
		
		if($this->generarPaquete && FuncionesPHPLocal::GuardarArchivo($this->carpetaPaquete,$ClassPHP,$archivo."_am.php"))
		{
			$msgactualizacion = "Se ha publicado correctamente.";
			$ret['IsSuccess'] = true;
		}

	}
	
	
	public function EncabezadoAM($cargaencabezado=true)
	{
		$ClassPHP = "<?php \n";	
		
		$ClassPHP .= "require(\"./config/include.php\");\n\r";
		
		$ClassPHP .= "\$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);\n";
		$ClassPHP .= "\$conexion->SeleccionBD(BASEDATOS);\n\r";
		
		$ClassPHP .= "FuncionesPHPLocal::CargarConstantes(\$conexion,array(\"roles\"=>\"si\",\"sistema\"=>SISTEMA,\"multimedia\"=>\"si\"));\n";
		$ClassPHP .= "\$conexion->SetearAdmiGeneral(ADMISITE);\n\r";
		
		$ClassPHP .= "\$sesion = new Sesion(\$conexion,false);\n";
		$ClassPHP .= "\$sesion->TienePermisos(\$conexion,\$_SESSION['usuariocod'],\$_SESSION['rolcod'],\$_SERVER['PHP_SELF']);\n\r";
		
		$ClassPHP .= "\$oSistemaBloqueo = new SistemaBloqueo();\n";
		$ClassPHP .= "\$oSistemaBloqueo->VerificarBloqueo(\$conexion);\n\r";
		
		if ($cargaencabezado)
		{
			$ClassPHP .= "\$oEncabezados = new cEncabezados(\$conexion);\n";
			$ClassPHP .= "\$oEncabezados->EncabezadoMenuEmergente(\$_SESSION['rolcod'],\$_SESSION['usuariocod']);\n\r";
		}
		
		return $ClassPHP;
	}
	
	
	public function PieAM($cargapie=true)
	{
		$ClassPHP ="";
		if ($cargapie)
			$ClassPHP .= "\$oEncabezados->PieMenuEmergente();\n\r";
		$ClassPHP .= "?>";
		
		return $ClassPHP;
	}
	
}

?>