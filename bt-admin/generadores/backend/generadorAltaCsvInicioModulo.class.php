<?php  

class GenerarAltaCsvInicioModulo 
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
		
		$end = '"\n"';
		$ClassPHP .= "\$csv =\"\";\n";
		$ClassPHP .= "\$csv_end = ".$end.";\n";
		$ClassPHP .= "\$csv_sep = \";\";\n";
		$ClassPHP .= "\$nombre_archivo=\"".$this->archivoModulo."_\".date(\"Y-m-d\").\".csv\";\n";
		
		//print_r($arregloCamposTabla);die();
		
		$ClassPHP .= "//encabezado\n";
		$encabezado = "";
		
		$i =1;
		$total = count($arregloCamposTabla);
		foreach ($arregloCamposTabla as $Campo)
		{
			
			switch($arregloCampos['otroscampos']['camposaltatipo_'.$Campo['Field']])
			{
				case 1:
				case 2:
				case 3:
					$total = $total-1;	
				break;
				
			}
		}
		
		
		
		foreach ($arregloCamposTabla as $Campo)
		{
			
			switch($arregloCampos['otroscampos']['camposaltatipo_'.$Campo['Field']])
			{
				case 1:
				case 2:
				case 3:
				break;
				default:
					$encabezado .= "\"".$Campo['Field']."\"";
					if($total-1>= $i)
						$encabezado .= ".\$csv_sep.";
					$i++;
				break;
			}
			
			
		}
		$encabezado .= ".\$csv_end;";
		
		$ClassPHP .= "\$csv .=".$encabezado."\n\n\r";
		
		
		
		$ClassPHP .= "\$datos = \$_SESSION['BusquedaAvanzada'];\n\r";
		
		$ClassPHP .= "if(!\$oObjeto->BusquedaAvanzada (\$datos,\$resultado,\$numfilas))\n";
		$ClassPHP .= "\tdie();\n\r";
		
		$ClassPHP .= "while (\$fila = \$conexion->ObtenerSiguienteRegistro(\$resultado))\n";
		$ClassPHP .= "{\n";
		
		$i=1;
		$datoscsv =" ";
		foreach ($arregloCamposTabla as $Campo)
		{
			
			switch($arregloCampos['otroscampos']['camposaltatipo_'.$Campo['Field']])
			{
				case 1:
				case 2:
				case 3:
				break;
				default:
					if (array_key_exists($Campo['Field'],$arregloCampos['camposconCombo']))
					{
								$datoscsv .= "\$fila['".$Campo['Field']."desc']";
					}else
					{
						if (substr($arregloCampos['camposTabla'][$Campo['Field']]['Type'],0,4)=="date" || substr($arregloCampos['camposTabla'][$Campo['Field']]['Type'],0,8)=="datetime")
						{	
									$datoscsv .= "FuncionesPHPLocal::ConvertirFecha( \$fila['".$Campo['Field']."'],'aaaa-mm-dd','dd/mm/aaaa')";
						}else{
									$datoscsv .= "\$fila[\"".$Campo['Field']."\"]";
						}
					}
				
				if($total-1>= $i)
					$datoscsv .= ".\$csv_sep.";
				$i++;
				break;
			}
			
				
		}
		$datoscsv .= ".\$csv_end;";
		
		$ClassPHP .= "\t\$csv .=".$datoscsv."\n";
		
		$ClassPHP .= "}\n\r";
		
		
		$ClassPHP .= $this->PieLst();
		
		if($this->generarSitio && FuncionesPHPLocal::GuardarArchivo(DIR_ROOT,$ClassPHP,$this->archivoModulo."_csv.php"))
		{
			$msgactualizacion = "Se ha publicado correctamente.";
			$ret['IsSuccess'] = true;
		}
		
		if($this->generarPaquete && FuncionesPHPLocal::GuardarArchivo($this->carpetaPaquete,$ClassPHP,$this->archivoModulo."_csv.php"))
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
	
		$ClassPHP = "header(\"Content-type: application/vnd.ms-excel\");\n";
		$ClassPHP .= "header(\"Content-disposition: csv\" . date(\"Y-m-d\") . \".csv\");\n";
		$ClassPHP .= "header(\"Content-disposition: filename=\" . \$nombre_archivo);\n";
		$ClassPHP .= "print \$csv;\n";
		$ClassPHP .= "?>";
		
		return $ClassPHP;
	}
	
}
	
?>