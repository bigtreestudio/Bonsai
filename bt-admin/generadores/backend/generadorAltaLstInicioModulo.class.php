<?php  

class GenerarAltaLstInicioModulo 
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
		
		$ClassPHP = $this->EncabezadoLst();
		
		$ClassPHP .= "\$oObjeto = new ".$nombreClase."(\$conexion,\"\");\n";
		
		$ClassPHP .= "header('Content-Type: text/html; charset=iso-8859-1');\n";
		$ClassPHP .= "if (isset (\$_POST['page']))\n";
		$ClassPHP .= "\t\$page = \$_POST['page'];\n";
		$ClassPHP .= "else\n";
		$ClassPHP .= "\t\$page = 1; \n\r";
		
		$ClassPHP .= "if (isset (\$_POST['rows']))\n";
		$ClassPHP .= "\t\$limit = \$_POST['rows'];\n";
		$ClassPHP .= "else\n";
		$ClassPHP .= "\t\$limit = 1;\n\r";
		
		if($arregloCampos['tieneOrden']==1)
		{
			$ClassPHP .= "\$sidx = \"".$arregloCampos['campoOrden']."\";\n";
			$ClassPHP .= "\$sord = \"ASC\";\n\r";
			
		}
		else
		{
			$ClassPHP .= "\$sidx = \"".$arregloCampos['codigo']."\";\n";
			$ClassPHP .= "\$sord = \"DESC\";\n\r";
		}
		$ClassPHP .= "\$_SESSION['BusquedaAvanzada'] = array();\n\r";
		$ClassPHP .= "\$datos = \$_SESSION['BusquedaAvanzada'] = \$_POST;\n\r";
		
		$ClassPHP .= "if(!\$oObjeto->BusquedaAvanzada (\$datos,\$resultado,\$numfilas))\n";
		$ClassPHP .= "\tdie();\n\r";
		
		$ClassPHP .= "if (isset (\$_POST['sord']))\n";
		$ClassPHP .= "\t\$sord = \$_POST['sord'];\n";
		$ClassPHP .= "if (isset (\$_POST['sidx']))\n";
		$ClassPHP .= "\t\$sidx = \$_POST['sidx'];\n";
		
		
		$ClassPHP .= "\$count = \$numfilas;\n";
		
		$ClassPHP .= "\$count = \$numfilas;\n";
		$ClassPHP .= "if( \$count >0 )\n";
		$ClassPHP .= "\t\$total_pages = ceil(\$count/\$limit); \n";
		$ClassPHP .= "else\n";
		$ClassPHP .= "\t\$total_pages = 0; \n\r";
		
		$ClassPHP .= "if( \$page > \$total_pages )\n";
		$ClassPHP .= "\t\$page = \$total_pages;\n\r";
		
		$ClassPHP .= "if( \$limit<0 )\n";
		$ClassPHP .= "\t\$limit = 0;\n\r";
		
		$ClassPHP .= "\$start = \$limit*\$page - \$limit;";
		$ClassPHP .= "if( \$start<0 )\n";
		$ClassPHP .= "\t\$start = 0;\n\r";
		
		$ClassPHP .= "\$datos['orderby'] = \$sidx.\" \".\$sord;\n";
		$ClassPHP .= "\$datos['limit'] = \"LIMIT \".\$start.\" , \".\$limit;\n\r";
		
		$ClassPHP .= "if(!\$oObjeto->BusquedaAvanzada (\$datos,\$resultado,\$numfilas))\n";
		$ClassPHP .= "\tdie();\n\r";
		
		
		$ClassPHP .= "\t\$i = 0;\n";
		$ClassPHP .= "\t\$responce =new StdClass; \n";
		$ClassPHP .= "\t\$responce->page = \$page; \n";
		$ClassPHP .= "\t\$responce->total = \$total_pages; \n";
		$ClassPHP .= "\t\$responce->records = \$count;\n";
		$ClassPHP .= "\t\$responce->rows = array();\n";
		
		$ClassPHP .= "while (\$fila = \$conexion->ObtenerSiguienteRegistro(\$resultado))\n";
		$ClassPHP .= "{\n";
		$ClassPHP .= "\t\$linkedit = '<a class=\"editar\" href=\"".$this->archivoModulo."_am.php?".$arregloCampos['codigo']."='.\$fila[\"".$arregloCampos['codigo']."\"].'\" title=\"Editar\" id=\"editar_'.\$fila['".$arregloCampos['codigo']."'].'\">&nbsp;</a>';\n";
		
		
		if ($arregloCampos['tienePdf']==1)
		{
			$ClassPHP .= "\t\$linkpdf = '<a class=\"pdf\" target=\"_blank\" href=\"".$this->archivoModulo."_pdf.php?".$arregloCampos['codigo']."='.\$fila[\"".$arregloCampos['codigo']."\"].'\" title=\"PDF\" id=\"pdf_'.\$fila['".$arregloCampos['codigo']."'].'\">&nbsp;</a>';\n";
		}
		
		
		if ($arregloCampos['tieneEstado']==1 && $arregloCampos['tieneActivarDesactivar']==1)
		{
			$ClassPHP .= "\t\$tipoactivacion = 5;\n";
			$ClassPHP .= "\t\$class = \"desactivo\";\n";
			$ClassPHP .= "\tif (\$fila['".$arregloCampos['campoEstado']."']==ACTIVO)\n";
			$ClassPHP .= "\t{\n";
			$ClassPHP .= "\t\t\$tipoactivacion = 4;\n";
			$ClassPHP .= "\t\t\$class = \"activo\";\n";
			$ClassPHP .= "\t}\n";
			$ClassPHP .= "\t\$linkestado = '<a class=\"'.\$class.'\" href=\"javascript:void(0)\" onclick=\"ActivarDesactivar('.\$fila['".$arregloCampos['codigo']."'].','.\$tipoactivacion.')\" title=\"Activar / Desactivar\" >&nbsp;</a>';\n\r";
		
		}
		
		if ($arregloCampos['tieneEliminarLst'])
		{
			$ClassPHP .= "\t\$linkdel = '<a class=\"eliminar\" href=\"javascript:void(0)\" onclick=\"Eliminar('.\$fila['".$arregloCampos['codigo']."'].')\" title=\"Eliminar\" >&nbsp;</a>';\n\r";
		}
		
		$ClassPHP .= "\t\$datosmostrar = array(\n";
		foreach ($arregloCampos['camposListadoAvanzada'] as $Campos)
		{
			$muestraCampoestado = true;
			if ($arregloCampos['tieneEstado']==1 && $arregloCampos['tieneActivarDesactivar'])
				$muestraCampoestado = false;
			
			if ($Campos!=$arregloCampos['campoEstado'])
			{
				if (array_key_exists($Campos,$arregloCampos['camposconCombo']))
				{
						$ClassPHP .= "\t\tutf8_encode(FuncionesPHPLocal::HtmlspecialcharsBigtree(\$fila['".$Campos."desc'],ENT_QUOTES)),\n";
				}else
				{
					if ($Campos==$arregloCampos['codigo'])
						$ClassPHP .= "\t\t\$fila['".$Campos."'],\n";
					else{	
						if (substr($arregloCampos['camposTabla'][$Campos]['Type'],0,4)=="date" || substr($arregloCampos['camposTabla'][$Campos]['Type'],0,8)=="datetime")
						{	
							$ClassPHP .= "\t\tFuncionesPHPLocal::ConvertirFecha( \$fila['".$Campos."'],'aaaa-mm-dd','dd/mm/aaaa'),\n";
						}else
							$ClassPHP .= "\t\tutf8_encode(FuncionesPHPLocal::HtmlspecialcharsBigtree(\$fila['".$Campos."'],ENT_QUOTES)),\n";
					}
				}
			}elseif ($muestraCampoestado)
				$ClassPHP .= "\t\tutf8_encode(FuncionesPHPLocal::HtmlspecialcharsBigtree(\$fila['".$Campos."'],ENT_QUOTES)),\n";
		
		}
		
		if ($arregloCampos['tienePdf'])
			$ClassPHP .= "\t\t\$linkpdf,\n";
		if ($arregloCampos['tieneActivarDesactivar'])
			$ClassPHP .= "\t\t\$linkestado,\n";
		$ClassPHP .= "\t\t\$linkedit";
		if ($arregloCampos['tieneEliminarLst'])
			$ClassPHP .= ",\n\t\t\$linkdel";
		
		
		$ClassPHP .= "\n\t);\n";
		
		
		$ClassPHP .= "\t\$responce->rows[\$i]['".$arregloCampos['codigo']."'] = \$fila['".$arregloCampos['codigo']."'];\n";
		$ClassPHP .= "\t\$responce->rows[\$i]['id'] = \$fila['".$arregloCampos['codigo']."'];\n";
		$ClassPHP .= "\t\$responce->rows[\$i]['cell'] = \$datosmostrar;\n";
		$ClassPHP .= "\t\$i++;\n";
		
		
		$ClassPHP .= "}\n\r";
		
		
		$ClassPHP .= $this->PieLst();
		
		if($this->generarSitio && FuncionesPHPLocal::GuardarArchivo(DIR_ROOT,$ClassPHP,$this->archivoModulo."_lst_ajax.php"))
		{
			$msgactualizacion = "Se ha publicado correctamente.";
			$ret['IsSuccess'] = true;
		}
		
		if($this->generarPaquete && FuncionesPHPLocal::GuardarArchivo($this->carpetaPaquete,$ClassPHP,$this->archivoModulo."_lst_ajax.php"))
		{
			$msgactualizacion = "Se ha publicado correctamente.";
			$ret['IsSuccess'] = true;
		}
	
	}
	
	public function EncabezadoLst($cargaencabezado=true)
	{
		$ClassPHP = "<?php \n";	
		
		$ClassPHP .= "require(\"./config/include.php\");\n\r";
		
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
	
	
	public function PieLst($cargapie=true)
	{
	
		$ClassPHP = "echo json_encode(\$responce);\n";
		$ClassPHP .= "?>";
		
		return $ClassPHP;
	}
	
}
	
?>