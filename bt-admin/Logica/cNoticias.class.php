<?
/*
CLASE LOGICA PARA EL MANEJO DE LAS NOTICIAS.
*/
include(DIR_CLASES_DB."cNoticias.db.php");

class cNoticias extends cNoticiasdb	
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
//							 PUBLICAS	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Retorna una consulta con los datos completos de la noticia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			noticiacod = codigo de la noticia

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	function BuscarDatosCompletosxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarDatosCompletosxCodigo($datos,$resultado,$numfilas))
			return false;

		return true;
	}
// Retorna una consulta con los datos completos de la noticia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			noticiacod = codigo de la noticia

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	function BuscarDatosCompletosNoticiasPublicadasxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarDatosCompletosNoticiasPublicadasxCodigo($datos,$resultado,$numfilas))
			return false;

		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna una consulta con los datos de la noticia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			noticiacod = codigo de la noticia

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCodigo($datos,$resultado,$numfilas))
			return false;

		return true;
	}
//----------------------------------------------------------------------------------------- 
// Retorna una consulta si la noticia es duplicado

// Parámetros de Entrada:
//		datos: arreglo de datos
//			noticiacod = codigo de la noticia

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function TieneDuplicado($datos)
	{
		if (!parent::TieneDuplicadoDb($datos,$resultado,$numfilas))
			return false;

		if($numfilas==0)
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
			'estadonoticiacod'=> 0,
			'noticiacod'=> "",
			'estadotitulo'=> 0,
			'noticiatitulo'=> "",
			'estadofecha'=> 0,
			'noticiafecha'=> "",
			'noticiafecha2'=> "",
			'estadonoticiaestadocod'=> 0,
			'estadonoticiacopiacod'=> 1,
			'estadonoticiacopiacodorig' => 0,
			'noticiaestadocod'=> "-1",
			'xcatcod'=> 0,
			'catcod'=> "-1",
			'usuariocod' => $datos['usuariocod'],
			'rolcod' => "-1",
			'xnoticiaestadocodbaja'=> 0,
			'noticiaestadocodbaja'=> NOTELIMINADA,
			'orderby'=> $datos['orderby'],
			'limit'=> ""
			);	
	
		if (isset ($datos['rolcod']) && $datos['rolcod']!="")
			$sparam['rolcod']= $datos['rolcod'];
		
		if (isset ($datos['noticiasestadobaja']) && $datos['noticiasestadobaja']!="")
			$sparam['xnoticiaestadocodbaja']= 1;

		if (isset ($datos['estadonoticiacopiacod']))
			$sparam['estadonoticiacopiacod'] = $datos['estadonoticiacopiacod'];

		if (isset ($datos['estadonoticiacopiacodorig']))
			$sparam['estadonoticiacopiacodorig'] = $datos['estadonoticiacopiacodorig'];

	
	
		if (isset ($datos['catcod']) && $datos['catcod']!="")
		{
			$sparam['catcod']= $datos['catcod'];
			$sparam['xcatcod']= 1;
		}
		else
		{
			if (isset($datos["categorias"]) && $datos["categorias"]!="")
			{
				$sparam['catcod']= $datos['categorias'];
				$sparam['xcatcod']= 1;
			}
		}
		
		if (isset ($datos['noticiacod']) && $datos['noticiacod']!="")
		{
			$sparam['noticiacod']= $datos['noticiacod'];
			$sparam['estadonoticiacod']= 1;
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
		if (isset ($datos['noticiaestadocod']) && $datos['noticiaestadocod']!="")
		{
			$sparam['noticiaestadocod']= $datos['noticiaestadocod'];
			$sparam['estadonoticiaestadocod']= 1;
		}
	
		if (isset ($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];
	
		if (isset ($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];

	
		if (!parent::BusquedaAvanzada($sparam,$resultado,$numfilas))
			return false;

		return true;
	}


	

	

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Insertar una nueva noticia

// Parámetros de Entrada:
//		datos: array asociativo con los datos a agregar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Insertar($datos,&$codigoinsertado,&$datosnuevos)
	{
		
		$oNoticiasWorkflowRoles = new cNoticiasWorkflowRoles($this->conexion,$this->formato);
		$datosacciones['noticiaestadocod'] = NOTBORRADOR;
		$datosacciones['noticiaworkflowcod'] = $datos['noticiaworkflowcod'];
		$datosacciones['rolcod'] = $datos['rolcod'];
		if (!$oNoticiasWorkflowRoles->PuedeRealizarAccionNoticia($datosacciones,$datosworkflow))
		{	
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, accion invalida. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		
		if (!$this->_ValidarInsertar($datos,$datosvalidados))
			return false;
			

		$datosnuevos['cambioestado']=false; 
		$datosvalidados['noticiaestadocod'] = NOTBORRADOR;
		if ($datosworkflow['noticiaestadocodfinal']!=NOTBORRADOR)	
		{
			$datosvalidados['noticiaestadocod'] = $datosworkflow['noticiaestadocodfinal'];
			$datosnuevos['cambioestado']=true; 
		}

		$datosvalidados['noticiacopete'] = preg_replace("/<p[^>]*><\\/p[^>]*>/",'<div class="space">&nbsp;</div>',$datosvalidados['noticiacopete']);
		$datosvalidados['noticiacuerpo'] = preg_replace("/<p[^>]*><\\/p[^>]*>/",'<div class="space">&nbsp;</div>',$datosvalidados['noticiacuerpo']);
		
		if (!parent::InsertarDB($datosvalidados,$codigoinsertado))
			return false;

		$datos['noticiacod'] = $codigoinsertado;
		if (!$this->ActualizarDatosExternos($datos))
			return false;
		
		$datosnuevos['noticiaestadocod'] = $datosworkflow['noticiaestadocodfinal'];
		$datosnuevos['noticiaestadodesc'] = $datosworkflow['noticiaestadodesc'];
		
		return true;
		
	
	}
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Insertar una nueva noticia duplicada //NO VALIDA DATOSS

// Parámetros de Entrada:
//		datos: array asociativo con los datos a agregar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function InsertarNoticiaDuplicada($datos,&$codigoinsertado)
	{
		$datos['noticiacopiacod'] = "NULL";
		$this->_SetearNull($datos,$datosvalidados);
		
		if (!parent::InsertarDB($datosvalidados,$codigoinsertado))
			return false;

		return true;
	}
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Modificar los datos de la noticia a una noticia publiada //NO VALIDA DATOSS

// Parámetros de Entrada:
//		datos: array asociativo con los datos a modificar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ModificarNoticiaDuplicada($datos)
	{
		if (!parent::ModificarDB($datos))
			return false;

		return true;
	}
		
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Elimina una noticia

// Parámetros de Entrada:
//		datos: array asociativo con los datos a agregar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Eliminar($datos)
	{
		//ELIMINO TODOS LOS DATOS ADICIONALES 
		if(!$this->EliminarDatosAdicionales($datos))
			return false;

		if (!parent::Eliminar($datos))
			return false;

		return true;		
	}



//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Elimina una noticia

// Parámetros de Entrada:
//		datos: array asociativo con los datos a agregar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function EliminarLogica($datos)
	{
		$datos['noticiaestadocod']=NOTELIMINADA;
		if (!parent::ActualizarEstado($datos))
			return false;	

		return true;		
	}
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Elimina una noticia

// Parámetros de Entrada:
//		datos: array asociativo con los datos a agregar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function EliminarNoticiaCompleta($datos)
	{

		if (!$this->_ValidarEliminar($datos,$datosvalidados))
			return false;

		if ($datosvalidados['noticiacopiacodorig']!="")
		{
			$codigonull="NULL";
			$datosacualizar["noticiacod"]=$datosvalidados['noticiacopiacodorig'];
			 if (!$this->ActualizarCopiaOriginal($datosacualizar,$codigonull))
				return false;
			//ELIMINO LA NOTICIA COMPLETA
			if(!$this->Eliminar($datos))
				return false;
		}elseif ($datosvalidados['noticiaestadocod']==NOTBORRADOR)
		{	
			if(!$this->Eliminar($datosvalidados))
				return false;
		}else
		{
			if(!$this->EliminarLogica($datosvalidados))
				return false;
		}
		if ($datosvalidados['noticiaestadocod']==NOTPUBLICADA)
		{
			$oNoticiasPublicacion = new cNoticiasPublicacion($this->conexion,$this->formato);
			if(!$oNoticiasPublicacion->Eliminar($datosvalidados))
				return false;
			
		}

		return true;		
	}
	
	
// Funcion que modifica el campo de noticiacodcopia que indica cual es el código de copia de una noticia
	
// Parámetros de Entrada:
//		datos: array asociativo con los datos a agregar
//			noticiacod: codigo de la noticia a modificar
//			codigoinsertadocopia: codigo de la copia de la noticia

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ActualizarCopiaOriginal($datos,$codigoinsertadocopia)
	{
		if (!parent::ActualizarCopiaOriginal($datos,$codigoinsertadocopia))
			return false;

		return true;		
	}	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Modificar los datos de un noticia

// Parámetros de Entrada:
//		datos: array asociativo con los datos a agregar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Modificar($datos,&$datosnuevos)
	{
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, noticia inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		$datonoticia =  $this->conexion->ObtenerSiguienteRegistro($resultado); 
		
		$jsonCategoria = json_decode($datonoticia['catdatajson'],1);
		$visualizacion = 1;
		if (isset($jsonCategoria['visualizacioncod']) && $jsonCategoria['visualizacioncod']!="")
			$visualizacion = $jsonCategoria['visualizacioncod'];
		
		$oNoticiasWorkflowRoles = new cNoticiasWorkflowRoles($this->conexion,$this->formato);
		$datosacciones['noticiaestadocod'] = $datonoticia['noticiaestadocod'];
		$datosacciones['noticiaworkflowcod'] = $datos['noticiaworkflowcod'];
		$datosacciones['rolcod'] = $datos['rolcod'];
		if (!$oNoticiasWorkflowRoles->PuedeRealizarAccionNoticia($datosacciones,$datosworkflow))
		{	
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, accion erronea. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		if (!$this->_ValidarModificar($datos,$datosvalidados))
			return false;

		$datosvalidados['noticiacopete'] = preg_replace("/<p[^>]*><\\/p[^>]*>/",'<div class="space">&nbsp;</div>',$datosvalidados['noticiacopete']);
		$datosvalidados['noticiacuerpo'] = preg_replace("/<p[^>]*><\\/p[^>]*>/",'<div class="space">&nbsp;</div>',$datosvalidados['noticiacuerpo']);
		
		$erroren = "";
		$oProcesarCuerpo = new cProcesarCuerpo($this->conexion,$this->formato);

		$datosTamanio = array();
		$datosTamanio['fotoizq'] = constant("TAMANIONOTFOTOI_".$visualizacion);
		$datosTamanio['fotoder'] =constant("TAMANIONOTFOTOD_".$visualizacion);
		$datosTamanio['fotocen'] =constant("TAMANIONOTFOTOC_".$visualizacion);
		$datosTamanio['videoizq'] = constant("TAMANIONOTVIDEOI_".$visualizacion);
		$datosTamanio['videoder'] =constant("TAMANIONOTVIDEOD_".$visualizacion);
		$datosTamanio['videocen'] =constant("TAMANIONOTVIDEOC_".$visualizacion);
		$datosTamanio['videoalto'] =constant("TAMANIONOTVIDEOALTO_".$visualizacion);
		$oProcesarCuerpo->SetearTamanios($datosTamanio);

		$sql = "SELECT a.multimediaconjuntocod, a.notmultimediatitulo, b.*, c.multimediacatcarpeta FROM not_noticias_mul_multimedia AS a INNER JOIN mul_multimedia AS b ON a.multimediacod=b.multimediacod 
				INNER JOIN mul_multimedia_categorias AS c ON b.multimediacatcod=c.multimediacatcod 
				WHERE noticiacod=".$datos['noticiacod']." and a.notmultimediamuestrahome=0 ORDER BY a.multimediaconjuntocod ASC, notmultimediaorden ASC";
		$this->conexion->_EjecutarQuery($sql,$erroren,$resultado,$errno);
		$numfilas = $this->conexion->ObtenerCantidadDeRegistros($resultado);
		$datosImagenes = array();
		$datosVideos = array();
		$datosAudios = array();
		if($numfilas>0)
		{
			while($multimedia = $this->conexion->ObtenerSiguienteRegistro($resultado))
			{
				
				if($multimedia['multimediaconjuntocod']==FOTOS)
				{
					$urlImagen = $multimedia['multimediacatcarpeta']."N/".$multimedia['multimediaubic'];
					$datosCarga['ubicacion'] = $urlImagen;
					$datosCarga['epigrafe'] = $multimedia['notmultimediatitulo'];
					$datosImagenes[] = $datosCarga;
				}
				if($multimedia['multimediaconjuntocod']==VIDEOS)
				{
					$datosCarga['multimediacod'] = $multimedia['multimediacod'];
					$datosCarga['epigrafe'] = $multimedia['notmultimediatitulo'];
					$datosVideos[] = $datosCarga;
				}
				
				if($multimedia['multimediaconjuntocod']==AUDIOS)
					$datosAudios[] = $multimedia;

			}
		}
		$cuerpo = $datosvalidados['noticiacuerpo'];
		$cuerpo = $oProcesarCuerpo->ProcesarImagenesCuerpo($datosImagenes,$cuerpo);
		$cuerpo = $oProcesarCuerpo->ProcesarVideosCuerpo($datosVideos,$cuerpo);
		$cuerpo = $oProcesarCuerpo->reemplazarFrasesWide($cuerpo);

		$datosvalidados['noticiacuerpoprocesado'] = $cuerpo;
		if (!parent::ModificarDB($datosvalidados))
			return false;

		if (!$this->ActualizarDatosExternos($datos))
			return false;
			
		
		$datosnuevos['cambioestado']=false; 
		if ($datonoticia['noticiaestadocod']!=$datosworkflow['noticiaestadocodfinal'])	
		{
			$datosestado['noticiacod'] = $datos['noticiacod'];
			$datosestado['noticiaestadocod'] = $datosworkflow['noticiaestadocodfinal'];
			if (!$this->ActualizarEstado($datosestado))
				return false;
			$datosnuevos['cambioestado']=true; 
		}
		$datosnuevos['noticiaestadocod'] = $datosworkflow['noticiaestadocodfinal'];
		$datosnuevos['noticiaestadodesc'] = $datosworkflow['noticiaestadodesc'];
			
		return true;
	
	}
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Actualiza el codigo de una publicacion

// Parámetros de Entrada:
//		datos: array asociativo con los datos a agregar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ActualizarCodigo($datos)
	{
		if (!parent::ActualizarCodigo($datos))
			return false;

		return true;
	
	}	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Modificar los datos de un noticia

// Parámetros de Entrada:
//		datos: array asociativo con los datos a agregar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ActualizarEstado($datos)
	{

		if (!parent::ActualizarEstado($datos))
			return false;	
	

		return true;
	
	}
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Buscar noticias publicadas

// Parámetros de Entrada:
//		datos: array asociativo con los datos a agregar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarNoticiasPublicadas($datos,&$resultado,&$numfilas)
	{


		$sparam=array(
			'xcatcod'=> 0,
			'catcod'=> "-1",
			'orderby'=> $datos['orderby'],
			'limit'=> $datos['limit']
			);		
		
		if (isset ($datos['catcod']) && $datos['catcod']!="")
		{
			$sparam['catcod']= $datos['catcod'];
			$sparam['xcatcod']= 1;
		}
				
	
		if (!parent::BuscarNoticiasPublicadas($sparam,$resultado,$numfilas))
			return false;	
	

		return true;
	
	}	
	
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Publica una noticia

// Parámetros de Entrada:
//		datos: array asociativo con los datos a agregar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function Publicar($datos)
	{
			
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
			
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, noticia inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		$datonoticia =  $this->conexion->ObtenerSiguienteRegistro($resultado); 
		
		$oNoticiasWorkflowRoles = new cNoticiasWorkflowRoles($this->conexion,$this->formato);
		$datosacciones['noticiaestadocod'] = $datonoticia['noticiaestadocod'];
		$datosacciones['noticiaworkflowcod'] = $datos['noticiaworkflowcod'];
		$datosacciones['rolcod'] = $datos['rolcod'];
		if (!$oNoticiasWorkflowRoles->PuedeRealizarAccionNoticia($datosacciones,$datosworkflow))
		{	
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, accion erronea. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		$oNoticiasPublicacion = new cNoticiasPublicacion($this->conexion,$this->formato);
		if($datonoticia['noticiacopiacodorig']!='')
		{
			if(!$oNoticiasPublicacion->ActualizarCambiosNoticiaPublicada($datonoticia))
				return false;
		}else{

			if (!$oNoticiasPublicacion->Publicar($datonoticia,$codigoinsertado))
				return false;
			$datosestado['noticiacod'] = $datonoticia['noticiacod'];
			$datosestado['noticiaestadocod'] = $datosworkflow['noticiaestadocodfinal'];
			if (!$this->ActualizarEstado($datosestado))
				return false;
		}

		return true;
	
	}
		
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Modificar los datos de un noticia

// Parámetros de Entrada:
//		datos: array asociativo con los datos a agregar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ActualizarDatosExternos($datos)
	{
		//print_r($datos);
		$oTags = new cNoticiasTags($this->conexion,$this->formato);
		if(!$oTags->Actualizar($datos))
			return false;

		if (!isset($datos['catcodrel']))
			$datos['catcodrel'] = array();
		$oCategorias = new cNoticiasCategorias($this->conexion,$this->formato);
		$datosenviar['noticiacod']= $datos['noticiacod'];
		$datosenviar['catcodrel']= $datos['catcodrel'];
		if(!$oCategorias->Actualizar($datosenviar))
			return false;

		
		$oNoticiasNoticiasTemas = new cNoticiasNoticiasTemas($this->conexion,$this->formato);
		if(!$oNoticiasNoticiasTemas->Actualizar($datos))
			return false;
		$oTemas = new cTemas($this->conexion);
		if (!isset($datos['temacod']))
			$datos['temacod'] = array();
		foreach ($datos['temacod'] as $temacod)
		{
			$datostema['temacod'] = $temacod;
			if(!$oTemas->BuscarxCodigo($datostema,$resultadotema,$numfilastema))
				return false;
			$filatema = $this->conexion->ObtenerSiguienteRegistro($resultadotema);	
			if($filatema['temacodsuperior']!="")
			{
				$datosbuscar['temacodsuperior'] = $filatema['temacodsuperior'];
				
				if(!$oTemas->BuscarTemasxTemaSuperior($datosbuscar,$resultado,$numfilas))
					return false;
				while($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))
				{
					$datosnoticia['noticiacod']=$datos['noticiacod'];
					$datosnoticia['temacod']=$filatema['temacodsuperior'];
					
					if(!$oNoticiasNoticiasTemas->BuscarxCodigo($datosnoticia,$resultadonoticia,$numfilasnoticias))
						return false;
					if($numfilasnoticias==0)
					{
						if(!$oNoticiasNoticiasTemas->Insertar($datosnoticia))
							return false;
					}	
				}
			}
		}
		return true;
			
	
	}
	


//----------------------------------------------------------------------------------------- 
// Inserta todos los datos adicionales de una noticia a otra.

// Parámetros de Entrada:
//		datos: arreglo de datos
//			noticiacod = codigo de la noticia (la que se quiere buscar es la noticia en publicacion)

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function DuplicarDatosAdicionalesNoticia($datos,$noticiacodinsertar)
	{
		//BUSCO E INSERTO LOS DATOS DE GALERIAS DE LA NOTICIA EN LA TABLA NOT_NOTICIAS_GAL_GALERIAS
		$oNoticiasGalerias = new cNoticiasGalerias($this->conexion,$this->formato);		
		if (!$oNoticiasGalerias->BuscarGaleriasRelacionadasxNoticia($datos,$resultadogaleriasrel,$numfilasgaleriasrel))
			return false;	
			
		if($numfilasgaleriasrel>0){			
			while ($datosgaleriarelacionadainsertar = $this->conexion->ObtenerSiguienteRegistro($resultadogaleriasrel)){
				$datosgaleriarelacionadainsertar['noticiacod']=$noticiacodinsertar;
				if (!$oNoticiasGalerias->InsertarDuplicar($datosgaleriarelacionadainsertar))
					return false;
			}			
		}
			
	
		//BUSCO E INSERTO LOS DATOS DE GALERIAS DE LA NOTICIA EN LA TABLA NOT_NOTICIAS_NOT_NOTICIAS		
		$oNoticiasNoticias = new cNoticiasNoticias($this->conexion,$this->formato);		
		if (!$oNoticiasNoticias->BuscarNoticiasRelacionadasxCodigoNoticia($datos,$resultadonoticiasrel,$numfilasnoticiasrel))
			return false;				
		
		if($numfilasnoticiasrel>0){
			while ($datosnoticiarelacionadainsertar = $this->conexion->ObtenerSiguienteRegistro($resultadonoticiasrel)){
				$datosnoticiarelacionadainsertar['noticiacod']=$noticiacodinsertar;
				if (!$oNoticiasNoticias->InsertarDuplicar($datosnoticiarelacionadainsertar))
					return false;
			}
		}	

		//BUSCO E INSERTO LOS DATOS DE GALERIAS DE LA NOTICIA EN LA TABLA NOT_NOTICIAS_MUL_MULTIMEDIA
		$oNoticiasMultimedia = new cNoticiasMultimedia($this->conexion,$this->formato);		
		if (!$oNoticiasMultimedia->BuscarMultimediaxCodigoNoticia($datos,$resultadomultimedia,$numfilasmultimedia))
			return false;	

		if($numfilasmultimedia>0){
			while ($datosnoticiamultimediainsertar = $this->conexion->ObtenerSiguienteRegistro($resultadomultimedia)){
				$datosnoticiamultimediainsertar['noticiacod']=$noticiacodinsertar;
				if (!$oNoticiasMultimedia->InsertarDuplicar($datosnoticiamultimediainsertar))
					return false;
			}
		}

		//BUSCO E INSERTO LOS DATOS DE GALERIAS DE LA NOTICIA  EN LA TABLA NOT_NOTICIAS_NOT_CATEGORIAS
		$oNoticiasCategorias = new cNoticiasCategorias($this->conexion,$this->formato);		
		if (!$oNoticiasCategorias->BuscarCategoriasxNoticia($datos,$resultadocategorias,$numfilascategorias))
			return false;	

		if($numfilascategorias>0){
			while ($datosnoticiacategoriasinsertar = $this->conexion->ObtenerSiguienteRegistro($resultadocategorias)){
				$datosnoticiacategoriasinsertar['noticiacod']=$noticiacodinsertar;
				if (!$oNoticiasCategorias->InsertarDuplicar($datosnoticiacategoriasinsertar))
					return false;
			}	
 		 }	

		//BUSCO E INSERTO LOS DATOS DE GALERIAS DE LA NOTICIA EN LA TABLA NOT_NOTICIAS_TAGS
		$oNoticiasTags = new cNoticiasTags($this->conexion,$this->formato);		
		if (!$oNoticiasTags->BuscarTagsxNoticia($datos,$resultadotags,$numfilastags))
			return false;	

		if($numfilastags>0){
			while ($datosnoticiatagsinsertar = $this->conexion->ObtenerSiguienteRegistro($resultadotags)){
				$datosnoticiatagsinsertar['noticiacod']=$noticiacodinsertar;
				if (!$oNoticiasTags->InsertarDuplicar($datosnoticiatagsinsertar))
					return false;
			}	
 		 }	
		 //BUSCO E INSERTO LOS DATOS DE LA NOTICIA EN LA TABLA NOT_NOTICIAS_TEMAS
		$oNoticiasNoticiasTemas = new cNoticiasNoticiasTemas($this->conexion,$this->formato);	
		if (!$oNoticiasNoticiasTemas->BuscarxCodigoNoticia($datos,$resultadotemas,$numfilastemas))
			return false;
		if($numfilastemas>0){
			while ($datosnoticiatemainsertar = $this->conexion->ObtenerSiguienteRegistro($resultadotemas)){
				$datosnoticiatemainsertar['noticiacod']=$noticiacodinsertar;
				if (!$oNoticiasNoticiasTemas->InsertarDuplicar($datosnoticiatemainsertar))
					return false;
			}	
 		 }			 		 
		
		return true;	
	}


//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Elimina los datos adicionales de una noticia

// Parámetros de Entrada:
//		datos: array
//			noticiacod: codigo de noticia a eliminar de las tablas relacionadas

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function EliminarDatosAdicionales($datos)
	{

		$oNoticiasTags = new cNoticiasTags($this->conexion,$this->formato);		
		$oNoticiasCategorias = new cNoticiasCategorias($this->conexion,$this->formato);		
		$oNoticiasMultimedia = new cNoticiasMultimedia($this->conexion,$this->formato);		
		$oNoticiasNoticias = new cNoticiasNoticias($this->conexion,$this->formato);		
		$oNoticiasGalerias = new cNoticiasGalerias($this->conexion,$this->formato);		
		$oNoticiasNoticiasTemas = new cNoticiasNoticiasTemas($this->conexion,$this->formato);	
		//ELIMINO LA NOTICIA EN LA TABLA NOT_NOTICIAS_TAGS
		if (!$oNoticiasTags->Eliminar($datos))
			return false;
		
		//ELIMINO LA NOTICIA EN LA TABLA NOT_NOTICIAS_NOT_CATEGORIAS
		if (!$oNoticiasCategorias->EliminarCompletoxNoticiacod($datos))
			return false;
 		 	
		//ELIMINO LA NOTICIA EN LA TABLA NOT_NOTICIAS_MUL_MULTIMEDIA
		if (!$oNoticiasMultimedia->EliminarCompletoxNoticiacod($datos))
			return false;
		
		//ELIMINO LA NOTICIA EN LA TABLA NOT_NOTICIAS_NOT_NOTICIAS
		if (!$oNoticiasNoticias->EliminarCompletoxNoticiacod($datos))
			return false;
		
		//ELIMINO LA NOTICIA EN LA TABLA NOT_NOTICIAS_GAL_GALERIAS
		if (!$oNoticiasGalerias->EliminarCompletoxNoticiacod($datos))
			return false;
		//ELIMINO LA NOTICIA EN LA TABLA NOT_NOTICIAS_TEMAS
		if (!$oNoticiasNoticiasTemas->EliminarCompletoxNoticiacod($datos))
			return false;	

		return true;
	}

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// function que valida los datos al momento de insertar una nueva noticia

// Parámetros de Entrada:
//		datos: array asociativo con los datos a validar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	private function _ValidarInsertar($datos,&$datosvalidados)
	{
		if (!$this->_ValidarDatosVacios($datos,$datosvalidados))
			return false;
		
		return true;
	}


	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// function que valida los datos al momento de modificar una nueva noticia, 

// Parámetros de Entrada:
//		datos: array asociativo con los datos a validar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	private function _ValidarModificar($datos,&$datosvalidados)
	{
		
		if (!$this->_ValidarDatosVacios($datos,$datosvalidados))
			return false;
		
		return true;
	}

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// function que valida los datos al momento de eliminar una noticia, 

// Parámetros de Entrada:
//		datos: array asociativo con los datos a validar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	private function _ValidarEliminar($datos,&$datosvalidados)
	{
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
			
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, noticia inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		$datosvalidados = $this->conexion->ObtenerSiguienteRegistro($resultado);
		$oNoticiasWorkflowRoles = new cNoticiasWorkflowRoles($this->conexion,$this->formato);
		$datosacciones['noticiaestadocod'] = $datosvalidados['noticiaestadocod'];
		$datosacciones['noticiaworkflowcod'] = $datos['noticiaworkflowcod'];
		$datosacciones['rolcod'] = $datos['rolcod'];
		if (!$oNoticiasWorkflowRoles->PuedeRealizarAccionNoticia($datosacciones,$datosworkflow))
		{	
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, accion erronea. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		
		return true;
	}

	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// function que valida los datos al momento de modificar el estado de la noticia, 

// Parámetros de Entrada:
//		datos: array asociativo con los datos a validar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	private function _ValidarPuedeActualizarEstado($datos,&$datosvalidados)
	{
		
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
			'noticiatitulo'=> "",
			'noticiatitulocorto'=> "",
			'noticiahrefexterno'=> "",
			'noticiacopete'=> "",
			'noticiacuerpo'=> "",
			'noticiacuerpoprocesado'=> "NULL",
			'noticiavolanta'=> "",
			'noticiaautor'=> "",
			'noticiafecha'=> "",
			'noticiaestadocod'=> "",
			'noticiabloqusuario'=> $_SESSION['usuariocod'],
			'noticiacopiacodorig'=> "NULL",
			'noticiacopiacod'=> "NULL",
			'pnoticialat'=> "NULL",
			'pnoticialng'=> "NULL",
			'pnoticiazoom'=> "NULL",
			'pnoticiatype'=> "NULL",
			'pnoticiadireccion'=> $datos['noticiadireccion'],
			'usuariodioalta'=> $_SESSION['usuariocod'],
			'noticiafalta'=> date("Y/m/d H:i:s"),
			'noticiafbaja'=> "NULL",
		);

		if ($datos['catcod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe seleccionar una categoria principal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));

			return false;
		}
		
		$oCategorias = new cCategorias($this->conexion,$this->formato);
		
		if(!$oCategorias->BuscarxCodigo($datos,$resultadocat,$numfilascat))
			return false;
			
		$filacat = $this->conexion->ObtenerSiguienteRegistro($resultadocat);
		if($filacat['catestado'] == ELIMINADO)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error categoria principal fue eliminada.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		if($filacat['catestado'] == NOACTIVO)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error categoria principal no esta activa.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		if ($datos['noticiatitulo']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar un titulo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));

			return false;
		}
		
		if ($datos['noticiatitulocorto']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar un titulo corto. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		if ($datos['noticiafecha']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe seleccionar la fecha de la noticia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['noticiafecha'],"FechaDDMMAAAA"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe seleccionar la fecha valida. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		$datosvalidados = array_merge($datosvalidados,$datos);
		if ($datosvalidados['noticiacopiacod']=="")
			$datosvalidados['noticiacopiacod'] = "NULL";
		
		if ($datosvalidados['noticiacopiacodorig']=="")
			$datosvalidados['noticiacopiacodorig'] = "NULL";
		

		$datosvalidados['noticiafecha'] = FuncionesPHPLocal::ConvertirFecha($datos['noticiafecha'],"dd/mm/aaaa","aaaa-mm-dd");
		$datosvalidados['noticiafecha'] .= " ".$datos['noticiahora'].":".$datos['noticiaminutos'].":00";
		

		return true;
	}
	
	
	
	private function _SetearNull($datos,&$datosvalidados)
	{
		
		
		$datosvalidados=array(
			'catcod'=>"",
			'noticiatitulo'=>"",
			'noticiatitulocorto'=>"",
			'noticiahrefexterno'=>"",
			'noticiacopete'=>"",
			'noticiacuerpo'=>"",
			'noticiacuerpoprocesado'=>"",
			'noticiavolanta'=>"",
			'noticiaautor'=>"",
			'noticiacopiacodorig'=>"",
			'noticiacopiacod'=>"",
			'noticiadireccion'=>"",
			'noticialat'=>"",
			'noticialng'=>"",
			'noticiazoom'=>"",
			'noticiatype'=>"",
			'noticiacomentarios'=>"",
			'noticiadestacada'=>"",
			'noticiafbaja'=>"",
		);
		

		
		
		$datosvalidados = array_merge($datosvalidados,$datos);
		
		if ($datosvalidados['catcod']=="")
			$datosvalidados['catcod'] = "NULL";
		if ($datosvalidados['noticiatitulo']=="")
			$datosvalidados['noticiatitulo'] = "NULL";
		if ($datosvalidados['noticiatitulocorto']=="")
			$datosvalidados['noticiatitulocorto'] = "NULL";
		if ($datosvalidados['noticiahrefexterno']=="")
			$datosvalidados['noticiahrefexterno'] = "NULL";
		if ($datosvalidados['noticiacopete']=="")
			$datosvalidados['noticiacopete'] = "NULL";
		if ($datosvalidados['noticiacuerpo']=="")
			$datosvalidados['noticiacuerpo'] = "NULL";
		if ($datosvalidados['noticiacuerpoprocesado']=="")
			$datosvalidados['noticiacuerpoprocesado'] = "NULL";	
		if ($datosvalidados['noticiavolanta']=="")
			$datosvalidados['noticiavolanta'] = "NULL";		
		if ($datosvalidados['noticiaautor']=="")
			$datosvalidados['noticiaautor'] = "NULL";	
		if ($datosvalidados['noticiacopiacodorig']=="")
			$datosvalidados['noticiacopiacodorig'] = "NULL";	
		if ($datosvalidados['noticiacopiacod']=="")
			$datosvalidados['noticiacopiacod'] = "NULL";	
		if ($datosvalidados['noticiadireccion']=="")
			$datosvalidados['noticiadireccion'] = "NULL";	
		if ($datosvalidados['noticialat']=="")
			$datosvalidados['noticialat'] = "NULL";		
		if ($datosvalidados['noticialng']=="")
			$datosvalidados['noticialng'] = "NULL";	
		if ($datosvalidados['noticiazoom']=="")
			$datosvalidados['noticiazoom'] = "NULL";	
		if ($datosvalidados['noticiatype']=="")
			$datosvalidados['noticiatype'] = "NULL";			
		if ($datosvalidados['noticiamuestramapa']=="")
			$datosvalidados['noticiamuestramapa'] = "NULL";
		if ($datosvalidados['noticiacomentarios']=="")
			$datosvalidados['noticiacomentarios'] = "NULL";
		if ($datosvalidados['noticiadestacada']=="")
			$datosvalidados['noticiadestacada'] = "NULL";
		if ($datosvalidados['noticiafbaja']=="")
			$datosvalidados['noticiafbaja'] = "NULL";		
		

		return true;	
	}


	
	
	
}//fin clase	

?>