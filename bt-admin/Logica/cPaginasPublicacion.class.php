<?php 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con la lógica para el manejo de las noticias relacionadas

include(DIR_CLASES_DB."cPaginasPublicacion.db.php");

class cPaginasPublicacion extends cPaginasPublicaciondb	
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
// Retorna una consulta si es una pagina publicada

// Parámetros de Entrada:
//		datos: arreglo de datos
//			pagcod = codigo de la pagina (la que se quiere buscar es la noticia en publicacion)

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function EsPaginaPublicada($datos,&$resultado,&$numfilas)
	{
		if (!parent::EsPaginaPublicada($datos,$resultado,$numfilas))
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
			'xpagcod'=> 0,
			'pagcod'=> "",
			'xcatcod'=> 0,
			'catcod'=> "-1",
			'orderby'=> "pagorden ASC",
			'limit'=> ""
		);	

		if (isset ($datos['pagcod']) && $datos['pagcod']!="")
		{
			$sparam['pagcod'] = $datos['pagcod'];
			$sparam['xpagcod'] = 1;
		}
			
		if (isset ($datos['catcod']) && $datos['catcod']!="")
		{
			$sparam['catcod']= $datos['catcod'];
			$sparam['xcatcod']= 1;
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
// Retorna un query con los datos de una pagina superior

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
// Duplica una pagina completa a una nueva y las relaciona con un código de copia.

// Parámetros de Entrada:
//		datos: arreglo de datos
//			pagcod = codigo de la pagina (la que se quiere buscar es la noticia en publicacion)

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BajarPaginaaEdicion($datos,&$pagcodnueva)
	{
		$oPagina = new cPaginas($this->conexion,$this->formato);

		if (!$oPagina->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, pagina inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		$datospaginaorig =$this->conexion->ObtenerSiguienteRegistro($resultado);
		if ($datospaginaorig['pagcopiacod']!="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, la pagina ya se encuentra duplicada. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}


		$oPaginasWorkflowRoles = new cPaginasWorkflowRoles($this->conexion,$this->formato);
		$datosacciones['pagestadocod'] = $datospaginaorig['pagestadocod'];
		$datosacciones['paginaworkflowcod'] = $datos['paginaworkflowcod'];
		$datosacciones['rolcod'] = $datos['rolcod'];
		if (!$oPaginasWorkflowRoles->PuedeRealizarAccionPagina($datosacciones,$datosworkflow))
		{	
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, accion erronea. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		$datospaginaorig['pagestadocod'] = $datosworkflow['paginaestadocodfinal'];
		$datospaginaorig['pagcopiacodorig']=$datospaginaorig['pagcod'];
		$datos['pagcod']=$datospaginaorig['pagcopiacodorig'];
		$datospaginaorig['pagcodsuperior']=($datospaginaorig['pagcodsuperior']=="")?"NULL":$datospaginaorig['pagcodsuperior'];
		
		if (!$oPagina->InsertarPaginaDuplicada($datospaginaorig,$pagcodnueva))
			return false;

		if(!$oPagina->DuplicarDatosAdicionalesPagina($datos,$pagcodnueva))
			return false;			

		if (!$oPagina->ActualizarCopiaOriginal($datos,$pagcodnueva))
			return false;

		$oPaginasModulos = new cPaginasModulos($this->conexion,$this->formato);
		$datospaginanueva['pagcod'] = $pagcodnueva;
		if (!$oPaginasModulos->GenerarColumnas($datospaginanueva))
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

	public function ActualizarCambiosPaginaPublicada($datosacualizar)
	{
		$oPaginas = new cPaginas($this->conexion,$this->formato);
		//COPIO TODOS LOS DATOS NUEVOS EN LA VARIABLE DATOSNOTICIAS Y SOBREESCRIBO EL NOTICIACOD
		$datospagina=$datosacualizar;
		$datospagina['pagcod']=$datosacualizar['pagcopiacodorig'];

		//ELIMINO TODOS LOS DATOS ADICIONALES 
		if(!$oPaginas->EliminarDatosAdicionales($datospagina))
			return false;

		//INSERTO LOS NUEVOS DATOS ADICIONALES
		if(!$oPaginas->DuplicarDatosAdicionalesPagina($datosacualizar,$datospagina['pagcod']))
			return false;		
		
		//AL PUBLICAR SETEO EN NULL EL CAMPO COPIA DE LA PAGINA ORIGINAL
		$codigonull="NULL";
		 if (!$oPaginas->ActualizarCopiaOriginal($datospagina,$codigonull))
			return false;

		//AL PUBLICAR SETEO EN NULL EL CAMPO COPIA DE LA PAGINA ORIGINAL
		if (!$oPaginas->ModificarPaginaDuplicada($datospagina,$codigonull))
			return false;

		if (!$this->Modificar($datospagina))
			return false;

		//ELIMINO LA COPIA DE LA PAGINA COMPLETA
		if(!$oPaginas->Eliminar($datosacualizar))
			return false;


		$oPaginasModulos = new cPaginasModulos($this->conexion,$this->formato);
		if (!$oPaginasModulos->GenerarColumnas($datospagina))
			return false;

		if (!$this->GenerarJsonxCodigo($datospagina))
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

		if (!$this->GenerarJsonxCodigo($datosvalidados))
			return false;	

		return true;
	
	}

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Actualiza el estado de una noticia publicada a una despublicada\.

// Parámetros de Entrada:
//		noticiacod: codigo de noticia a dar de baja

// Retorna:
// la función retorna true o false si se pudo ejecutar con éxito o no

	public function ActualizarEstadoDespublicada($datos)
	{
		$datos['pagestadocod']=NOTDESPUBLICADAS;
		$oPagina = new cPaginas($this->conexion);
		if (!$oPagina->ActualizarEstado($datos))
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

	
	public function DespublicarPagina($datos)
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

	protected function Modificar($datos)
	{
		if (!$this->_ValidarPublicar($datos,$datosvalidados))
			return false;

		if (!parent::Modificar($datosvalidados))
			return false;

		return true;
	
	}


//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Eliminar una pagina publicada

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

// function que valida los datos al momento de publicar una nueva noticia

// Parámetros de Entrada:
//		datos: array asociativo con los datos a validar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	private function _ValidarEliminar($datos)
	{
		if (!$this->EsPaginaPublicada($datos,$resultado,$numfilas))
			return false;
		
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, pagina publica inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		
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
			'pagsubtitulo'=> "",
			'pagtitulocorto'=> "",
			'pagcopete'=> "",
			'pagcuerpo'=> "",
			'pagcodsuperior'=> ""
			);
		
		$datosvalidados = array_merge($datosvalidados,$datos);

		if ($datosvalidados['pagsubtitulo']=="")
			$datosvalidados['pagsubtitulo'] = "NULL";
		if ($datosvalidados['pagtitulocorto']=="")
			$datosvalidados['pagtitulocorto'] = "NULL";
		if ($datosvalidados['pagcopete']=="")
			$datosvalidados['pagcopete'] = "NULL";
		if ($datosvalidados['pagcuerpo']=="")
			$datosvalidados['pagcuerpo'] = "NULL";
		if ($datosvalidados['pagcodsuperior']=="")
			$datosvalidados['pagcodsuperior'] = "NULL";

		$datosvalidados['pagdominio'] = $this->ArmarUrl($datosvalidados['pagtitulo']);
		
		//INSERTO EL NOMBRE DE LA CATEGORIA
		$oCategoria = new cPaginasCategorias($this->conexion,$this->formato);
		if(!$oCategoria->BuscarxCodigo($datos,$resultadocat,$numfilascat))
			return false;
			
		$datoscategoria = $this->conexion->ObtenerSiguienteRegistro($resultadocat);	
		$datosvalidados['catnom'] = $datoscategoria['catnom'];

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
	

	
	private function ArmarUrl($titulo)
	{
		
		$dominio="";
		$dominio = FuncionesPHPLocal::EscapearCaracteres($titulo);
		$dominio=preg_replace('/[^a-zA-Z0-9-_ ]/', '-', trim($dominio));
		$dominio=str_replace(' ', '-', trim($dominio));
			
		return $dominio;
	}
				
	public function GenerarJsonxCodigo($datos)
	{		
		if(!isset($datos['pagcod']) || $datos['pagcod']=="")
			return false;
		
		if(!$this->BusquedaAvanzada($datos,$resultado,$numfilas))
			return false;
			
		$datospaginas = $this->conexion->ObtenerSiguienteRegistro($resultado);
		
		if(!is_dir(PUBLICA."json/")){ 
			@mkdir(PUBLICA."json/");
		}
		
		if(!is_dir(PUBLICA."json/paginas/")){ 
			@mkdir(PUBLICA."json/paginas/");
		}
			
		$datospaginasJson = $datospaginas;
		$datospaginasJson = FuncionesPHPLocal::DecodificarUtf8($datospaginasJson);
		$json = json_encode($datospaginasJson);
		
		if(!FuncionesPHPLocal::GuardarArchivo(PUBLICA."json/paginas/",$json,$datospaginas['pagdominio'].".json"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_INF,"Error, al generar el json de la pagina. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}		
			
}//FIN CLASE

?>