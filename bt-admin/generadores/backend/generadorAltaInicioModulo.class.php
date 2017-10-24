<?php  

class GenerarAltaInicioModulo 
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

		$ClassPHP .= FuncionesPHPLocal::RenderFile("generadores/templates/templateform.php",$arregloCampos);
		//$tem = FuncionesPHPLocal::RenderFile("generadores/templates/templateform.php",$arregloCampos);
		$ClassPHP .= $this->Pie();
		
		
		if($this->generarSitio && FuncionesPHPLocal::GuardarArchivo(DIR_ROOT,$ClassPHP,$this->archivoModulo.".php"))
		{
			$msgactualizacion = "Se ha publicado correctamente.";
			$ret['IsSuccess'] = true;
		}
		if($this->generarPaquete && FuncionesPHPLocal::GuardarArchivo($this->carpetaPaquete,$ClassPHP,$this->archivoModulo.".php"))
		{
			$msgactualizacion = "Se ha publicado correctamente.";
			$ret['IsSuccess'] = true;
		}
	
	
	}
	
	public function Encabezado($cargaencabezado=true)
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
	
	
	public function Pie($cargapie=true)
	{
		$ClassPHP = "<?php \n";
		if ($cargapie)
			$ClassPHP .= "\$oEncabezados->PieMenuEmergente();\n\r";
		$ClassPHP .= "?>";
		
		return $ClassPHP;
	}

}
?>