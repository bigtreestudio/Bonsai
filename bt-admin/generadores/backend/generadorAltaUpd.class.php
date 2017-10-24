<?php  
class GenerarAltaUpd 
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
		$ClassPHP .= "\$oObjeto = new ".$nombreClase."(\$conexion,\"\");\n\n";
		
		$ClassPHP .= "switch(\$datos['accion'])\n";
		$ClassPHP .= "{\n";
		$ClassPHP .= "\tcase 1:\n";
				$ClassPHP .= $this->SwitchInsertar($arregloCampos);
		$ClassPHP .= "\tbreak;\n";
		
		$ClassPHP .= "\tcase 2:\n";
				$ClassPHP .= $this->SwitchModificar($arregloCampos);
		$ClassPHP .= "\tbreak;\n";
		
		$ClassPHP .= "\tcase 3:\n";
				$ClassPHP .= $this->SwitchEliminar($arregloCampos);
		$ClassPHP .= "\tbreak;\n";
		
		if ($arregloCampos['tieneActivarDesactivar']==1 && $arregloCampos['tieneEstado']==1)
		{
			$ClassPHP .= "\tcase 4:\n";
					$ClassPHP .= $this->SwitchDesActivar($arregloCampos);
			$ClassPHP .= "\tbreak;\n";
		
			$ClassPHP .= "\tcase 5:\n";
					$ClassPHP .= $this->SwitchActivar($arregloCampos);
			$ClassPHP .= "\tbreak;\n";
		}
		
		if ($arregloCampos['tieneOrden']){
			$ClassPHP .= "\tcase 6:\n";
					$ClassPHP .= $this->SwitchOrden($arregloCampos);
			$ClassPHP .= "\tbreak;\n";
		}
		
		$ClassPHP .= "}\n\r\r";
		
		
		$ClassPHP .= $this->Pie();
		
		
		if($this->generarSitio && FuncionesPHPLocal::GuardarArchivo(DIR_ROOT,$ClassPHP,$this->archivoModulo."_upd.php"))
		{
			$msgactualizacion = "Se ha publicado correctamente.";
			$ret['IsSuccess'] = true;
		}
		
		if($this->generarPaquete && FuncionesPHPLocal::GuardarArchivo($this->carpetaPaquete,$ClassPHP,$this->archivoModulo."_upd.php"))
		{
			$msgactualizacion = "Se ha publicado correctamente.";
			$ret['IsSuccess'] = true;
		}
	
	}

	public function Encabezado()
	{
		$ClassPHP = "<?php \n";	
		
		$ClassPHP .= "ob_start();\n";
		$ClassPHP .= "require(\"./config/include.php\");\n\r";
		
		$ClassPHP .= "\$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);\n";
		$ClassPHP .= "\$conexion->SeleccionBD(BASEDATOS);\n\r";
		
		$ClassPHP .= "FuncionesPHPLocal::CargarConstantes(\$conexion,array(\"roles\"=>\"si\",\"sistema\"=>SISTEMA,\"multimedia\"=>\"si\"));\n";
		$ClassPHP .= "\$conexion->SetearAdmiGeneral(ADMISITE);\n\r";
		
		$ClassPHP .= "\$sesion = new Sesion(\$conexion,false);\n";
		$ClassPHP .= "\$sesion->TienePermisos(\$conexion,\$_SESSION['usuariocod'],\$_SESSION['rolcod'],\$_SERVER['PHP_SELF']);\n\r";
		
		$ClassPHP .= "\$oSistemaBloqueo = new SistemaBloqueo();\n";
		$ClassPHP .= "\$oSistemaBloqueo->VerificarBloqueo(\$conexion);\n\r";
	
	
		$ClassPHP .= "header('Content-Type: text/html; charset=iso-8859-1');\n";
		$ClassPHP .= "\$_POST=FuncionesPHPLocal::ConvertiraUtf8 (\$_POST);\n";
		$ClassPHP .= "\$msg = array();\n";
		$ClassPHP .= "\$msg['IsSucceed'] = false;\n";
		$ClassPHP .= "\$datos = \$_POST;\n";
		$ClassPHP .= "\$conexion->ManejoTransacciones(\"B\");\n\r\r";
		$ClassPHP .= "if (!isset(\$datos['accion']) || \$datos['accion']==\"\")\n";
		$ClassPHP .= "{\n";
		$ClassPHP .= "\t\$msg['Msg'] = \"Error al procesar\";\n";
		$ClassPHP .= "\techo json_encode(\$msg);\n";
		$ClassPHP .= "\tob_end_flush();\n";
		$ClassPHP .= "\tdie();\n";
		$ClassPHP .= "}\n";
	
	
		
		
		return $ClassPHP;
	}
	
	
	public function Pie()
	{
	
		$ClassPHP = "\n\r\rif (\$msg['IsSucceed'])\n";
		$ClassPHP .= "\t\$conexion->ManejoTransacciones(\"C\");\n";
		$ClassPHP .= "else\n";
		$ClassPHP .= "{\n";
		$ClassPHP .= "\t\$msg['Msg'] = utf8_encode(ob_get_contents()); \n";
		$ClassPHP .= "\t\$conexion->ManejoTransacciones(\"R\");\n";
		$ClassPHP .= "}\n";
		$ClassPHP .= "ob_clean();\n";
		$ClassPHP .= "echo json_encode(\$msg);\n";
		$ClassPHP .= "ob_end_flush();\n";
		$ClassPHP .= "?>";
		
		return $ClassPHP;
	}
	
	
	public function SwitchInsertar($arregloCampos)
	{
		$ClassPHP = "\t\tif(\$oObjeto->Insertar(\$datos,\$codigoinsertado))\n";
		$ClassPHP .= "\t\t{\n";
		$ClassPHP .= "\t\t\t\$msg['IsSucceed'] = true;\n";
		$ClassPHP .= "\t\t\t\$msg['Msg'] = \"Se ha agregado correctamente a las \".date(\"H\").\":\".date(\"i\").\"Hs\"; \n";
		$ClassPHP .= "\t\t\t\$msg['".$arregloCampos['codigo']."'] = \$codigoinsertado; \n";
		$ClassPHP .= "\t\t\t\$msg['header'] = \"".$arregloCampos['archivo']."_am.php?".$arregloCampos['codigo']."=\".\$codigoinsertado; \n";
		$ClassPHP .= "\t\t}\n";
		return $ClassPHP;
	
	}
	
	public function SwitchModificar($arregloCampos)
	{
		$ClassPHP = "\t\tif(\$oObjeto->Modificar(\$datos))\n";
		$ClassPHP .= "\t\t{\n";
		$ClassPHP .= "\t\t\t\$msg['IsSucceed'] = true;\n";
		$ClassPHP .= "\t\t\t\$msg['Msg'] = \"Se ha modificado correctamente a las \".date(\"H\").\":\".date(\"i\").\"Hs\"; \n";
		$ClassPHP .= "\t\t}\n";
		return $ClassPHP;
	}
	
	public function SwitchEliminar($arregloCampos)
	{
		$ClassPHP = "\t\tif(\$oObjeto->Eliminar(\$datos))\n";
		$ClassPHP .= "\t\t{\n";
		$ClassPHP .= "\t\t\t\$msg['IsSucceed'] = true;\n";
		$ClassPHP .= "\t\t\t\$msg['Msg'] = \"Se ha eliminado correctamente a las \".date(\"H\").\":\".date(\"i\").\"Hs\"; \n";
		$ClassPHP .= "\t\t}\n";
		return $ClassPHP;
	}
	
	
	public function SwitchActivar($arregloCampos)
	{
		$ClassPHP = "\t\tif(\$oObjeto->Activar(\$datos))\n";
		$ClassPHP .= "\t\t{\n";
		$ClassPHP .= "\t\t\t\$msg['IsSucceed'] = true;\n";
		$ClassPHP .= "\t\t\t\$msg['Msg'] = \"Se ha activado correctamente a las \".date(\"H\").\":\".date(\"i\").\"Hs\"; \n";
		$ClassPHP .= "\t\t}\n";
		return $ClassPHP;
	}
	
	public function SwitchDesActivar($arregloCampos)
	{
		$ClassPHP = "\t\tif(\$oObjeto->DesActivar(\$datos))\n";
		$ClassPHP .= "\t\t{\n";
		$ClassPHP .= "\t\t\t\$msg['IsSucceed'] = true;\n";
		$ClassPHP .= "\t\t\t\$msg['Msg'] = \"Se ha desactivado correctamente a las \".date(\"H\").\":\".date(\"i\").\"Hs\"; \n";
		$ClassPHP .= "\t\t}\n";
		return $ClassPHP;
	}
	
	
	public function SwitchOrden($arregloCampos)
	{
		$ClassPHP = "\t\tif(\$oObjeto->ModificarOrdenCompleto(\$datos))\n";
		$ClassPHP .= "\t\t{\n";
		$ClassPHP .= "\t\t\t\$msg['IsSucceed'] = true;\n";
		$ClassPHP .= "\t\t\t\$msg['Msg'] = \"Se ha modificado el orden correctamente a las \".date(\"H\").\":\".date(\"i\").\"Hs\"; \n";
		$ClassPHP .= "\t\t}\n";
		return $ClassPHP;
	}


}


?>