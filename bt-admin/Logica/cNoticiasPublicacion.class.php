<?php 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con la lógica para el manejo de las noticias relacionadas

include(DIR_CLASES_DB."cNoticiasPublicacion.db.php");

class cNoticiasPublicacion extends cNoticiasPublicaciondb	
{


	protected $conexion;
	protected $formato;
	
	
	// Constructor de la clase
	function __construct($conexion,$formato=FMT_TEXTO){
		$this->conexion = &$conexion;
		$this->formato = $formato;
		parent::__construct(); 
    } 
	
	// Destructor de la clase
	function __destruct() {	
		parent::__destruct(); 
    } 	


//----------------------------------------------------------------------------------------- 
// Retorna una consulta si es una noticia publicada

// Parámetros de Entrada:
//		datos: arreglo de datos
//			noticiacod = codigo de la noticia (la que se quiere buscar es la noticia en publicacion)

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function EsNoticiaPublicada($datos,&$resultado,&$numfilas)
	{
		if (!parent::EsNoticiaPublicada($datos,$resultado,$numfilas))
			return false;

		return true;
	}
	


//----------------------------------------------------------------------------------------- 
// Retorna una consulta con las ultimas noticias de los ultimos 2 dias

// Parámetros de Entrada:

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarNoticiasGoogleNews(&$resultado,&$numfilas)
	{
		if (!parent::BuscarNoticiasGoogleNews($resultado,$numfilas))
			return false;

		return true;
	}
	


	// Retorna una consulta con todos los usuarios que cumplan con las condiciones

	// Parámetros de Entrada:
	//		datosbuscar: array asociativo con los filtros. Claves: usuarionombre, usuarioapellido, usuariocuit, usuarioemail

	// Retorna:
	//		numfilas,resultado: cantidad de filas y query de resultado
	//		la función retorna true o false si se pudo ejecutar con éxito o no

	function BusquedaAvanzada ($datos,&$resultado,&$numfilas)
	{
		$sparam=array(
			'estadotitulo'=> 0,
			'noticiatitulo'=> "",
			'estadofecha'=> 0,
			'noticiafecha'=> "",
			'noticiafecha2'=> "",
			'xcatcod'=> 0,
			'catcod'=> "-1",
			'xnoticiadestacada'=> 0,
			'noticiadestacada'=> "",
			'orderby'=> $datos['orderby'],
			'limit'=> ""
			);	
	
	
		if (isset ($datos['catcod']) && $datos['catcod']!="")
		{
			$sparam['catcod']= $datos['catcod'];
			$sparam['xcatcod']= 1;
		}
		
		if (isset ($datos['noticiadestacada']) && $datos['noticiadestacada']!="")
		{
			$sparam['noticiadestacada']= $datos['noticiadestacada'];
			$sparam['xnoticiadestacada']= 1;
		}
		
		if (isset ($datos['noticiatitulo']) && $datos['noticiatitulo']!="")
		{
			$sparam['noticiatitulo']= $datos['noticiatitulo'];
			$sparam['estadotitulo']= 1;
		}	

		if (isset ($datos['noticiafecha']) && $datos['noticiafecha']!="")
		{
			$fecha_bd=FuncionesPHPLocal::ConvertirFecha($datos['noticiafecha'],'dd/mm/aaaa','aaaa-mm-dd');
			$sparam['noticiafecha']= $fecha_bd." 0:00:00";
			$sparam['noticiafecha2']= $fecha_bd." 23:59:59";
			$sparam['estadofecha']= 1;
		}
	
		if (isset ($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];
	
		if (isset ($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];

	
		if (!parent::BusquedaAvanzada($sparam,$resultado,$numfilas))
			return false;

		return true;
	}

	function BuscarNoticiaxCodigo ($datos,&$resultado,&$numfilas)
	{
		$sparam=array(
			'noticiacod'=> $datos['noticiacod'],
			);	
	
		if (!parent::BuscarNoticiaxCodigo($sparam,$resultado,$numfilas))
			return false;

		return true;
	}
	
//----------------------------------------------------------------------------------------- 
// Duplica una noticia completa a una nueva y las relaciona con un código de copia.

// Parámetros de Entrada:
//		datos: arreglo de datos
//			noticiacod = codigo de la noticia (la que se quiere buscar es la noticia en publicacion)

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BajarNoticiaaEdicion($datos,&$noticiacodnueva)
	{
		$oNoticias = new cNoticias($this->conexion,$this->formato);
		$oNoticiasPermisos = new cNoticiasPermisos($this->conexion,$this->formato);
		if (!$oNoticias->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, noticia inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		$datosnoticiaorig =$this->conexion->ObtenerSiguienteRegistro($resultado);
		if ($datosnoticiaorig['noticiacopiacod']!="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, la noticia ya se encuentra duplicada. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}


		$oNoticiasWorkflowRoles = new cNoticiasWorkflowRoles($this->conexion,$this->formato);
		$datosacciones['noticiaestadocod'] = $datosnoticiaorig['noticiaestadocod'];
		$datosacciones['noticiaworkflowcod'] = $datos['noticiaworkflowcod'];
		$datosacciones['rolcod'] = $datos['rolcod'];
		if (!$oNoticiasWorkflowRoles->PuedeRealizarAccionNoticia($datosacciones,$datosworkflow))
		{	
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, accion erronea. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		$datosnoticiaorig['noticiaestadocod'] = $datosworkflow['noticiaestadocodfinal'];
		$datosnoticiaorig['noticiacopiacodorig']=$datosnoticiaorig['noticiacod'];
		$datos['noticiacod']=$datosnoticiaorig['noticiacopiacodorig'];
		
		if (!$oNoticias->InsertarNoticiaDuplicada($datosnoticiaorig,$noticiacodnueva))
			return false;

		if(!$oNoticias->DuplicarDatosAdicionalesNoticia($datos,$noticiacodnueva))
			return false;

		if (!$oNoticias->ActualizarCopiaOriginal($datos,$noticiacodnueva))
			return false;
			 
		return true;
	}
	
	
	
	


//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Actualizar cambios de una noticia publicada publicada

// Parámetros de Entrada:
//		datos: array asociativo con los datos a agregar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ActualizarCambiosNoticiaPublicada($datosacualizar)
	{

		$oNoticias = new cNoticias($this->conexion,$this->formato);
		//COPIO TODOS LOS DATOS NUEVOS EN LA VARIABLE DATOSNOTICIAS Y SOBREESCRIBO EL NOTICIACOD
		$datosnoticia=$datosacualizar;
		$datosnoticia['noticiacod']=$datosacualizar['noticiacopiacodorig'];

		//ELIMINO TODOS LOS DATOS ADICIONALES 
		if(!$oNoticias->EliminarDatosAdicionales($datosnoticia))
			return false;

		//INSERTO LOS NUEVOS DATOS ADICIONALES
		if(!$oNoticias->DuplicarDatosAdicionalesNoticia($datosacualizar,$datosnoticia['noticiacod']))
			return false;

		//AL PUBLICAR SETEO EN NULL EL CAMPO COPIA DE LA NOTICIA ORIGINAL
		$codigonull="NULL";
		 if (!$oNoticias->ActualizarCopiaOriginal($datosnoticia,$codigonull))
			return false;

		//AL PUBLICAR SETEO EN NULL EL CAMPO COPIA DE LA NOTICIA ORIGINAL
		if (!$oNoticias->ModificarNoticiaDuplicada($datosnoticia,$codigonull))
			return false;

		if (!$this->ModificarPublicacion($datosnoticia))
			return false;

		//ELIMINO LA COPIA DE LA NOTICIA COMPLETA
		if(!$oNoticias->Eliminar($datosacualizar))
			return false;
			
		//public json de notas de la categoria
		if (!$this->PublicarJSONCategoria($datosnoticia))
			return false;

		if (!$this->GenerarArchivoNoticia($datosnoticia))
			return false;	

		return true;
	
	}

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Insertar una nueva noticia publicada

// Parámetros de Entrada:
//		datos: array asociativo con los datos a agregar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Publicar($datos,&$codigoinsertado)
	{
		if (!$this->_ValidarPublicar($datos,$datosvalidados))
			return false;

		if (!parent::Insertar($datosvalidados,$codigoinsertado))
			return false;
		
		//public json de notas de la categoria
		if (!$this->PublicarJSONCategoria($datos))
			return false;

		if (!$this->GenerarArchivoNoticia($datos))
			return false;	

		return true;
	
	}

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Actualiza el estado de una noticia publicada a una despublicada\.

// Parámetros de Entrada:
//		noticiacod: codigo de noticia a dar de baja

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ActualizarEstadoDespublicada($datos)
	{
		$datos['noticiaestadocod']=NOTDESPUBLICADAS;
		$oNoticias = new cNoticias($this->conexion);

		if (!$oNoticias->ActualizarEstado($datos))
			return false;	

		return true;		
	}	

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Despublica una noticia

// Parámetros de Entrada:
//		noticiacod: codigo de noticia a dar de baja

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	
	public function DespublicarNoticia($datos)
	{
		if (!$this->ActualizarEstadoDespublicada($datos))
			return false;
		
		if (!parent::Eliminar($datos))
			return false;

		return true;
	
	}	

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Modifica los datos de una noticia publicada //NO VALIDA DATOS

// Parámetros de Entrada:
//		datos: array asociativo con los datos a agregar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	protected function ModificarPublicacion($datos)
	{
		if (!$this->_ValidarPublicar($datos,$datosvalidados))
			return false;

		if (!parent::ModificarPublicacion($datosvalidados))
			return false;

		return true;
	
	}
	

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Eliminar una noticia publicada

// Parámetros de Entrada:
//		datos: array asociativo con los datos a agregar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Eliminar($datos)
	{
		if (!$this->_ValidarEliminar($datos))
			return false;

		if (!parent::Eliminar($datos))
			return false;

		return true;
	
	}
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// function que valida los datos al momento eliminar una noticia publicada

// Parámetros de Entrada:
//		datos: array asociativo con los datos a validar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	private function _ValidarEliminar($datos)
	{
		if (!$this->EsNoticiaPublicada($datos,$resultado,$numfilas))
			return false;
		
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, noticia publica inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		
		return true;
	}
	
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// function que valida los datos al momento de publicar una nueva noticia

// Parámetros de Entrada:
//		datos: array asociativo con los datos a validar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	private function _ValidarPublicar($datos,&$datosvalidados)
	{
		if (!$this->_ValidarDatosVacios($datos,$datosvalidados))
			return false;
		
		return true;
	}
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// function que valida los datos obigatorios

// Parámetros de Entrada:
//		datos: array asociativo con los datos a validar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	private function _ValidarDatosVacios($datos,&$datosvalidados)
	{


		$datosvalidados=array(
			'catcod'=> "",
			'catdominio'=> "",
			'catnom'=> "",
			'noticiadominio'=> "",
			'noticiatitulo'=> "",
			'noticiatitulocorto'=> "",
			'noticiahrefexterno'=> "",
			'noticiacopete'=> "",
			'noticiacuerpo'=> "",
			'noticiavolanta'=> "",
			'noticiaautor'=> "",
			'noticiatags'=> "",
			'noticiafecha'=> ""
			);
		
		$datosvalidados = array_merge($datosvalidados,$datos);

		if ($datosvalidados['noticiatitulo']=="")
			$datosvalidados['noticiatitulo'] = "NULL";
		if ($datosvalidados['noticiatitulocorto']=="")
			$datosvalidados['noticiatitulocorto'] = "NULL";
		if ($datosvalidados['noticiacopiacodorig']=="")
			$datosvalidados['noticiacopiacodorig'] = "NULL";
		if ($datosvalidados['noticiacopiacod']=="")
			$datosvalidados['noticiacopiacod'] = "NULL";
		if ($datosvalidados['noticiavolanta']=="")
			$datosvalidados['noticiavolanta'] = "NULL";
		if ($datosvalidados['noticiacuerpo']=="")
			$datosvalidados['noticiacuerpo'] = "NULL";
		if ($datosvalidados['noticiacopete']=="")
			$datosvalidados['noticiacopete'] = "NULL";
		if ($datosvalidados['noticiahrefexterno']=="")
			$datosvalidados['noticiahrefexterno'] = "NULL";
		if ($datosvalidados['noticiaautor']=="")
			$datosvalidados['noticiaautor'] = "NULL";
		
		$datosvalidados['noticiafecha'] = $datos['noticiafecha'];
		
		$oTags = new cNoticiasTags($this->conexion,$this->formato);
		if(!$oTags->BuscarTagsxNoticia($datos,$resultadotags,$numfilastags))
			return false;
		
		$arreglotags = array();
		while ($filatags = $this->conexion->ObtenerSiguienteRegistro($resultadotags))
			$arreglotags[] = $filatags['noticiatag'];
			
		$datosvalidados['noticiatags'] = implode(", ",$arreglotags);

		$oNoticiasCategorias=new cCategorias($this->conexion);
		if(!$oNoticiasCategorias->BuscarxCodigo($datos,$resultado,$numfilas))
			die();
		
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, categoria de noticia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		
		$datoscategoria = $this->conexion->ObtenerSiguienteRegistro($resultado);
		$datosvalidados['catnom'] = $datoscategoria['catnom'];
		$datosvalidados['catcod'] = $datoscategoria['catcod'];
		$datosvalidados['catdominio'] = $datoscategoria['catdominio'];
		
		$carpeta = date("Ym",strtotime($datosvalidados['noticiafalta']));	
		$datosvalidados['noticiadominio'] = $carpeta."/".$this->ArmarUrlNoticia($datosvalidados['noticiatitulo'],$datosvalidados['noticiacod']);
		return true;

	}
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// function que genera la url de una noticia

// Parámetros de Entrada:
//		datos: array asociativo con los datos a validar
//			noticiatitulo: El titulo de la noticia para generar el url friendly

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	

	
	private function ArmarUrlNoticia($titulo,$codigo)
	{
		
		$dominio="";
		$dominionoticia = FuncionesPHPLocal::EscapearCaracteres($titulo);
		$dominionoticia=preg_replace('/[^a-zA-Z0-9-_ ]/', '-', trim($dominionoticia));
		$dominionoticia=str_replace(' ', '-', trim($dominionoticia));
			
		return $dominionoticia."_n".$codigo;
	}
	
	
	public function BusquedaAvanzadaMultimediaEstadisticas ($datos,&$resultado,&$numfilas)
	{
		$sparam=array(
			'multimediaconjuntocod'=> 1,
			'limit'=> "LIMIT 0,48"
			);	
	
		if (isset ($datos['multimediaconjuntocod']) && $datos['multimediaconjuntocod']!="")
			$sparam['multimediaconjuntocod']= $datos['multimediaconjuntocod'];
			
	
		if (isset ($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];

	
		if (!parent::BusquedaAvanzadaMultimediaEstadisticas($sparam,$resultado,$numfilas))
			return false;

		return true;
	}
	
	private function PublicarJSONCategoria($datosnoticia)
	{
		$catcod=$datosnoticia["catcod"];
		$orden="noticiafecha desc";
		if (isset($catcod) && $catcod!="")
		{
			$oCategorias = new cCategorias($this->conexion);
			if(!$oCategorias->ArmarArbolCategorias($catcod,$arbol))
				die();
			$arreglocodigos = array();	
			FuncionesPHPLocal::ArmarArregloCodigos($arbol,$arreglocodigos);
			$arreglocodigos[$catcod] = $catcod;
			$datos['catcod'] = implode(",",$arreglocodigos);
		}
		$datos['limit'] = "LIMIT 0,50";
		$datos['orderby'] = $orden;
		if(!$this->BusquedaAvanzada ($datos,$resultado,$numfilas))
			return false;
			
		$arraynoticias=array();
		if ($numfilas>0)
		{
			while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))
			{
				$arraynoticias[$fila["noticiacod"]]=FuncionesPHPLocal::DecodificarUtf8($fila);
			}
		}
		$json=json_encode($arraynoticias);
		$archivo="noticias_".$catcod.".json";
		file_put_contents(CARPETAJSON.$archivo, $json);
		return true;
	}
	
	
	public function GenerarArchivoNoticia($datos)
	{
		$oNoticias = new cNoticias($this->conexion,$this->formato);
		if (!$oNoticias->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;	

		$datosnoticia = $this->conexion->ObtenerSiguienteRegistro($resultado);
		$carpetaFecha = date("Ym",strtotime($datosnoticia['noticiafalta']))."/";
		
		if(!is_dir(PUBLICA."json/")){ 
			@mkdir(PUBLICA."json/");
		}
		
		if(!is_dir(PUBLICA."json/noticias/")){ 
			@mkdir(PUBLICA."json/noticias/");
		}
		
		if(!is_dir(PUBLICA."json/noticias/".$carpetaFecha)){ 
			@mkdir(PUBLICA."json/noticias/".$carpetaFecha);
		}
		
		$noticiacod = $datosnoticia['noticiacod']; 
		if (!$this->GenerarArrayDatosJson($noticiacod,$carpetaFecha,$datosnoticiaJson))
			return false;
		
		$datosnoticiaJson['noticiacarpetaFecha'] = $carpetaFecha;
		$datosnoticiaJson = FuncionesPHPLocal::DecodificarUtf8($datosnoticiaJson);
		$json = json_encode($datosnoticiaJson);
		if(!FuncionesPHPLocal::GuardarArchivo(PUBLICA."json/noticias/".$carpetaFecha,$json,"noticia_".$datosnoticia['noticiacod'].".json"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_INF,"Error, al generar el json de la noticia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}
	
	
	public function GenerarArrayDatosJson($noticiacod,$carpetaFecha,&$array)
	{
		 //BUSCO E INSERTO LOS DATOS DE LA TABLA NOT_NOTICIAS_PUBLICADAS
		$array = array();
		$datosbuscar['noticiacod'] = $noticiacod;	
		if (!$this->EsNoticiaPublicada($datosbuscar,$resultado,$numfilas))
			return false;
		$array = $this->conexion->ObtenerSiguienteRegistro($resultado);
		
		//BUSCO E INSERTO LOS DATOS DE GALERIAS DE LA NOTICIA EN LA TABLA NOT_NOTICIAS_TAGS
		$array['noticiaurl'] = $array['catdominio']."/".$array['noticiadominio'];
		$array['noticiaurlcompartir'] = "cn".$noticiacod;
		
		 //BUSCO E INSERTO LOS DATOS DE LA TABLA NOT_NOTICIAS_TAGS
		$oNoticiasTags = new cNoticiasTags($this->conexion,$this->formato);		
		if (!$oNoticiasTags->BuscarTagsxNoticia($datosbuscar,$resultadotags,$numfilastags))
			return false;	
		$array['tags'] = array();
		if($numfilastags>0){
			$i =0;
			while ($arraytagsinsertar = $this->conexion->ObtenerSiguienteRegistro($resultadotags))
			{
				$array['tags'][$i]=$arraytagsinsertar['noticiatag'];
				$i++;
			}
 		 }	
		 //BUSCO E INSERTO LOS DATOS DE LA TABLA NOT_NOTICIAS_TEMAS
		$oNoticiasNoticiasTemas = new cNoticiasNoticiasTemas($this->conexion,$this->formato);
		$DatosBuscarTemasNoticia['noticiacod'] = $noticiacod;
		$DatosBuscarTemasNoticia['temaestado'] = ACTIVO;		
		if (!$oNoticiasNoticiasTemas->BuscarxNoticia($DatosBuscarTemasNoticia,$resultadotemas,$numfilastemas))
			return false;	
		$array['temas'] = array();
		if($numfilastemas>0){
			while ($arraytemasinsertar = $this->conexion->ObtenerSiguienteRegistro($resultadotemas))
				$array['temas'][$arraytemasinsertar['temacod']]=$arraytemasinsertar;
 		 }	
		 
		 //BUSCO E INSERTO LOS DATOS DE LA TABLA NOT_NOTICIAS_MUL_MULTIMEDIAS
		 $oNoticiasMultimedia = new cNoticiasMultimedia($this->conexion,$this->formato);			
		// FOTOS DE LA NOTICIA
		$array['multimedias']['fotos'] = array();
		$datosmultimedia['multimediaconjuntocod'] = FOTOS;
		$datosmultimedia['noticiacod'] = $noticiacod;
		if(!$oNoticiasMultimedia->BuscarMultimediaxCodigoxMultimediaConjunto($datosmultimedia,$resultadoFotos,$numfilasFotos))
			return false;
		if($numfilasFotos>0)
		{
			while($filaFotos= $this->conexion->ObtenerSiguienteRegistro($resultadoFotos))
			{
				
				if(!$this->GenerarDatosMultimedia($filaFotos,$array['multimedias'],'multimediacod','not'))
					return false;
			}
		}
		
		// AUDIOS DE LA NOTICIA	
		$array['multimedias']['audios'] = array();
		$datosmultimedia['multimediaconjuntocod'] = AUDIOS;
		$datosmultimedia['noticiacod'] = $noticiacod;
		if(!$oNoticiasMultimedia->BuscarMultimediaxCodigoxMultimediaConjunto($datosmultimedia,$resultadoAudios,$numfilasAudios))
			return false;
		if($numfilasAudios>0)
		{
			while($filaAudios= $this->conexion->ObtenerSiguienteRegistro($resultadoAudios))
			{
				if(!$this->GenerarDatosMultimedia($filaAudios,$array['multimedias'],'multimediacod','not'))
					return false;			
			}
		}
		
		
		// VIDEOS DE LA NOTICIA	
		$array['multimedias']['videos'] = array();
		$datosmultimedia['multimediaconjuntocod'] = VIDEOS;
		if(!$oNoticiasMultimedia->BuscarMultimediaxCodigoxMultimediaConjunto($datosmultimedia,$resultadoVideos,$numfilasVideos))
			return false;
		if($numfilasVideos>0)
		{
			while($filaVideos= $this->conexion->ObtenerSiguienteRegistro($resultadoVideos))
			{
				if(!$this->GenerarDatosMultimedia($filaVideos,$array['multimedias'],'multimediacod','not'))
					return false;			
			}
		}
		// FILES DE LA NOTICIA	
		$array['multimedias']['archivos'] = array();
		$datosmultimedia['multimediaconjuntocod'] = FILES;
		if(!$oNoticiasMultimedia->BuscarMultimediaxCodigoxMultimediaConjunto($datosmultimedia,$resultadoFiles,$numfilasFiles))
			return false;
		if($numfilasFiles>0)
		{
			while($filaFiles= $this->conexion->ObtenerSiguienteRegistro($resultadoFiles))
			{
				if(!$this->GenerarDatosMultimedia($filaFiles,$array['multimedias'],'multimediacod','not'))
					return false;			
			}
		}
		
		//BUSCO E INSERTO LOS DATOS DE LA TABLA NOT_NOTICIAS_GAL_GALERIAS			
		$arraygalerias = array();
		$oNoticiasGalerias = new cNoticiasGalerias($this->conexion,$this->formato);		
		if (!$oNoticiasGalerias->BuscarGaleriasRelacionadasxNoticia($datosbuscar,$resultadogaleriasrel,$numfilasgaleriasrel))
			return false;				
		
		if($numfilasgaleriasrel>0)
		{
			while ($datosgaleriarelacionada = $this->conexion->ObtenerSiguienteRegistro($resultadogaleriasrel))
			{
				$arraygalerias[$datosgaleriarelacionada['galeriacod']]['galeriacodigo'] = $datosgaleriarelacionada['galeriacod'];
				$arraygalerias[$datosgaleriarelacionada['galeriacod']]['galeriatitulo'] = $datosgaleriarelacionada['galeriatitulo'];
				$arraygalerias[$datosgaleriarelacionada['galeriacod']]['galeriadesc'] = $datosgaleriarelacionada['galeriadesc'];
				$arraygalerias[$datosgaleriarelacionada['galeriacod']]['galeriaimportante'] = $datosgaleriarelacionada['galeriaimportante'];
				$arraytemp = array();
				switch ($datosgaleriarelacionada['multimediaconjuntocod'])
				{
					case FOTOS:
					
							
							$arraytemp['codigo'] = $datosgaleriarelacionada['multimediacod'];
							$arraytemp['conjunto'] = $datosgaleriarelacionada['multimediaconjuntocod'];
							$arraytemp['titulo'] = $datosgaleriarelacionada['multimediatitulo'];
							$arraytemp['descripcion'] = $datosgaleriarelacionada['multimedianombre'];
							$arraytemp['idexterno'] = $datosgaleriarelacionada['multimediaidexterno'];
							$arraytemp['url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$datosgaleriarelacionada['multimediaubic'];
							if(isset($datosgaleriarelacionada['previewubic']) && $datosgaleriarelacionada['previewubic']!="")
							$arraytemp['multimedias']['url_preview'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$datosgaleriarelacionada['previewubic'];
							$arraygalerias[$datosgaleriarelacionada['galeriacod']]['multimedias']['fotos'][$datosgaleriarelacionada['multimediacod']] = FuncionesPHPLocal::DecodificarUtf8($arraytemp);
						break;
					case VIDEOS:
							$arraytemp['codigo'] = $datosgaleriarelacionada['multimediacod'];
							$arraytemp['conjunto'] = $datosgaleriarelacionada['multimediaconjuntocod'];
							$arraytemp['titulo'] = $datosgaleriarelacionada['multimediatitulo'];
							$arraytemp['descripcion'] = $datosgaleriarelacionada['multimedianombre'];
							$arraytemp['idexterno'] = $datosgaleriarelacionada['multimediaidexterno'];
							if(isset($datosgaleriarelacionada['multimediaidexterno']) && $datosgaleriarelacionada['multimediaidexterno']!="")
								$arraytemp['url'] = "";
							else
								$arraytemp['url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS."videos/".$datosgaleriarelacionada['multimediaubic'];
							if(isset($datosgaleriarelacionada['previewubic']) && $datosgaleriarelacionada['previewubic']!="")
							$arraytemp['multimedias']['url_preview'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$datosgaleriarelacionada['previewubic'];
							$arraygalerias[$datosgaleriarelacionada['galeriacod']]['multimedias']['video'][$datosgaleriarelacionada['multimediacod']] = FuncionesPHPLocal::DecodificarUtf8($arraytemp);
						break;
					case AUDIOS:
							$arraytemp['codigo'] = $datosgaleriarelacionada['multimediacod'];
							$arraytemp['conjunto'] = $datosgaleriarelacionada['multimediaconjuntocod'];
							$arraytemp['titulo'] = $datosgaleriarelacionada['multimediatitulo'];
							$arraytemp['descripcion'] = $datosgaleriarelacionada['multimedianombre'];
							$arraytemp['idexterno'] = $datosgaleriarelacionada['multimediaidexterno'];
							if(isset($datosgaleriarelacionada['multimediaidexterno']) && $datosgaleriarelacionada['multimediaidexterno']!="")
								$arraytemp['url'] = "";
							else
								$arraytemp['url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS."audios/".$datosgaleriarelacionada['multimediaubic'];
							if(isset($datosgaleriarelacionada['previewubic']) && $datosgaleriarelacionada['previewubic']!="")
							$arraytemp['multimedias']['url_preview'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$datosgaleriarelacionada['previewubic'];
							$arraygalerias[$datosgaleriarelacionada['galeriacod']]['multimedias']['audio'][$datosgaleriarelacionada['multimediacod']] = FuncionesPHPLocal::DecodificarUtf8($arraytemp);
						break;
					case FILES:
							$arraytemp['codigo'] = $datosgaleriarelacionada['multimediacod'];
							$arraytemp['conjunto'] = $datosgaleriarelacionada['multimediaconjuntocod'];
							$arraytemp['titulo'] = $datosgaleriarelacionada['multimediatitulo'];
							$arraytemp['descripcion'] = $datosgaleriarelacionada['multimedianombre'];
							$arraytemp['idexterno'] = $datosgaleriarelacionada['multimediaidexterno'];
							if(isset($datosgaleriarelacionada['multimediaidexterno']) && $datosgaleriarelacionada['multimediaidexterno']!="")
								$arraytemp['url'] = "";
							else
								$arraytemp['url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ARCHIVOS.$datosgaleriarelacionada['multimediaubic'];
							if(isset($datosgaleriarelacionada['previewubic']) && $datosgaleriarelacionada['previewubic']!="")
							$arraytemp['multimedias']['url_preview'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$datosgaleriarelacionada['previewubic'];
							$arraygalerias[$datosgaleriarelacionada['galeriacod']]['multimedias']['archivo'][$datosgaleriarelacionada['multimediacod']] = FuncionesPHPLocal::DecodificarUtf8($arraytemp);
						break;
				}
			}
		}
		$array['galerias'] = $arraygalerias;
		
		
		//BUSCO E INSERTO LOS DATOS DE LA TABLA NOT_NOTICIAS_NOT_NOTICIAS	
		$arrayrelacionadas = array();	
		$oNoticiasNoticias = new cNoticiasNoticias($this->conexion,$this->formato);		
		if (!$oNoticiasNoticias->BuscarNoticiasRelacionadasxCodigoNoticia($datosbuscar,$resultadonoticiasrel,$numfilasnoticiasrel))
			return false;				
		
		if($numfilasnoticiasrel>0){
			while ($arrayrelacionada = $this->conexion->ObtenerSiguienteRegistro($resultadonoticiasrel)){
				
				$arrayrelacionadas[$arrayrelacionada['noticiacodrel']]['noticiacod']=$arrayrelacionada['noticiacodrel'];
				$arrayrelacionadas[$arrayrelacionada['noticiacodrel']]['noticiatitulo']=$arrayrelacionada['noticiatitulo'];
				$arrayrelacionadas[$arrayrelacionada['noticiacodrel']]['noticiatitulocorto']=$arrayrelacionada['noticiatitulocorto'];
				$arrayrelacionadas[$arrayrelacionada['noticiacodrel']]['noticiacopete']=$arrayrelacionada['noticiacopete'];
				$arrayrelacionadas[$arrayrelacionada['noticiacodrel']]['noticiafecha']=$arrayrelacionada['noticiafecha'];
				$arrayrelacionadas[$arrayrelacionada['noticiacodrel']]['noticiadominio']=$arrayrelacionada['noticiadominio'];
				$arrayrelacionadas[$arrayrelacionada['noticiacodrel']]['noticiaurl']=$arrayrelacionada['catdominio']."/".$arrayrelacionada['noticiadominio'];;
				$arrayrelacionadas[$arrayrelacionada['noticiacodrel']]['noticiaorden']=$arrayrelacionada['noticiaorden'];
				$arrayrelacionadas[$arrayrelacionada['noticiacodrel']]['noticiaimportante']=$arrayrelacionada['noticiaimportante'];
				$arrayrelacionadas[$arrayrelacionada['noticiacodrel']]['multimedias']['fotos']= array();
				$arrayrelacionadas[$arrayrelacionada['noticiacodrel']]['multimedias']['videos']= array();
				$arrayrelacionadas[$arrayrelacionada['noticiacodrel']]['multimedias']['audios']= array();
				$arrayrelacionadas[$arrayrelacionada['noticiacodrel']]['multimedias']['archivos']= array();
			
				$datosbuscarmultimedia['noticiacod'] = $arrayrelacionada['noticiacodrel'];
				if (!$oNoticiasMultimedia->BuscarMultimediaxCodigoNoticiaxMinimoOrden($datosbuscarmultimedia,$resultadomultimediarel,$numfilasmultimrdiarel))
					return false;				
				while ($datosmultimediarel = $this->conexion->ObtenerSiguienteRegistro($resultadomultimediarel)){
						
						if(!$this->GenerarDatosMultimedia($datosmultimediarel,$arrayrelacionadas[$arrayrelacionada['noticiacodrel']]['multimedias'],'multimediacod','not'))
							return false;	
						
						/*switch ($datosmultimediarel['multimediaconjuntocod'])
						{
							case FOTOS:
									$datosmultimediarel['multimediaubic'] = CARPETA_SERVIDOR_MULTIMEDIA.CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$datosmultimediarel['multimediaubic'];
									if(isset($datosmultimediarel['previewubic']) && $datosmultimediarel['previewubic']!="")
										$datosmultimediarel['previewubic'] = CARPETA_SERVIDOR_MULTIMEDIA.CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$datosmultimediarel['previewubic'];
									$arrayrelacionadas[$arrayrelacionada['noticiacodrel']]['multimedias']['foto'][$datosmultimediarel['multimediacod']]= FuncionesPHPLocal::DecodificarUtf8($datosmultimediarel);
								break;
							case VIDEOS:
									if($datosmultimediarel['multimediaubic']!="")
										$datosmultimediarel['multimediaubic'] = CARPETA_SERVIDOR_MULTIMEDIA.CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS."videos/".$datosmultimediarel['multimediaubic'];
									if(isset($datosmultimediarel['previewubic']) && $datosmultimediarel['previewubic']!="")
										$datosmultimediarel['previewubic'] = CARPETA_SERVIDOR_MULTIMEDIA.CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$datosmultimediarel['previewubic'];
									$arrayrelacionadas[$arrayrelacionada['noticiacodrel']]['multimedias']['video'][$datosmultimediarel['multimediacod']]= FuncionesPHPLocal::DecodificarUtf8($datosmultimediarel);
								break;
							case AUDIOS:
									$datosmultimediarel['multimediaubic'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS."audios/".$datosmultimediarel['multimediaubic'];
									if(isset($datosmultimediarel['previewubic']) && $datosmultimediarel['previewubic']!="")
										$datosmultimediarel['previewubic'] = CARPETA_SERVIDOR_MULTIMEDIA.CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$datosmultimediarel['previewubic'];
									$arrayrelacionadas[$arrayrelacionada['noticiacodrel']]['multimedias']['audio'][$datosmultimediarel['multimediacod']]= FuncionesPHPLocal::DecodificarUtf8($datosmultimediarel);
								break;
							case FILES:
									$datosmultimediarel['multimediaubic'] = CARPETA_SERVIDOR_MULTIMEDIA.CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ARCHIVOS.$datosmultimediarel['multimediaubic'];
									if(isset($datosmultimediarel['previewubic']) && $datosmultimediarel['previewubic']!="")
										$datosmultimediarel['previewubic'] = CARPETA_SERVIDOR_MULTIMEDIA.CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$datosmultimediarel['previewubic'];
									$arrayrelacionadas[$arrayrelacionada['noticiacodrel']]['multimedias']['archivo'][$datosmultimediarel['multimediacod']]= FuncionesPHPLocal::DecodificarUtf8($datosmultimediarel);
								break;
						}*/

				}
				
			}
		}	
		$array['relacionadas'] = $arrayrelacionadas;
		return true;
	}
	
	public function GenerarDatosMultimedia($fila,&$arraymultimedia,$id,$prefijo)
	{
		$arraytemp = array();
		switch ($fila['multimediaconjuntocod'])
		{
			case FOTOS:
					$arraytemp['codigo'] = $fila[$id];
					$arraytemp['conjunto'] = $fila['multimediaconjuntocod'];
					$arraytemp['tipo'] = $fila['multimediatipocod'];
					$arraytemp['titulo'] = $fila[$prefijo.'multimediatitulo'];
					$arraytemp['descripcion'] = $fila[$prefijo.'multimediadesc'];
					$arraytemp['nombre_archivo'] = $fila['multimedianombre'];
					$arraytemp['orden'] = $fila[$prefijo.'multimediaorden'];
					$arraytemp['muestrahome'] = $fila[$prefijo.'multimediamuestrahome'];
					$arraytemp['idexterno'] = $fila['multimediaidexterno'];
					$arraytemp['url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$fila['multimediaubic'];
					if(isset($fila['previewubic']) && $fila['previewubic']!="")
					$arraytemp['url_preview'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$fila['previewubic'];
					$arraymultimedia['fotos'][$fila[$id]] = FuncionesPHPLocal::DecodificarUtf8($arraytemp);
				break;	
			case VIDEOS:
					$arraytemp['codigo'] = $fila[$id];
					$arraytemp['conjunto'] = $fila['multimediaconjuntocod'];
					$arraytemp['tipo'] = $fila['multimediatipocod'];
					$arraytemp['titulo'] = $fila[$prefijo.'multimediatitulo'];
					$arraytemp['descripcion'] = $fila[$prefijo.'multimediadesc'];
					$arraytemp['nombre_archivo'] = $fila['multimedianombre'];
					$arraytemp['orden'] = $fila[$prefijo.'multimediaorden'];
					$arraytemp['muestrahome'] = $fila[$prefijo.'multimediamuestrahome'];
					$arraytemp['idexterno'] = $fila['multimediaidexterno'];
					if(isset($fila['multimediaidexterno']) && $fila['multimediaidexterno']!="")
						$arraytemp['url'] = "";	
					else
						$arraytemp['url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS."videos/".$fila['multimediaubic'];
					if(isset($fila['previewubic']) && $fila['previewubic']!="")
					$arraytemp['url_preview'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$fila['previewubic'];
					$arraymultimedia['videos'][$fila[$id]] = FuncionesPHPLocal::DecodificarUtf8($arraytemp);
				break;
			case AUDIOS:
					$arraytemp['codigo'] = $fila[$id];
					$arraytemp['conjunto'] = $fila['multimediaconjuntocod'];
					$arraytemp['tipo'] = $fila['multimediatipocod'];
					$arraytemp['titulo'] = $fila[$prefijo.'multimediatitulo'];
					$arraytemp['descripcion'] = $fila[$prefijo.'multimediadesc'];
					$arraytemp['nombre_archivo'] = $fila['multimedianombre'];
					$arraytemp['orden'] = $fila[$prefijo.'multimediaorden'];
					$arraytemp['muestrahome'] = $fila[$prefijo.'multimediamuestrahome'];
					$arraytemp['idexterno'] = $fila['multimediaidexterno'];
					if(isset($fila['multimediaidexterno']) && $fila['multimediaidexterno']!="")
						$arraytemp['url'] = "";
					else
						$arraytemp['url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS."audios/".$fila['multimediaubic'];	
					if(isset($fila['previewubic']) && $fila['previewubic']!="")
					$arraytemp['url_preview'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$fila['previewubic'];
					$arraymultimedia['audios'][$fila[$id]] = FuncionesPHPLocal::DecodificarUtf8($arraytemp);
				break;
			case FILES:
					$arraytemp['codigo'] = $fila[$id];
					$arraytemp['conjunto'] = $fila['multimediaconjuntocod'];
					$arraytemp['tipo'] = $fila['multimediatipocod'];
					$arraytemp['titulo'] = $fila[$prefijo.'multimediatitulo'];
					$arraytemp['descripcion'] = $fila[$prefijo.'multimediadesc'];
					$arraytemp['nombre_archivo'] = $fila['multimedianombre'];
					$arraytemp['orden'] = $fila[$prefijo.'multimediaorden'];
					$arraytemp['muestrahome'] = $fila[$prefijo.'multimediamuestrahome'];
					$arraytemp['idexterno'] = $fila['multimediaidexterno'];
					if(isset($fila['multimediaidexterno']) && $fila['multimediaidexterno']!="")
						$arraytemp['url'] = "";
					else
						$arraytemp['url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ARCHIVOS.$fila['multimediaubic'];
					if(isset($fila['previewubic']) && $fila['previewubic']!="")
					$arraytemp['url_preview'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$fila['previewubic'];
					$arraymultimedia['archivos'][$fila[$id]] = FuncionesPHPLocal::DecodificarUtf8($arraytemp);
				break;
		}
		return true;
	}
	
	
}//FIN CLASE

?>