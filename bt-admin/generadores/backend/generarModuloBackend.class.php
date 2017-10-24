<?php 


class cGenerarModuloBackend 
{
	protected $conexion;
	protected $archivoModulo;
	protected $carpetaPaquete;
	protected $generarSitio;
	protected $generarPaquete;
	protected $nameFileSql="modulos.sql";
	protected $fileWp;

	public function __construct($conexion,$carpetaPaquete,$carpetaPaqueteSql,$archivoModulo,$generarSitio=true,$generarPaquete=true){
		
		$this->conexion = &$conexion;
		$this->carpetaPaquete = $carpetaPaquete;
		$this->carpetaPaqueteSql = $carpetaPaqueteSql;
		$this->archivoModulo = $archivoModulo;
		$this->generarSitio = $generarSitio;
		$this->generarPaquete = $generarPaquete;
    } 
	
	// Destructor de la clase
	public function __destruct() {	

    } 	


	public function GenerarModulo($arregloCamposTabla,$arregloCampos,$nombreClase)
	{
		$result=true;
		$this->conexion->ManejoTransacciones("B");
		$oArchivos= new cArchivos($this->conexion);
		$oModulos=new cModulos($this->conexion); 
		$oModulosArchivos=new cModulosArchivos($this->conexion); 
		$oRoles_Modulos=new cRolesModulos ($this->conexion);
		$oGrupomod_Modulos= new cGruposmodModulos($this->conexion);
		$arreglocodigos = array();
		$ArregloDatos['archivonom']= $this->archivoModulo.".php";
		
		$sql = "SELECT * FROM archivos WHERE LCASE(archivonom) = LCASE('".$ArregloDatos['archivonom']."');";
		$erroren="";
		$this->conexion->_EjecutarQuery($sql,$erroren,$resultadoArchivo,$errno);
		$numfilas = $this->conexion->ObtenerCantidadDeRegistros($resultadoArchivo);
		if($numfilas=="0")
		{
			if (!$oArchivos->Insertar ($ArregloDatos,$archivocod))
				$result=false;
				$archivocodmodulo = $archivocod;
				$arreglocodigos[] = $archivocodmodulo;	
					
		}
		else
		{
			$fila = $this->conexion->ObtenerSiguienteRegistro($resultadoArchivo);
			$arreglocodigos[] = $fila['archivocod'];
			$archivocodmodulo = $fila['archivocod'];	
		}
		
		$ArregloDatos['archivonom']= $this->archivoModulo."_upd.php";
		$sql = "SELECT * FROM archivos WHERE LCASE(archivonom) = LCASE('".$ArregloDatos['archivonom']."');";
		$erroren="";
		$this->conexion->_EjecutarQuery($sql,$erroren,$resultadoArchivo,$errno);
		$numfilas = $this->conexion->ObtenerCantidadDeRegistros($resultadoArchivo);
		if($numfilas=="0")
		{
			if (!$oArchivos->Insertar ($ArregloDatos,$archivocod))
				$result=false;
			$arreglocodigos[] = $archivocod;		
		}
		else
		{
			$fila = $this->conexion->ObtenerSiguienteRegistro($resultadoArchivo);
			$arreglocodigos[] = $fila['archivocod'];	
		}
		$ArregloDatos['archivonom']= $this->archivoModulo."_am.php";
		$sql = "SELECT * FROM archivos WHERE LCASE(archivonom) = LCASE('".$ArregloDatos['archivonom']."');";
		$erroren="";
		$this->conexion->_EjecutarQuery($sql,$erroren,$resultadoArchivo,$errno);
		$numfilas = $this->conexion->ObtenerCantidadDeRegistros($resultadoArchivo);
		if($numfilas=="0")
		{
			if (!$oArchivos->Insertar ($ArregloDatos,$archivocod))
				$result=false;
			$arreglocodigos[] = $archivocod;			
		}
		else
		{
			$fila = $this->conexion->ObtenerSiguienteRegistro($resultadoArchivo);
			$arreglocodigos[] = $fila['archivocod'];	
		}
		$ArregloDatos['archivonom']= $this->archivoModulo."_lst_ajax.php";
		$sql = "SELECT * FROM archivos WHERE LCASE(archivonom) = LCASE('".$ArregloDatos['archivonom']."');";
		$erroren="";
		$this->conexion->_EjecutarQuery($sql,$erroren,$resultadoArchivo,$errno);
		$numfilas = $this->conexion->ObtenerCantidadDeRegistros($resultadoArchivo);
		if($numfilas=="0")
		{
			if (!$oArchivos->Insertar ($ArregloDatos,$archivocod))
				$result=false;
			$arreglocodigos[] = $archivocod;			
		}
		else
		{
			$fila = $this->conexion->ObtenerSiguienteRegistro($resultadoArchivo);
			$arreglocodigos[] = $fila['archivocod'];	
		}
		if($arregloCampos['tieneCsv'])
		{
			$ArregloDatos['archivonom']= $this->archivoModulo."_csv.php";
			$sql = "SELECT * FROM archivos WHERE LCASE(archivonom) = LCASE('".$ArregloDatos['archivonom']."');";
			$erroren="";
			$this->conexion->_EjecutarQuery($sql,$erroren,$resultadoArchivo,$errno);
			$numfilas = $this->conexion->ObtenerCantidadDeRegistros($resultadoArchivo);
			if($numfilas=="0")
			{
				if (!$oArchivos->Insertar ($ArregloDatos,$archivocod))
					$result=false;
					$arreglocodigos[] = $archivocodmodulo;	
						
			}
			else
			{
				$fila = $this->conexion->ObtenerSiguienteRegistro($resultadoArchivo);
				$arreglocodigos[] = $fila['archivocod'];
			}
		}
		
		if($arregloCampos['tienePdf'])
		{
			$ArregloDatos['archivonom']= $this->archivoModulo."_pdf.php";
			$sql = "SELECT * FROM archivos WHERE LCASE(archivonom) = LCASE('".$ArregloDatos['archivonom']."');";
			$erroren="";
			$this->conexion->_EjecutarQuery($sql,$erroren,$resultadoArchivo,$errno);
			$numfilas = $this->conexion->ObtenerCantidadDeRegistros($resultadoArchivo);
			if($numfilas=="0")
			{
				if (!$oArchivos->Insertar ($ArregloDatos,$archivocod))
					$result=false;
					$arreglocodigos[] = $archivocodmodulo;	
						
			}
			else
			{
				$fila = $this->conexion->ObtenerSiguienteRegistro($resultadoArchivo);
				$arreglocodigos[] = $fila['archivocod'];
			}
		}
		
		
		if($arregloCampos['TieneMultimedia'])
		{
			$sql = "SELECT * FROM archivos WHERE LCASE(archivonom) = LCASE('mul_multimedia_general_simple_visualizar.php');";
			$erroren="";
			$this->conexion->_EjecutarQuery($sql,$erroren,$resultadoArchivo,$errno);
			$fila = $this->conexion->ObtenerSiguienteRegistro($resultadoArchivo);
			$arreglocodigos[] = $fila['archivocod'];
			$sql = "SELECT * FROM archivos WHERE LCASE(archivonom) = LCASE('mul_multimedia_am_upd.php');";
			$erroren="";
			$this->conexion->_EjecutarQuery($sql,$erroren,$resultadoArchivo,$errno);
			$fila = $this->conexion->ObtenerSiguienteRegistro($resultadoArchivo);
			$arreglocodigos[] = $fila['archivocod'];
		}	
		if($arregloCampos['tieneFoto'])
		{
			$sql = "SELECT * FROM archivos WHERE LCASE(archivonom) = LCASE('mul_multimedia_general_simple_fotos.php');";
			$erroren="";
			$this->conexion->_EjecutarQuery($sql,$erroren,$resultadoArchivo,$errno);
			$fila = $this->conexion->ObtenerSiguienteRegistro($resultadoArchivo);
			$arreglocodigos[] = $fila['archivocod'];
		}
		if($arregloCampos['tieneVideo'])
		{
			$sql = "SELECT * FROM archivos WHERE LCASE(archivonom) = LCASE('mul_multimedia_general_simple_videos.php');";
			$erroren="";
			$this->conexion->_EjecutarQuery($sql,$erroren,$resultadoArchivo,$errno);
			$fila = $this->conexion->ObtenerSiguienteRegistro($resultadoArchivo);
			$arreglocodigos[] = $fila['archivocod'];
		}
		if($arregloCampos['tieneAudio'])
		{
			$sql = "SELECT * FROM archivos WHERE LCASE(archivonom) = LCASE('mul_multimedia_general_simple_audios.php');";
			$erroren="";
			$this->conexion->_EjecutarQuery($sql,$erroren,$resultadoArchivo,$errno);
			$fila = $this->conexion->ObtenerSiguienteRegistro($resultadoArchivo);
			$arreglocodigos[] = $fila['archivocod'];
		}
		if($arregloCampos['tieneArchivo'])
		{
			$sql = "SELECT * FROM archivos WHERE LCASE(archivonom) = LCASE('mul_multimedia_general_simple_archivos.php');";
			$erroren="";
			$this->conexion->_EjecutarQuery($sql,$erroren,$resultadoArchivo,$errno);
			$fila = $this->conexion->ObtenerSiguienteRegistro($resultadoArchivo);
			$arreglocodigos[] = $fila['archivocod'];
		}
		
		$codigomodulo = $_POST['codigomodulo'];
		$ArregloDatos['modulocod']=$_POST['codigomodulo'];	
		$ArregloDatos['modulodesc']=$_POST['nombremodulo'];	
		$ArregloDatos['modulotextomenu']=$_POST['nombremodulo'];	
		$ArregloDatos['archivocod']=$archivocodmodulo;	
		$ArregloDatos['modulosec']="10";	
		$ArregloDatos['modulomostrar']="S";	
		$ArregloDatos['moduloimg']="";	
		$ArregloDatos['modulodash']=0;	
		$ArregloDatos['moduloacciones']=0;	
		
		$sql = "SELECT * FROM modulos WHERE modulocod =".$codigomodulo.";";
		$erroren="";
		$this->conexion->_EjecutarQuery($sql,$erroren,$resultadoModulo,$errno);
		$numfilasModulo = $this->conexion->ObtenerCantidadDeRegistros($resultadoModulo);
		if($numfilasModulo=="0")
		{
			if (!$oModulos->Insertar ($ArregloDatos,$codigo))
				$result=false;		
		}
		
			
		foreach($arreglocodigos as $archivocod)
		{
			$ArregloDatos=array('modulocod'=>$codigomodulo,'archivocod'=>$archivocod);
			$sql = "SELECT * FROM modulos_archivos WHERE modulocod =".$ArregloDatos['modulocod']." AND archivocod=".$ArregloDatos['archivocod'].";";
			$erroren="";
			$this->conexion->_EjecutarQuery($sql,$erroren,$resultadoModuloArchivo,$errno);
			$numfilasModuloArchivo = $this->conexion->ObtenerCantidadDeRegistros($resultadoModuloArchivo);
			
			if($numfilasModuloArchivo=="0")
			{
				if (!$oModulosArchivos->Insertar ($ArregloDatos))
					$result=false;	
			}
			
			
		}

		$ArregloDatos=array('rolcod'=>$_POST['rolcod'],'modulocod'=>$codigomodulo);
		$sql = "SELECT * FROM roles_modulos WHERE rolcod=".$ArregloDatos['rolcod']." AND modulocod =".$ArregloDatos['modulocod'].";";
		$erroren="";
		$this->conexion->_EjecutarQuery($sql,$erroren,$resultadoRolesModulos,$errno);
		$numfilasRolesModulos = $this->conexion->ObtenerCantidadDeRegistros($resultadoRolesModulos);
		
		if($numfilasRolesModulos=="0")
		{
			if (!$oRoles_Modulos->Insertar ($ArregloDatos))
				$result=false;
		}
		
		
		
		if(isset($_POST['grupomodulo']) && $_POST['grupomodulo']!="")
		{
			$oGruposModulos= new cGruposModulos($this->conexion);
			$ArregloDatos['grupomodtextomenu']=$_POST['grupomodulo'];
			$ArregloDatos['grupomodsec']=10;
			
			$sql = "SELECT * FROM gruposmod WHERE LCASE(grupomodtextomenu) = LCASE('".$ArregloDatos['grupomodtextomenu']."');";
			$erroren="";
			$this->conexion->_EjecutarQuery($sql,$erroren,$resultadoGruposModulos,$errno);
			$numfilasGruposModulos = $this->conexion->ObtenerCantidadDeRegistros($resultadoGruposModulos);
			if($numfilasGruposModulos=="0")
			{
				if (!$oGruposModulos->Insertar ($ArregloDatos,$codigogrupomodulo))
					$result=false;
				$_POST['grupomodcod'] = $codigogrupomodulo;
			}
			else
			{
				$fila = $this->conexion->ObtenerSiguienteRegistro($resultadoGruposModulos);
				$_POST['grupomodcod'] =  $fila['grupomodcod'];	
			}
			
		}
		
		$ArregloDatos=array('modulocod'=>$codigomodulo,'grupomodcod'=>$_POST['grupomodcod']);
		$sql = "SELECT * FROM gruposmod_modulos WHERE grupomodcod=".$ArregloDatos['grupomodcod']." AND modulocod =".$ArregloDatos['modulocod'].";";
		$erroren="";
		$this->conexion->_EjecutarQuery($sql,$erroren,$resultadoGrupomod_Modulos,$errno);
		$numfilasGrupomod_Modulos = $this->conexion->ObtenerCantidadDeRegistros($resultadoGrupomod_Modulos);
		if($numfilasGrupomod_Modulos=="0")
		{
			if (!$oGrupomod_Modulos->Insertar ($ArregloDatos))
				$result=false;
		}
		
		if ($result)
			$this->conexion->ManejoTransacciones("C");
		else
			$this->conexion->ManejoTransacciones("R");
	
	}
	
	
	public function GenerarModuloSQL($arregloCamposTabla,$arregloCampos,$nombreClase)
	{
		$this->RemoveAndOpenFile();
		
		
		$codigomodulo = $_POST['codigomodulo'];
		$modulodesc=$_POST['nombremodulo'];	
		$this->InicioSql();
		$this->InsertSet($codigomodulo);
		$this->InsertGenerateArchivo($this->archivoModulo.".php");
		$this->InsertGenerateArchivo($this->archivoModulo."_upd.php");
		$this->InsertGenerateArchivo($this->archivoModulo."_am.php");
		$this->InsertGenerateArchivo($this->archivoModulo."_lst_ajax.php");
		if($arregloCampos['tieneCsv'])
			$this->InsertGenerateArchivo($this->archivoModulo."_csv.php");
			
		$this->InsertModulo($modulodesc,$this->archivoModulo.".php");
	
		$this->InsertModuloArchivo($this->archivoModulo.".php");
		$this->InsertModuloArchivo($this->archivoModulo."_upd.php");
		$this->InsertModuloArchivo($this->archivoModulo."_am.php");
		$this->InsertModuloArchivo($this->archivoModulo."_lst_ajax.php");
		if($arregloCampos['tieneCsv'])
			$this->InsertGenerateArchivo($this->archivoModulo."_csv.php");
		
		if(isset($_POST['grupomodulo']) && $_POST['grupomodulo']!="")
		{
			$ArregloDatos['grupomodtextomenu']=$_POST['grupomodulo'];
			$this->InsertGrupoModulo($ArregloDatos['grupomodtextomenu']);
		}else
		{
			$gruposmodmodulo = $_POST['grupomodcod'];
			$this->InsertGrupoModuloModulo($gruposmodmodulo);
		}
		
		$this->InsertRoles($_POST['rolcod']);
		$this->FinSql();
		
		$this->CloseFile();
	}
	
	
	
	public function RemoveAndOpenFile()
	{
		if (file_exists($this->carpetaPaqueteSql.$this->nameFileSql))
			unlink($this->carpetaPaqueteSql.$this->nameFileSql);
		 $this->fileWp = fopen($this->carpetaPaqueteSql.$this->nameFileSql,"ab+");
		
	}

	public function InsertGrupoModulo($grupomodnombre)
	{
	   $txtInsert = "INSERT INTO gruposmod (grupomodtextomenu, grupomodsec, grupomodimg, ultmodusuario, ultmodfecha) VALUES ('".$grupomodnombre."','10',NULL,'1','".date("Y-m-d H:i:s")."');\n";
	   fwrite($this->fileWp,$txtInsert);
	   $gruposmodmodulo = "LAST_INSERT_ID()";
	   $this->InsertGrupoModuloModulo($gruposmodmodulo);

	}
	
	public function InsertGrupoModuloModulo($gruposmodmodulo)
	{
	   $txtInsert = "INSERT INTO gruposmod_modulos (grupomodcod, modulocod, ultmodusuario, ultmodfecha) VALUES(".$gruposmodmodulo.",@Modulo,'1','".date("Y-m-d H:i:s")."');\n";
	   fwrite($this->fileWp,$txtInsert);
	}
	

	
	public function InsertSet($modulocod)
	{
  	   $txtInsert = "SET @Modulo = \"".$modulocod."\";\n";
	   fwrite($this->fileWp,$txtInsert);
	}
	
	
	public function InsertGenerateArchivo($archivonom)
	{
  	   $txtInsert = "INSERT INTO archivos (archivonom, ultmodusuario, ultmodfecha)  VALUES ('".$archivonom."',1,'".date("Y-m-d H:i:s")."');\n";
	   fwrite($this->fileWp,$txtInsert);
	}
	
	
	public function InsertModulo($modulodesc,$archivonom)
	{
  	   $txtInsert = "INSERT INTO modulos (modulocod, modulodesc, modulotextomenu, archivocod, modulosec, modulomostrar, moduloimg, modulodash, moduloacciones, ultmodusuario, ultmodfecha)( SELECT @Modulo,'".htmlspecialchars($modulodesc,ENT_QUOTES)."', '".htmlspecialchars($modulodesc,ENT_QUOTES)."', archivocod, '10', 'S', '', '0', '0', '1', '".date("Y-m-d H:i:s")."' FROM archivos WHERE archivonom=\"".$archivonom."\");\n";
	   fwrite($this->fileWp,$txtInsert);
	}
	
	

	public function InsertModuloArchivo($archivonom)
	{
  	   $txtInsert = "INSERT INTO modulos_archivos (modulocod, archivocod, ultmodusuario, ultmodfecha) (SELECT @Modulo ,archivocod, '1', '".date("Y-m-d H:i:s")."' FROM archivos WHERE archivonom=\"".$archivonom."\");\n";
	   fwrite($this->fileWp,$txtInsert);
	}
	
	
	public function InsertRoles($rolcod)
	{
  	   $txtInsert = "INSERT INTO roles_modulos (rolcod, modulocod, ultmodusuario, ultmodfecha) VALUES ('".$rolcod."' ,@Modulo, '1', '".date("Y-m-d H:i:s")."');\n";
	   fwrite($this->fileWp,$txtInsert);
	}
	
	
	public function InicioSql()
	{
  	   $txtInsert = "SET FOREIGN_KEY_CHECKS=0;\n";
  	   $txtInsert .= "SET AUTOCOMMIT = 0;\n";
  	   $txtInsert .= "START TRANSACTION;\n\n\n";
	   fwrite($this->fileWp,$txtInsert);
	}
	
	public function FinSql()
	{
  	   $txtInsert = "\n\n\nSET FOREIGN_KEY_CHECKS=1;\n";
  	   $txtInsert .= "COMMIT;\n";
	   fwrite($this->fileWp,$txtInsert);
	}
	
	


	public function CloseFile()
	{
		 fclose($this->fileWp);
	}
	
}
?>