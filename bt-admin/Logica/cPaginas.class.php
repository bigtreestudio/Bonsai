<? 
include(DIR_CLASES_DB."cPaginas.db.php");

class cPaginas extends cPaginasdb	
{
	protected $conexion;
	protected $formato;
	protected $activos;

	
// CLASE cPaginas 
// EN CASO DE QUE LA VARIABLE ACTIVOS SE ENCUENTRE EN LA LLAMADA A LA CLASE,
// LAS FUNCIONES SE TRABAJARAN CON SOLO  LOS DATOS DE LAS PAGINAS QUE SE ENCUENTREN
// EN ESTADO EDICION O PUBLICADA

//-----------------------------------------------------------------------------------------
//  LAS FUNCIONES QUE HASTA AHORA TIENEN ESTO SON:  
// 	ArregloHijos
//  TieneHijos
//-----------------------------------------------------------------------------------------

	// Constructor de la clase
	function __construct($conexion,$activos=true,$formato=FMT_TEXTO){
		$this->conexion = &$conexion;
		$this->activos = $activos;
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
// Retorna en un arreglo con los datos de una página
// Parámetros de Entrada:
//		pagcod: página a buscar

// Retorna:
//		resultado= Arreglo con todos los datos de una página.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		
		return true;
	} 
	
	
	
	public function BuscarHermanoseHijos($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarHermanoseHijos($datos,$resultado,$numfilas))
			return false;
		
		return true;
	} 

	
//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de un página

// Parámetros de Entrada:
//		pagcodsuperior: página superior a buscar

// Retorna:
//		resultado= Arreglo con todos los datos de una página.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	public function BuscarPaginasxPaginaSuperior($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarPaginasxPaginaSuperior($datos,$resultado,$numfilas))
			return false;
		
		return true;
	}
//----------------------------------------------------------------------------------------- 

// Parámetros de Entrada:
//		datos: arreglo de datos
//			catcod = codigo de la categoria del albun
//          pagcodsuperior = codigo de la página superior de la página
//			pagestadocod = estado a buscar de las paginas

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		$sparam=array(
			'xcatcod'=> 0,
			'catcod'=> "",
			'xpagcopiacod'=>0,
			'xpagestadocod'=> 0,
			'pagestadocod'=> "-1",
			'xpagcodsuperior'=> 0,
			'pagcodsuperior'=> "",
			'xpagtitulo'=> 0,
			'pagtitulo'=> "",
			'orderby'=> "pagorden ASC",
			'rolcod'=>"-1",
			'pagestadocodbaja'=>PAGELIMINADA,
			'xpaginaestadobaja'=>0,
			'limit'=> ""
		);	

		if (isset ($datos['paginaestadobaja']) && $datos['paginaestadobaja']!="")
			$sparam['xpaginaestadobaja']= 1;
		
		
		if (isset ($datos['pagcopiacod']) && $datos['pagcopiacod']!="")
			$sparam['xpagcopiacod']= 1;


		if (isset ($datos['rolcod']) && $datos['rolcod']!="")
			$sparam['rolcod']= $datos['rolcod'];
			
		if (isset ($datos['catcod']) && $datos['catcod']!="")
		{
			$sparam['catcod']= $datos['catcod'];
			$sparam['xcatcod']= 1;
		}

		if (isset ($datos['pagtitulo']) && $datos['pagtitulo']!="")
		{
			$sparam['pagtitulo']= $datos['pagtitulo'];
			$sparam['xpagtitulo']= 1;
		}

		if (isset ($datos['pagestadocod']) && $datos['pagestadocod']!="")
		{
			$sparam['pagestadocod']= $datos['pagestadocod'];
			$sparam['xpagestadocod']= 1;
		}
		
		if (isset ($datos['pagcodsuperior']) && $datos['pagcodsuperior']!="")
		{
			$sparam['pagcodsuperior1']= $datos['pagcodsuperior'];
			$sparam['xpagcodsuperior1']= 1;
		}
				
		if (isset ($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];
			
		if (isset ($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];
		
		if (!parent::BusquedaAvanzada ($sparam,$resultado,$numfilas))
			return false;
		return true;	
	}

//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos del raiz de una página

// Retorna:
//		resultado= Arreglo con todos los datos de una página.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no		

	public function BuscaPaginaRaiz(&$resultado,&$numfilas)
	{
		if (!parent::BuscaPaginaRaiz($resultado,$numfilas))
			return false;
		
		return true;
	}

//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de una página

// Parámetros de Entrada:
//		pagcodsuperior: pgina superior a buscar.Si vale "", entonces retorna el raiz de la página

// Retorna:
//		resultado= Arreglo con el maximo orden de una página.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no	


public function BuscarPaginaUltimoOrden($datos,&$resultado,&$numfilas)
	{
		$sparam=array(
			'xpagcodsuperior'=> 0,
			'pagcodsuperior'=> "",
			);
			
		if (isset ($datos['pagcodsuperior']) && $datos['pagcodsuperior']!="NULL")
		{
			$sparam['pagcodsuperior']= $datos['pagcodsuperior'];
			$sparam['xpagcodsuperior']= 1;
		}		
		if (!parent::BuscarPaginaUltimoOrden($sparam,$resultado,$numfilas))
			return false;
		
		return true;
	}	

//----------------------------------------------------------------------------------------- 
// Retorna un arreglo con todos los padres de una página

// Parámetros de Entrada:
//		pagcod: codigo de página a buscar
//		nivelarbol= Se inicializa en 0.

// Retorna:
//		arrcat: devuelve el arreglo con todos los padres de la página
//		nivelarbol: Devuelve el nivel en que se encuentra la página.
//		la función retorna true o false si se pudo ejecutar con éxito o no
 
 
 	public function ArregloPadres($pagcod,&$arrcat,&$nivelarbol)
	{
		if ($pagcod!="")
		{
			$datoscat['pagcod'] = $pagcod;
			if (!$this->BuscarxCodigo($datoscat,$resultado,$numfilas))
				return false;
			$result=true;
		
			if ($numfilas==0)
				$result=false;

			if ($result)
			{		
				while ($filasub=$this->conexion->ObtenerSiguienteRegistro($resultado))
				{
					$padre=$filasub['pagcodsuperior'];
					
					$arrcat[]=$filasub;
				}
				$nivelarbol++;
				if ($padre!="")
					if (!$this->ArregloPadres($padre,$arrcat,$nivelarbol))
						return false;

				$darvueltaarreglo=asort($arrcat);
			}
		}
		return true;
	} 


//----------------------------------------------------------------------------------------- 
// Retorna un arreglo con todos los hijos de una página
// Parámetros de Entrada:
//		pagcod: codigo de página a buscar
//		cantidadarreglo: Se inicializa en 0.

// Retorna:
//		arrcat: devuelve el arreglo con todos los hijos de la página
//		errcat: el error en caso de que se produzca
//		cantidadarreglo: La cantidad total del arreglo.
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function ArregloHijos($pagcod,&$arrcat,&$cantidadarreglo,$activos=false)
	{

		$arrcat = array();
		if ($pagcod!="")
		{
			$datoscat['pagcodsuperior'] = $pagcod;
			if (!$this->BuscarPaginasxPaginaSuperior($datoscat,$resultado,$numfilas))
				return false;
			
			$result=true;
			if ($numfilas==0)
				$result=false;

			if ($result)
			{		
				while ($filasub=$this->conexion->ObtenerSiguienteRegistro($resultado))
				{
					if (!$activos)
					{
						$arrcat[$cantidadarreglo]=$filasub;
						$cantidadarreglo++;
					}else
					{
						if ($filasub['pagestadocod']!=PAGELIMINADA)
						{
							$arrcat[$cantidadarreglo]=$filasub;
							$cantidadarreglo++;
						}
					}
				}
			}
		}
		else
		{
			if (!$this->BuscaPaginaRaiz($resultado,$numfilas))
				return false;
			
			while ($filasub=$this->conexion->ObtenerSiguienteRegistro($resultado))
			{
				if (!$activos)
				{
					$arrcat[$cantidadarreglo]=$filasub;
					$cantidadarreglo++;
				}else
				{
					if ($filasub['pagestadocod']!=PAGELIMINADA)
					{
						$arrcat[$cantidadarreglo]=$filasub;
						$cantidadarreglo++;
					}
				}
			}
		}
	
		return true;
	} 




//----------------------------------------------------------------------------------------- 
// Retorna un ok si tiene hijos

// Parámetros de Entrada:
//		pagcod: codigo de página a buscar

// Retorna:
//		errcat: el error en caso de que se produzca
//		ok: devulve verdadero en caso de que tenga hijos, falso si no tiene.
//		la función retorna true o false si se pudo ejecutar con éxito o no

	
	public function TieneHijos($pagcod,&$ok)
	{
		
		$datoscat['pagcodsuperior'] = $pagcod;
		if (!$this->BuscarPaginasxPaginaSuperior($datoscat,$resultado,$numfilas))
		{	
			$ok = false;
			return false;
		}

		$result=true;
		if ($result)
		{		
			if ($filasub=$this->conexion->ObtenerSiguienteRegistro($resultado))
				$ok=true;
			else
				$ok=false;
		}
		return true;
	} 

	
//----------------------------------------------------------------------------------------- 
// Retorna la rama ascendente de un página con redirección

// Parámetros de Entrada:
//		catcod: categoria de página a buscar
//		pagcod: codigo de página a buscar

// Retorna:
//		jerarquia: un string con la ruta (href)
//		errcat: el error en caso de que se produzca
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function MostrarJerarquia($catcod,$pagcod,&$jerarquia,&$nivel)
	{
		$i=1;
		$jerarquia="";
		$nivel=0;
		$arrjerarquia = array();
		if(!$this->ArregloPadres($pagcod,$arrjerarquia,$nivel))
			return false;

		if ($nivel!=0)
		{
			FuncionesPHPLocal::ArmarLinkMD5("pag_paginas.php",array("catcod"=>$catcod),$get,$md5);
			$jerarquia.="<a href='pag_paginas.php?".$get."'>";
			$jerarquia.="Inicio</a> &raquo; ";
		}
		else
			$jerarquia.="<span class=\"bold\">Inicio</span>";
		
		foreach ($arrjerarquia as $clave=>$valor) 
		{
			
			if ($i!=$nivel)
			{ 
				FuncionesPHPLocal::ArmarLinkMD5("pag_paginas.php",array("catcod"=>$catcod,"pagcodsuperior"=>$valor['pagcod']),$get,$md5);
				$jerarquia.="<a href='pag_paginas.php?".$get."' class='bold'>";
				$jerarquia.=$valor['pagtitulo']."</a> &raquo; ";
			}
			else
				$jerarquia.="<span class=\"bold\">".$valor['pagtitulo']."</span>";

			$i++;
		}
		$nivel=0;

		return true;
	} 
	
//----------------------------------------------------------------------------------------- 
// Retorna la rama ascendente de una página

// Parámetros de Entrada:
//		pagcod: codigo de página a buscar

// Retorna:
//		jerarquia: un string con la ruta
//		errcat: el error en caso de que se produzca
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function MostrarArbolJerarquia($pagcod,&$jerarquia,$estilos=true)
	{
		$arrcat=array();
		if(!$this->ArregloPadres($pagcod,$arrjerarquia,$nivel))
			return false;

		$i=1;
		$jerarquia="";
		foreach ($arrjerarquia as $clave=>$valor) 
		{
			if ($i!=$nivel)
				$jerarquia.=htmlspecialchars($valor['pagtitulo'],ENT_QUOTES)." &raquo; ";
			else
			{
				if($estilos)
					$jerarquia.="<span class='negrita'>".htmlspecialchars($valor['pagtitulo'],ENT_QUOTES)."</span>";	
				else
					$jerarquia.=htmlspecialchars($valor['pagtitulo'],ENT_QUOTES);	
			}
			$i++;
		}
		$nivel=0;
		
		return true;
	} 

//----------------------------------------------------------------------------------------- 
// Retorna un array con todo el arbol dependiente del pagcod ingresado

// Parámetros de Entrada:
//		pagcod: raiz del arbol a retornar. Si vale "", entonces retorna el arbol completo de la página

// Retorna:
//		arbol: array con el resultado de la consulta.
//					Además de la información del categoria, se agregan los subindices:
//						subarbol: arbol con las páginas dependientes de la pagina
//						ruta: jerarquia ascendente desde el categoria actual hasta la raiz
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ArmarArbolPaginas($pagcod,&$arbol)
	{
		//traigo primero todos los hijos dela página solicitada
		$total=0;
		if(!$this->ArregloHijos($pagcod,$arbol,$total))
			return false;
		
		//ordeno por nombre los categorias
/*		$arbol=FuncionesPHPLocal::array_column_sort($arbol,"catnom");*/
		
		//recorro todos las páginas para asignar la ruta y armar el subarbol dependiente
		foreach($arbol as $indice => $datos)
		{
			$arbol[$indice]["subarbol"]=array();
	
			if(!$this->MostrarArbolJerarquia($datos["pagcod"],$jerarquia))
				return false;
			$arbol[$indice]["ruta"]=$jerarquia;
			
			//si tiene hijos entonces llamo a la funcion recursivamente para armar el subarbol dependiente
			if($this->TieneHijos($datos["pagcod"],$ok) && $ok)
			{
				if(!$this->ArmarArbolPaginas($datos["pagcod"],$arbol[$indice]["subarbol"]))
					return false;
			}
		}
		
		return true;
	}


//----------------------------------------------------------------------------------------- 
// Función recursiva que busca si un pagcod está en el arbol dado

// Parámetros de Entrada:
//		arbol
//		pagcod

// Retorna:
//		la función retorna true si no se produzco error y el codigo de pagina está en el arbol, false en caso contrario

	public function BuscarCategoriaEnArbol(&$arbol,$pagcod)
	{
		foreach($arbol as $clave => $datos)
		{
			if($datos["pagcod"]==$pagcod)
				return true;
				
			if(count($datos["subarbol"])>0)
			{
				if($this->BuscarCategoriaEnArbol($datos["subarbol"],$pagcod))
					return true;
			}
		}

		return false;
	}



//----------------------------------------------------------------------------------------- 
//ABM DE PAGINAS.-
//----------------------------------------------------------------------------------------- 

//----------------------------------------------------------------------------------------- 

//----------------------------------------------------------------------------------------- 
// Modifica los datos de una página

// Parámetros de Entrada:

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function ModificarPagina($datos,&$datosnuevos)
	{
		$datos['pagcuerpo'] = preg_replace("/<p[^>]*><\\/p[^>]*>/",'<div class="space">&nbsp;</div>',$datos['pagcuerpo']);
		$datos['pagcopete'] = preg_replace("/<p[^>]*><\\/p[^>]*>/",'<div class="space">&nbsp;</div>',$datos['pagcopete']);
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
			
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, pagina inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		$datospagina =  $this->conexion->ObtenerSiguienteRegistro($resultado); 
		
		$oPaginaWorkflowRoles = new cPaginasWorkflowRoles($this->conexion,$this->formato);
		$datosacciones['pagestadocod'] = $datospagina['pagestadocod'];
		$datosacciones['paginaworkflowcod'] = $datos['paginaworkflowcod'];
		$datosacciones['rolcod'] = $datos['rolcod'];
		if (!$oPaginaWorkflowRoles->PuedeRealizarAccionPagina($datosacciones,$datosworkflow))
		{	
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, accion erronea. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		if (!$this->_ValidarDatosModificar($datos))
			return false;

		if (!isset ($datos['pagcodsuperior']) || $datos['pagcodsuperior']=="")
			$datos['pagcodsuperior']="NULL";
			
		$erroren = "";
		$oProcesarCuerpo = new cProcesarCuerpo($this->conexion,$this->formato);
		
		$datosTamanio = array();
		$datosTamanio['fotoizq'] = TAMANIOPAGFOTOI;
		$datosTamanio['fotoder'] =TAMANIOPAGFOTOD;
		$datosTamanio['fotocen'] =TAMANIOPAGFOTOC;

		$datosTamanio['videoizq'] = TAMANIOPAGVIDEOI;
		$datosTamanio['videoder'] =TAMANIOPAGVIDEOD;
		$datosTamanio['videocen'] =TAMANIOPAGVIDEOC;
		$datosTamanio['videoalto'] =TAMANIOPAGVIDEOALTO;
		$oProcesarCuerpo->SetearTamanios($datosTamanio);
		
		$sql = "SELECT a.multimediaconjuntocod, a.pagmultimediatitulo, b.*, c.multimediacatcarpeta FROM pag_paginas_mul_multimedia AS a INNER JOIN mul_multimedia AS b ON a.multimediacod=b.multimediacod 
				INNER JOIN mul_multimedia_categorias AS c ON b.multimediacatcod=c.multimediacatcod 
				WHERE pagcod=".$datos['pagcod']." and a.pagmultimediamuestrahome=0 ORDER BY a.multimediaconjuntocod ASC, pagmultimediaorden ASC";
		$this->conexion->_EjecutarQuery($sql,$erroren,$resultado,$errno);
		$numfilas = $this->conexion->ObtenerCantidadDeRegistros($resultado	);
		$datosImagenes = array();
		$datosVideos = array();
		$datosAudios = array();
		$datosArchivos = array();
		if($numfilas>0)
		{
			while($multimedia = $this->conexion->ObtenerSiguienteRegistro($resultado))
			{
				
				if($multimedia['multimediaconjuntocod']==FOTOS)
				{
					$urlImagen = $multimedia['multimediacatcarpeta']."N/".$multimedia['multimediaubic'];
					$datosCarga['ubicacion'] = $urlImagen;
					$datosCarga['epigrafe'] = $multimedia['pagmultimediatitulo'];
					$datosImagenes[] = $datosCarga;
				}
				if($multimedia['multimediaconjuntocod']==VIDEOS)
				{
					$datosCarga['multimediacod'] = $multimedia['multimediacod'];
					$datosCarga['epigrafe'] = $multimedia['pagmultimediatitulo'];
					$datosVideos[] = $datosCarga;
				}
				if($multimedia['multimediaconjuntocod']==FILES)
				{
					$datosCarga['multimediacod'] = $multimedia['multimediacod'];
					$datosCarga['url'] = CARPETA_SERVIDOR_MULTIMEDIA."noticias/archivos/".$multimedia['multimediaubic'];
					$datosArchivos[] = $datosCarga;
				}
				
				if($multimedia['multimediaconjuntocod']==AUDIOS)
					$datosAudios[] = $multimedia;

			}
		}
		$cuerpo = $datos['pagcuerpo'];
		$cuerpo = $oProcesarCuerpo->ProcesarImagenesCuerpo($datosImagenes,$cuerpo);
		$cuerpo = $oProcesarCuerpo->ProcesarVideosCuerpo($datosVideos,$cuerpo);
		$cuerpo = $oProcesarCuerpo->reemplazarFrasesWide($cuerpo);
		$cuerpo = $oProcesarCuerpo->ProcesarAtajos($cuerpo);
		$cuerpo = $oProcesarCuerpo->ProcesarBotones($datosArchivos,$cuerpo);
		$cuerpo = $oProcesarCuerpo->ProcesarGaleriaMosaico($datosImagenes,$cuerpo);
		$cuerpo = $oProcesarCuerpo->ProcesarBotonesLinks($cuerpo);
		
		$datos['pagcuerpoprocesado'] = $cuerpo;

		if(!$this->Modificar($datos))
			return false;

		$datosnuevos['cambioestado']=false; 
		if ($datospagina['pagestadocod']!=$datosworkflow['paginaestadocodfinal'])	
		{
			$datosestado['pagcod'] = $datos['pagcod'];
			$datosestado['pagestadocod'] = $datosworkflow['paginaestadocodfinal'];
			if (!$this->ActualizarEstado($datosestado))
				return false;
			$datosnuevos['cambioestado']=true; 
		}
		$datosnuevos['pagestadocod'] = $datosworkflow['paginaestadocodfinal'];
		$datosnuevos['pagestadodesc'] = $datosworkflow['pagestadodesc'];
		
		return true;
	} 
	
	
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Modificar los datos de la pagina a una pagina publiada //NO VALIDA DATOSS

// Parámetros de Entrada:
//		datos: array asociativo con los datos a modificar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ModificarPaginaDuplicada($datos)
	{
		if (!isset ($datos['pagcodsuperior']) || $datos['pagcodsuperior']=="")
			$datos['pagcodsuperior']="NULL";
		if (!parent::Modificar($datos))
			return false;

		return true;
	}
	
	
	
//----------------------------------------------------------------------------------------- 
// Inserta un categoria nuevo.

// Parámetros de Entrada:

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function InsertarPagina($datos,&$codigoinsertado,&$datosnuevos)
	{
		if (!$this->_ValidarDatosAlta($datos))
			return false;
		
		if (!isset ($datos['pagcodsuperior']) || $datos['pagcodsuperior']=="")
			$datos['pagcodsuperior']="NULL";

		$oProcesarCuerpo = new cProcesarCuerpo($this->conexion,$this->formato);
		$oPaginaWorkflowRoles = new cPaginasWorkflowRoles($this->conexion,$this->formato);
		$datosacciones['pagestadocod'] = PAGEDICION;
		$datosacciones['paginaworkflowcod'] = $datos['paginaworkflowcod'];
		$datosacciones['rolcod'] = $datos['rolcod'];
		if (!$oPaginaWorkflowRoles->PuedeRealizarAccionPagina($datosacciones,$datosworkflow))
		{	
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, accion erronea. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}


		$datos['pagcopiacodorig']="NULL";
		$datos['pagcopiacod']="NULL";
		$this->ObtenerProximoOrden($datos,$proxorden);	
		$datos['pagorden']= $proxorden;
		$datosnuevos['cambioestado']=false; 
		$datos['pagestadocod'] = PAGEDICION;
		if ($datosworkflow['paginaestadocodfinal']!=PAGEDICION)	
		{
			$datos['pagestadocod'] = $datosworkflow['paginaestadocodfinal'];
			$datosnuevos['cambioestado']=true; 
		}
		$datosnuevos['pagestadocod'] = $datosworkflow['paginaestadocodfinal'];
		$datosnuevos['pagestadodesc'] = $datosworkflow['pagestadodesc'];

		$datos['pagcuerpo'] = preg_replace("/<p[^>]*><\\/p[^>]*>/",'<div class="space">&nbsp;</div>',$datos['pagcuerpo']);
		$datos['pagcopete'] = preg_replace("/<p[^>]*><\\/p[^>]*>/",'<div class="space">&nbsp;</div>',$datos['pagcopete']);		


		$cuerpo = $datos['pagcuerpo'];
		$cuerpo = $oProcesarCuerpo->reemplazarFrasesWide($cuerpo);
		
		$datos['pagcuerpoprocesado'] = $cuerpo;

		if (!parent::Insertar($datos,$codigoinsertado))
			return false;

			
		return true;
	} 

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Insertar una nueva pagina duplicada //NO VALIDA DATOSS

// Parámetros de Entrada:
//		datos: array asociativo con los datos a agregar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function InsertarPaginaDuplicada($datos,&$codigoinsertado)
	{
		$datos['pagcopiacod'] = "NULL";
		if (!parent::Insertar($datos,$codigoinsertado))
			return false;
			
			
			

		return true;
	}
//----------------------------------------------------------------------------------------- 
// Inserta todos los datos adicionales de una pagina a otra.

// Parámetros de Entrada:
//		datos: arreglo de datos
//			pagmodulocodinsertar = codigo de la pagina 

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function DuplicarDatosAdicionalesPagina($datos,$pagcodnueva)
	{

		$oPaginasModulos = new cPaginasModulos($this->conexion,$this->formato);		
		if (!$oPaginasModulos->BuscarxPagina($datos,$resultado,$numfilas))
			return false;	
		if($numfilas>0){			
			while ($datospaginasmodulos = $this->conexion->ObtenerSiguienteRegistro($resultado)){
				$datospaginasmodulos['pagcod']=$pagcodnueva;
				if (!$oPaginasModulos->Insertar($datospaginasmodulos,$codigoinsertado))
					return false;
			}			
		}
			
		//BUSCO E INSERTO LOS DATOS DE GALERIAS DE LA PAGINA EN LA TABLA PAG_PAGINAS_MUL_MULTIMEDIA
		$oPaginasMultimedia = new cPaginasMultimedia($this->conexion,$this->formato);		
		if (!$oPaginasMultimedia->BuscarMultimediaxCodigoPagina($datos,$resultadomultimedia,$numfilasmultimedia))
			return false;	

		if($numfilasmultimedia>0){
			while ($datospaginamultimediainsertar = $this->conexion->ObtenerSiguienteRegistro($resultadomultimedia)){
				$datospaginamultimediainsertar['pagcod']=$pagcodnueva;
				if (!$oPaginasMultimedia->InsertarDuplicar($datospaginamultimediainsertar))
					return false;
			}
		}			
			
		return true;
	}
	
// Elimina los datos adicionales de una pagina

// Parámetros de Entrada:
//		datos: array
//			noticiacod: codigo de noticia a eliminar de las tablas relacionadas

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function EliminarDatosAdicionales($datos)
	{
		$oPaginasModulos = new cPaginasModulos($this->conexion,$this->formato);		
		$oPaginasMultimedia = new cPaginasMultimedia($this->conexion,$this->formato);		

		//ELIMINO EL MODULO DE LA PAGINA EN LA TABLA PAG_PAGINAS_MODULOS
		if (!$oPaginasModulos->EliminarxPagina($datos))
			return false;
			
		//ELIMINO EL MODULO DE LA PAGINA EN LA TABLA PAG_PAGINAS_MODULOS
		if (!$oPaginasModulos->EliminarColumnas($datos))
			return false;
			
		//ELIMINO LA PAGINA EN LA TABLA PAG_PAGINAS_MUL_MULTIMEDIA
		if (!$oPaginasMultimedia->EliminarCompletoxPaginacod($datos))
			return false;
				
		return true;
	}
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Publica una pagina

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
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, pagina inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		$datospagina =  $this->conexion->ObtenerSiguienteRegistro($resultado); 
		
		$oPaginasWorkflowRoles = new cPaginasWorkflowRoles($this->conexion,$this->formato);
		$datosacciones['pagestadocod'] = $datospagina['pagestadocod'];
		$datosacciones['paginaworkflowcod'] = $datos['paginaworkflowcod'];
		$datosacciones['rolcod'] = $datos['rolcod'];
		if (!$oPaginasWorkflowRoles->PuedeRealizarAccionPagina($datosacciones,$datosworkflow))
		{	
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, accion erronea. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		$oPaginaPublicar = new cPaginasPublicacion($this->conexion,$this->formato);
		
		if($datospagina['pagcopiacodorig']!='')
		{
			if(!$oPaginaPublicar->ActualizarCambiosPaginaPublicada($datospagina))
				return false;
		}else{

			if (!$oPaginaPublicar->Publicar($datospagina,$codigoinsertado))
					return false;

			$datosestado['pagcod'] = $datospagina['pagcod'];
			$datosestado['pagestadocod'] = $datosworkflow['paginaestadocodfinal'];
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

	public function ActualizarEstado($datos)
	{

		if (!parent::ActualizarEstado($datos))
			return false;	
	

		return true;
	
	}



// Funcion que modifica el campo de pagcodcopia que indica cual es el código de copia de una pagina
	
// Parámetros de Entrada:
//		datos: array asociativo con los datos a agregar
//			pagcod: codigo de la pagina a modificar
//			codigoinsertadocopia: codigo de la copia de la pagina

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ActualizarCopiaOriginal($datos,$codigoinsertadocopia)
	{
		if (!parent::ActualizarCopiaOriginal($datos,$codigoinsertadocopia))
			return false;

		return true;		
	}	

//-----------------------------------------------------------------------------------------
//							FUNCIONES PRIVADAS	
//----------------------------------------------------------------------------------------- 

//----------------------------------------------------------------------------------------- 
// Retorna true o false si algunos de los campos esta vacio

// Parámetros de Entrada:
//		pagtitulo = titulo de la página.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function _ValidarDatosVacios($datos)
	{
		
		if ($datos['pagtitulo']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un título. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if ($datos['catcod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una categoria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		$oPaginasCategorias = new cPaginasCategorias($this->conexion,$this->formato);
		if(!$oPaginasCategorias->BuscarxCodigo($datos,$resultadocat,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una categoria valida. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
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


		if (isset($datos['pagcodsuperior']) && $datos['pagcodsuperior']!="")
		{
			$datosavalidar['pagcod'] = $datos['pagcodsuperior'];
			if (!$this->BuscarxCodigo($datosavalidar,$resultado,$numfilas))
				return false;
				
			if ($numfilas!=1)	
			{
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_INF,"Error, el código de la pagina no existe. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
				return false;
			}
		}

	 	
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false al dar de alta si algunos de los campos esta vacio 

// Parámetros de Entrada:
//		pagtitulo = titulo de la página.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	function _ValidarDatosAlta($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false al modificar si algunos de los campos esta vacio 
// Parámetros de Entrada:
//		pagtitulo = titulo de la página.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function _ValidarDatosModificar($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;

		if (isset($datos['pagcodsuperior']) && $datos['pagcodsuperior']!="")
		{
			if ($datos['pagcodsuperior']==$datos['pagcod'])
			{
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_INF,"Error, no puede seleccionar la misma página como superior. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
				return false;
			}
			
			$this->ArregloHijos($datos['pagcod'],$arreglohijos,$cantidadarreglo);
			foreach($arreglohijos as $hijos)
			{
				if ($hijos['pagcod'] == $datos['pagcodsuperior'])
				{
					FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_INF,"Error, no puede seleccionar una pagina inferior como padre. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
					return false;
				}	
			}
		}
		return true;
	}
//----------------------------------------------------------------------------------------- 
// Retorna true o false si pudo eliminar la categoria

// Parámetros de Entrada:
//		pagcod = codigo de categoria a eliminar.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Eliminar($datos)
	{
		if (!$this->_ValidarEliminar($datos,$datospagina))
			return false;

		//ELIMINO TODOS LOS DATOS ADICIONALES 
		if(!$this->EliminarDatosAdicionales($datos))
			return false;
			
		if (!parent::Eliminar($datos))
			return false;
			
			
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false si la pagina tiene hijos

// Parámetros de Entrada:
//		pagcod = codigo de categoria a eliminar.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function _ValidarEliminar($datos,&$datospagina)
	{
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
			
		if ($numfilas!=1)	
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"La página no se encuentra. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		$datospagina = $this->conexion->ObtenerSiguienteRegistro($resultado);

		if (!$this->ArregloHijos($datos['pagcod'],$arrcat,$cantidadarreglo,true))
			return false;
	
		if ($cantidadarreglo>0)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"La página contiene subpáginas asociadas. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		
		return true;
	}
//----------------------------------------------------------------------------------------- 
// Retorna true o false si pudo cambiarle el estado a la página a eliminida

// Parámetros de Entrada:
//		pagcod = codigo de página.
//      pagestadocod = nuevo estado de la pagina

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function ModificarEstadoPagina($datos)
	{
		$datos['pagestadocod']= PAGELIMINADA;
		if (!parent::ModificarEstadoPagina($datos))
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

	public function EliminarPaginaCompleta($datos)
	{

		if (!$this->_ValidarEliminar($datos,$datosvalidados))
			return false;

		if ($datosvalidados['pagcopiacodorig']!="")
		{
			$codigonull="NULL";
			$datosacualizar["pagcod"]=$datosvalidados['pagcopiacodorig'];
			 if (!$this->ActualizarCopiaOriginal($datosacualizar,$codigonull))
				return false;
			//ELIMINO LA PAGINA COMPLETA
			if(!$this->Eliminar($datos))
				return false;
		}else
		{
			if(!$this->ModificarEstadoPagina($datosvalidados))
				return false;
		}
		
		if ($datosvalidados['pagestadocod']==PAGPUBLICADA)
		{
			$oPaginasPublicacion = new cPaginasPublicacion($this->conexion,$this->formato);
			if(!$oPaginasPublicacion->Eliminar($datosvalidados))
				return false;
			
		}

		return true;		
	}
	
	
//----------------------------------------------------------------------------------------- 
// Retorna proxorden. proximo orden de la página

// Parámetros de Entrada:
//		pagcod = codigo de la página.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	private function ObtenerProximoOrden($datos,&$proxorden)
	{
		$proxorden = 0;
		if (!$this->BuscarPaginaUltimoOrden($datos,$resultado,$numfilas))
			return false;
			
		if ($numfilas!=0)
		{
			$datos = $this->conexion->ObtenerSiguienteRegistro($resultado);
			$proxorden = $datos['maximo'] + 1;
		}
		return true;
	}
//----------------------------------------------------------------------------------------- 
//Retorna true o false si pudo cambiar el orden de la página

// Parámetros de Entrada:
//		pagorden = orden de las páginas.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no		
	public function ModificarOrden($datos)
	{
		$arreglopáginas = explode(",",$datos['orden']);
		
		$datosmodif['pagorden'] = 1;
		foreach ($arreglopáginas as $pagcod)
		{
			$datosmodif['pagcod'] = $pagcod;
			if (!parent::ModificarOrden($datosmodif))
					return false;
			$datosmodif['pagorden']++;
		}
		
		return true;
	}
	
	






}// FIN CLASE

?>