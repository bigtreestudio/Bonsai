<?php  
include(DIR_CLASES_DB."cGalerias.db.php");

class cGalerias extends cGaleriasdb	
{
	protected $conexion;
	protected $formato;
	protected $activos;

	
// CLASE cGalerias
// EN CASO DE QUE LA VARIABLE ACTIVOS SE ENCUENTRE EN LA LLAMADA A LA CLASE,
// LAS FUNCIONES SE TRABAJARAN CON SOLO  LOS DATOS DE LOS CATEGORIAS QUE SE ENCUENTREN
// EN ESTADO ACTIVO, O PENDIENTES DE MODIFICACION.

//-----------------------------------------------------------------------------------------
//  LAS FUNCIONES QUE HASTA AHORA TIENEN ESTO SON:  
// 	ArregloHijos
//  TieneHijos
//  TraerDatosCategoria
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


//------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de un categoria

// Parámetros de Entrada:
//		catcod: catetoria a buscar

// Retorna:
//		resultado= Arreglo con todos los datos de un categoria.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		
		return true;
	} 
//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de una galeri

// Parámetros de Entrada:
//		catestado: estado a buscar

// Retorna:
//		resultado= Arreglo con todos los datos de un categoria.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	public function BuscaGaleriasxEstado($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscaGaleriasxEstado($datos,$resultado,$numfilas))
			return false;
		
		return true;
	}

//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de un categoria 

// Parámetros de Entrada:
//		catsuperior: categoria superior a buscar.Si vale "", entonces retorna el raiz de la categorias

// Retorna:
//		resultado= Arreglo con todos los datos de un categoria.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	public function BuscarAvanzadaxGaleria($datos,&$resultado,&$numfilas)
	{
		 
		$sparam=array(
			'pxgaleriatitulo'=> 0,
			'pgaleriatitulo'=> "",
			'pxgaleriaestadocod'=> 0,
			'pgaleriaestadocod'=> "-1",
			'pxmultimediaconjuntocod'=>0,
			'pmultimediaconjuntocod'=>"",
			'pxcatcod'=>0,
			'pcatcod'=>"",
			'orderby'=> "galeriaorden ASC",
			'limit'=> ""
			);			 
			
		if (isset($datos['galeriatitulo']) && $datos['galeriatitulo']!="")
		{
			$sparam['pgaleriatitulo'] = $datos['galeriatitulo'];
			$sparam['pxgaleriatitulo'] = 1;
		}
		if (isset($datos['galeriaestadocod']) && $datos['galeriaestadocod']!="")
		{
			$sparam['pgaleriaestadocod'] = $datos['galeriaestadocod'];
			$sparam['pxgaleriaestadocod'] = 1;
		}
		if (isset($datos['multimediaconjuntocod']) && $datos['multimediaconjuntocod']!="")
		{
			$sparam['pmultimediaconjuntocod'] = $datos['multimediaconjuntocod'];
			$sparam['pxmultimediaconjuntocod'] = 1;
		}
		if (isset($datos['catcod']) && $datos['catcod']!="")
		{
			$sparam['pcatcod'] = $datos['catcod'];
			$sparam['pxcatcod'] = 1;
		}
		if (isset ($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];
		if (isset ($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];

	
		if (!parent::BuscarAvanzadaxGaleria($sparam,$resultado,$numfilas))
			return false;
		
		return true;
	}
	

//----------------------------------------------------------------------------------------- 
// Retorna la rama ascendente de un categoria con redirección

// Parámetros de Entrada:
//		catcod: categoria a buscar

// Retorna:
//		jerarquia: un string con la ruta (href)
//		errcat: el error en caso de que se produzca
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function MostrarJerarquia($catcod,&$jerarquia,&$nivel)
	{
		$i=1;
		$jerarquia="";
		$nivel=0;
		$arrjerarquia = array();


		if ($nivel!=0)
			$jerarquia.="<a href='gal_galerias.php'>Inicio</a> &raquo; ";
		else
			$jerarquia.="<span class=\"bold\">Inicio</span>";
		
		foreach ($arrjerarquia as $clave=>$valor) 
		{
			
			if ($i!=$nivel)
			{ 
				FuncionesPHPLocal::ArmarLinkMD5("gal_galerias.php",array("galeriacod"=>$valor['galeriacod']),$get,$md5);
				$jerarquia.="<a href='gal_galerias.php?galsuperior=";
				$jerarquia.= FuncionesPHPLocal::HtmlspecialcharsBigtree($valor['galeriacod'],ENT_QUOTES);
				$jerarquia.="&md5=";
				$jerarquia.= FuncionesPHPLocal::HtmlspecialcharsBigtree($md5,ENT_QUOTES);
				$jerarquia.="' class='bold'>";
				$jerarquia.=$valor['galeriadesc']."</a> &raquo; ";
			}
			else
				$jerarquia.="<span class=\"bold\">".$valor['galeriadesc']."</span>";

			$i++;
		}
		$nivel=0;

		return true;
	} 
	
//----------------------------------------------------------------------------------------- 
// Retorna proxorden. proximo orden de la galeria

// Parámetros de Entrada:
//		galeriacod = codigo de galeria.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	private function ObtenerProximoOrden($datos,&$proxorden)
	{
		$proxorden = 0;
		if (!parent::BuscarGaleriaUltimoOrden($datos,$resultado,$numfilas))
			return false;
			
		if ($numfilas!=0)
		{
			$datos = $this->conexion->ObtenerSiguienteRegistro($resultado);
			$proxorden = $datos['maximo'] + 1;
		}
		return true;
	}


//----------------------------------------------------------------------------------------- 
//ABM DE GALERIAS.-
//----------------------------------------------------------------------------------------- 

//----------------------------------------------------------------------------------------- 

//----------------------------------------------------------------------------------------- 
// Modifica los datos de un a galeria 

// Parámetros de Entrada:

// Retorna:
//		errgal: el error en caso de que se produzca
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function ModificarGaleria($datos)
	{
		
		if (!$this->_ValidarModificar($datos))
			return false;
		
		$this->_SetearNull($datos);
		if(!$this->Modificar($datos))
			return false;

		$datos["galeriadominio"] = FuncionesPHPLocal::EscapearCaracteres($datos["galeriatitulo"]);
		$datos["galeriadominio"]=preg_replace('/[^a-zA-Z0-9-_ ]/', '-', trim($datos["galeriadominio"]));
		$datos["galeriadominio"]="".str_replace(' ', '-', trim($datos["galeriadominio"]))."_g".$datos["galeriacod"];
	
		if(!$this->GenerarDominio($datos))
			return false;	
			
		if (!$this->Publicar($datos))
				return false;		
				
		return true;
	} 
	
//----------------------------------------------------------------------------------------- 
// Inserta una galeria nueva.

// Parámetros de Entrada:

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function InsertarGaleria($datos,&$codigoinsertado)
	{

		if (!$this->_ValidarInsertar($datos))
			return false;
		
		$this->ObtenerProximoOrden($datos,$proxorden);	
		$datos['galeriaorden']= $proxorden;
		$datos['galeriaestadocod'] = ACTIVO;
	
		$this->_SetearNull($datos);
		if(!$this->Insertar($datos,$codigoinsertado))
			return false;
			

		$datos["galeriadominio"] = FuncionesPHPLocal::EscapearCaracteres($datos["galeriatitulo"]);
		$datos["galeriadominio"]=preg_replace('/[^a-zA-Z0-9-_ ]/', '-', trim($datos["galeriadominio"]));
		$datos["galeriadominio"]=str_replace(' ', '-', trim($datos["galeriadominio"]))."_g".$codigoinsertado;
	
		$datos["galeriacod"]=$codigoinsertado;
		if(!$this->GenerarDominio($datos))
			return false;	
			
		if (!$this->Publicar($datos))
				return false;	
			
		return true;
	} 
//----------------------------------------------------------------------------------------- 
// Retorna true o false si pudo cambiarle el estado a las galerias

// Parámetros de Entrada:
//		galeriacod = codigo de galerias.
//      galeriaestadocod = nuevo estado de la galerias

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function GenerarDominio($datos)
	{
		if (!parent::GenerarDominio($datos))
			return false;
			
		return true;	
	}

//----------------------------------------------------------------------------------------- 
//FIN DE ABM DE CATEGORIAS.-
//----------------------------------------------------------------------------------------- 


//-----------------------------------------------------------------------------------------
//							 PRIVADAS	
//----------------------------------------------------------------------------------------- 

//----------------------------------------------------------------------------------------- 
// Retorna True o false, de acuerdo si existe algún categoria de la misma jerarquía que contenga el
// mismo nombre, tambien si alguno que esté pendiente de modificación.

// Parámetros de Entrada:
//		$padre= Codigo del categoria padre.
//		Se pasa el padre pq busco solo los nombres que existen con el mismo padre
//		nombre= nombre del categoria que deseo buscar.


// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function _VerificarNombre ($nombre,$padre,&$datosencontrados)
	{
		$datosencontrados = array();
		if ($padre=="NULL") 
		{
			$datos['catnom'] = $nombre;
			if (!parent::BuscaCategoriasNombreRaiz($datos,$resultado,$numfilas))
				return false;
		}
		else
		{
			$datos['catnom'] = $nombre;
			$datos['catsuperior'] = $padre;
			if (!parent::BuscaCategoriasNombrexCategoriaSuperior($datos,$resultado,$numfilas))
				return false;
		}	
		
		if ($numfilas!=0)
		{	
			$datosencontrados = $this->conexion->ObtenerSiguienteRegistro($resultado);
			return false;
		}
		
		return true;
	}


	private function _SetearNull(&$datos)
	{
		if (!isset($datos['galeriatitulo']) || $datos['galeriatitulo']=="")
			$datos['galeriatitulo']="NULL";

		if (!isset($datos['multimediaconjuntocod']) || $datos['multimediaconjuntocod']=="")
			$datos['multimediaconjuntocod']="NULL";

		if (!isset($datos['catcod']) || $datos['catcod']=="")
			$datos['catcod']="NULL";

		if (!isset($datos['albumcod']) || $datos['albumcod']=="")
			$datos['albumcod']="NULL";

		if (!isset($datos['galeriadominio']) || $datos['galeriadominio']=="")
			$datos['galeriadominio']="NULL";
			
		if (!isset($datos['menutipocod']) || $datos['menutipocod']=="")
			$datos['menutipocod']="NULL";	
			
		if (!isset($datos['menucod']) || $datos['menucod']=="")
			$datos['menucod']="NULL";	
			
		if (!isset($datos['galeriadesc']) || $datos['galeriadesc']=="")
			$datos['galeriadesc']="NULL";	
			
		if (!isset($datos['multimediacod']) || $datos['multimediacod']=="")
			$datos['multimediacod']="NULL";			
			
			
		return true;
	}


//----------------------------------------------------------------------------------------- 
// Retorna true o false si algunos de los campos esta vacio

// Parámetros de Entrada:
//		galeriatitulo = nombre de la galeria.


// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function _ValidarDatosVacios($datos)
	{
		
		if (!isset($datos['galeriatitulo']) || $datos['galeriatitulo']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un nombre de la galeria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		if (!isset($datos['multimediaconjuntocod']) || $datos['multimediaconjuntocod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un tipo de la galeria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		if (!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['multimediaconjuntocod'],"NumericoEntero"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar un campo numérico.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		/*if (!isset($datos['catcod']) || $datos['catcod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un tipo de la galeria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		if (!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['catcod'],"NumericoEntero"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar un campo numérico.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		if (!isset($datos['albumcod']) || $datos['albumcod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un album. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		if (!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['albumcod'],"NumericoEntero"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar un campo numérico.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		if (!isset($datos['galeriadominio']) || $datos['galeriadominio']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un dominio. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		if (!isset($datos['menutipocod']) || $datos['menutipocod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe selecionar un tipo de menú. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		if (!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['menucod'],"NumericoEntero"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar un campo numérico.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		
		if (!isset($datos['menucod']) || $datos['menucod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe selecionar un menú. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		if (!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['menucod'],"NumericoEntero"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar un campo numérico.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		
		if (!isset($datos['galeriadesc']) || $datos['galeriadesc']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una descripción. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		if (!isset($datos['multimediacod']) || $datos['multimediacod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar una foto",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['multimediacod'],"NumericoEntero"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar un campo numérico.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}*/

		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false al dar de alta si algunos de los campos esta vacio o si exite otra categoria con ese nombre

// Parámetros de Entrada:
//		catnom = nombre de la categoria.
//      catsuperior = si existe el cogido de la categoria: 		 

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	function _ValidarInsertar($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;

		if ($datos['multimediaconjuntocod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un tipo de la galeria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false al modificar si algunos de los campos esta vacio de una galeria.

// Parámetros de Entrada:
//		galeriatitulo = nombre de la galeria.
		 

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function _ValidarModificar($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;

		return true;
	}
//----------------------------------------------------------------------------------------- 
// Retorna true o false si pudo eliminar la categoria

// Parámetros de Entrada:
//		catcod = codigo de categoria a eliminar.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function EliminarGaleria($datos)
	{
		if (!$this->PuedeEliminarGaleria($datos,true))
			return false;
			
		//if (!parent::EliminarGaleriaAlbum($datos))
			//return false;			

		//if (!parent::EliminarGaleriaNoticia($datos))
			//return false;			

		//if (!parent::EliminarGaleriaMultimedias($datos))
			//return false;			

		$datosmodificar['galeriacod'] = $datos['galeriacod'];
		$datosmodificar['galeriaestadocod'] = ELIMINADO;
		if (!$this->ModificarEstadoGaleria($datosmodificar))
			return false;

		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false si la galeria.

// Parámetros de Entrada:
//		galeriacod = codigo de galeria a eliminar.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function PuedeEliminarGaleria($datos,$mostrarmsg=false)
	{

		
		return true;
	}
//----------------------------------------------------------------------------------------- 
// Retorna true o false si pudo cambiarle el estado a las galerias

// Parámetros de Entrada:
//		galeriacod = codigo de galerias.
//      galeriaestadocod = nuevo estado de la galerias

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function ModificarEstadoGaleria($datos)
	{
		if (!parent::ModificarEstadoGaleria($datos))
			return false;
			
		if (!$this->Publicar($datos))
				return false;		
			
		return true;	
	}
//----------------------------------------------------------------------------------------- 
// Retorna true o false si pudo cambiarle el estado a ACTIVO a la galeria

// Parámetros de Entrada:
//		catcod = codigo de la galeria.
//      galeriaestadocod = nuevo estado de la galeria

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function ActivarGaleria($datos)
	{
		
		$datosmodificar['galeriacod'] = $datos['galeriacod'];
		$datosmodificar['galeriaestadocod'] = ACTIVO;
		if (!$this->ModificarEstadoGaleria($datosmodificar))
			return false;
		
		return true;
	}
//----------------------------------------------------------------------------------------- 
// Retorna true o false si pudo cambiarle el estado a DESACTIVAR a la galeria

// Parámetros de Entrada:
//		galriacod = codigo de la galeria.
//      galeriaestadocod = nuevo estado de la galeria

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	public function DesActivarGaleria($datos)
	{
		
		$datosmodificar['galeriacod'] = $datos['galeriacod'];
		$datosmodificar['galeriaestadocod'] = NOACTIVO;
		if (!$this->ModificarEstadoGaleria($datosmodificar))
			return false;
		
		return true;
	}


//----------------------------------------------------------------------------------------- 
//Retorna true o false si pudo cambiar el orden de las galerias

// Parámetros de Entrada:
//		galeriaorden = orden de las galerias.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no		
	public function ModificarOrden($datos)
	{
		$arreglogalerias = explode(",",$datos['galeriaorden']);
		
		$datosmodif['galeriaorden'] = 1;
		foreach ($arreglogalerias as $galeriacod)
		{
			$datosmodif['galeriacod'] = $galeriacod;
			if (!parent::ModificarOrden($datosmodif))
					return false;
			if (!$this->Publicar($datosmodif))
				return false;		
			$datosmodif['galeriaorden']++;
		}
		
		return true;
	}
	
	public function Publicar($datos)
	{
		if(!$this->PublicarHtml())
			return false;
			
		if (!$this->PublicarListadoJson())
			return false;
		if (!$this->PublicarJsonxCodigo($datos))
			return false;
		$oAlbums = new cAlbums($this->conexion,$this->formato);	
		$oAlbumsGalerias = new cAlbumsGalerias($this->conexion,$this->formato);	
		if(!$oAlbumsGalerias->BusquedaAvanzada($datos,$resultado,$numfilas))
			return false;
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado)) 
		{
			if (!$oAlbums->Publicar($fila))
				return false;	
		}
		return true;
	}
	
	public function GuardarDatosJson($nombrearchivo,$carpeta,$array)
	{
		$datosJson = FuncionesPHPLocal::DecodificarUtf8($array);
		$jsonData = json_encode($datosJson);
		if(!is_dir($carpeta)){
			@mkdir($carpeta);
		}
		if(!FuncionesPHPLocal::GuardarArchivo($carpeta,$jsonData,$nombrearchivo.".json"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_INF,"Error, al generar el archivo json. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}



	public function EliminarDatosJson($nombrearchivo,$carpeta)
	{
		if(file_exists($carpeta.$nombrearchivo.".json"))
		{
			unlink($carpeta.$nombrearchivo.".json");
		}
		return true;
	}



	public function PublicarListadoJson()
	{
		$nombrearchivo = "galerias";
		$carpeta = PUBLICA;
		if(!$this->GerenarArrayDatosJsonListado($array))
			return false;
		if(count($array)>0)
		{
			if(!$this->GuardarDatosJson($nombrearchivo,$carpeta,$array))
				return false;
		}
		else
		{
			if(!$this->EliminarDatosJson($nombrearchivo,$carpeta))
				return false;
		}
		return true;
	}



	public function GerenarArrayDatosJsonListado(&$array)
	{
		$oMultimedia = new cMultimedia($this->conexion,$this->formato);
		$array = array();
		$datos['galeriaorden'] = ACTIVO;
		if(!$this->BuscarAvanzadaxGaleria($datos,$resultados,$numfilas))
			return false;
		if($numfilas>0)
		{
			while($fila = $this->conexion->ObtenerSiguienteRegistro($resultados))
			{
				$array[$fila['galeriacod']] = $fila;
				$array[$fila['galeriacod']]['fotos'] = array();
				$datosmultimedia['multimediaconjuntocod'] = FOTOS;
				$datosmultimedia['multimediacod'] = $fila['multimediacod'];
				if(!$oMultimedia->BuscarMultimediaxCodigo($datosmultimedia,$resultadoFotos,$numfilasFotos))
					return false;
				if($numfilasFotos>0)
				{
					while($filaFotos= $this->conexion->ObtenerSiguienteRegistro($resultadoFotos))
					{
						if(!$this->GenerarDatosMultimedia($filaFotos,$array[$fila['galeriacod']],'multimediacod',''))
							return false;
					}
				}
				
				$oGaleriasMultimedia = new cGaleriasMultimedia($this->conexion,$this->formato);
				$array[$fila['galeriacod']]['multimedias']['fotos'] = array();
				$datosmultimedia['multimediaconjuntocod'] = FOTOS;
				$datosmultimedia['galeriacod'] = $fila['galeriacod'];
				if(!$oGaleriasMultimedia->BuscarMultimediaFotosxCodigoGaleria($datosmultimedia,$resultadoFotos,$numfilasFotos))
					return false;
				if($numfilasFotos>0)
				{
					while($filaFotos= $this->conexion->ObtenerSiguienteRegistro($resultadoFotos))
					{
						if(!$this->GenerarDatosMultimedia($filaFotos,$array[$fila['galeriacod']]['multimedias'],'multimediacod','gal'))
							return false;
					}
				}

				$array[$fila['galeriacod']]['multimedias']['audios'] = array();
				$datosmultimedia['multimediaconjuntocod'] = AUDIOS;
				$datosmultimedia['galeriacod'] = $fila['galeriacod'];
				if(!$oGaleriasMultimedia->BuscarMultimediaAudiosxCodigoGaleria($datosmultimedia,$resultadoAudios,$numfilasAudios))
					return false;
				if($numfilasAudios>0)
				{
					while($filaAudios= $this->conexion->ObtenerSiguienteRegistro($resultadoAudios))
					{
						if(!$this->GenerarDatosMultimedia($filaAudios,$array[$fila['galeriacod']]['multimedias'],'multimediacod','gal'))
							return false;
					}
				}

				$array[$fila['galeriacod']]['multimedias']['videos'] = array();
				$datosmultimedia['multimediaconjuntocod'] = VIDEOS;
				$datosmultimedia['galeriacod'] = $fila['galeriacod'];
				if(!$oGaleriasMultimedia->BuscarMultimediaVideosxCodigoGaleria($datosmultimedia,$resultadoVideos,$numfilasVideos))
					return false;
				if($numfilasVideos>0)
				{
					while($filaVideos= $this->conexion->ObtenerSiguienteRegistro($resultadoVideos))
					{
						if(!$this->GenerarDatosMultimedia($filaVideos,$array[$fila['galeriacod']]['multimedias'],'multimediacod','gal'))
							return false;
					}
				}
				
				if(isset($array[$fila['galeriacod']]['multimedias']['fotos']) && count($array[$fila['galeriacod']]['multimedias']['fotos'])==0)
					unset($array[$fila['galeriacod']]['multimedias']['fotos']);
				if(isset($array[$fila['galeriacod']]['multimedias']['videos']) && count($array[$fila['galeriacod']]['multimedias']['videos'])==0)
					unset($array[$fila['galeriacod']]['multimedias']['videos']);
				if(isset($array[$fila['galeriacod']]['multimedias']['audios']) && count($array[$fila['galeriacod']]['multimedias']['audios'])==0)
					unset($array[$fila['galeriacod']]['multimedias']['audios']);

			}
			
			
		}
		return true;
	}



	public function PublicarJsonxCodigo($datos)
	{
		$nombrearchivo = "galeria_".$datos['galeriacod'];
		$carpeta = PUBLICA;
		if(!$this->GerenarArrayDatosJsonxCodigo($datos,$array))
			return false;
		//print_r($array);	
		if(count($array)>0)
		{
			if(!$this->GuardarDatosJson($nombrearchivo,$carpeta,$array))
				return false;
		}
		else
		{
			if(!$this->EliminarDatosJson($nombrearchivo,$carpeta))
				return false;
		}
		return true;
	}



	public function GerenarArrayDatosJsonxCodigo($datos,&$array)
	{
		
		$oMultimedia = new cMultimedia($this->conexion,$this->formato);
		$array = array();
		if(!$this->BuscarxCodigo($datos,$resultados,$numfilas))
			return false;
		if($numfilas==1)
		{
			while($fila = $this->conexion->ObtenerSiguienteRegistro($resultados))
			{
				$array[$fila['galeriacod']] = $fila;
				$array[$fila['galeriacod']]['fotos'] = array();
				$datosmultimedia['multimediaconjuntocod'] = FOTOS;
				$datosmultimedia['multimediacod'] = $fila['multimediacod'];
				if(!$oMultimedia->BuscarMultimediaxCodigo($datosmultimedia,$resultadoFotos,$numfilasFotos))
					return false;
				if($numfilasFotos>0)
				{
					while($filaFotos= $this->conexion->ObtenerSiguienteRegistro($resultadoFotos))
					{
						if(!$this->GenerarDatosMultimedia($filaFotos,$array[$fila['galeriacod']],'multimediacod',''))
							return false;
					}
				}
				$oGaleriasMultimedia = new cGaleriasMultimedia($this->conexion,$this->formato);
				$array[$fila['galeriacod']]['multimedias']['fotos'] = array();
				$datosmultimedia['multimediaconjuntocod'] = FOTOS;
				$datosmultimedia['galeriacod'] = $fila['galeriacod'];
				if(!$oGaleriasMultimedia->BuscarMultimediaFotosxCodigoGaleria($datosmultimedia,$resultadoFotos,$numfilasFotos))
					return false;
				if($numfilasFotos>0)
				{
					while($filaFotos= $this->conexion->ObtenerSiguienteRegistro($resultadoFotos))
					{
						if(!$this->GenerarDatosMultimedia($filaFotos,$array[$fila['galeriacod']]['multimedias'],'multimediacod','gal'))
							return false;
					}
				}

				$array[$fila['galeriacod']]['multimedias']['audios'] = array();
				$datosmultimedia['multimediaconjuntocod'] = AUDIOS;
				$datosmultimedia['galeriacod'] = $fila['galeriacod'];
				if(!$oGaleriasMultimedia->BuscarMultimediaAudiosxCodigoGaleria($datosmultimedia,$resultadoAudios,$numfilasAudios))
					return false;
				if($numfilasAudios>0)
				{
					while($filaAudios= $this->conexion->ObtenerSiguienteRegistro($resultadoAudios))
					{
						if(!$this->GenerarDatosMultimedia($filaAudios,$array[$fila['galeriacod']]['multimedias'],'multimediacod','gal'))
							return false;
					}
				}

				$array[$fila['galeriacod']]['multimedias']['videos'] = array();
				$datosmultimedia['multimediaconjuntocod'] = VIDEOS;
				$datosmultimedia['galeriacod'] = $fila['galeriacod'];
				if(!$oGaleriasMultimedia->BuscarMultimediaVideosxCodigoGaleria($datosmultimedia,$resultadoVideos,$numfilasVideos))
					return false;
				if($numfilasVideos>0)
				{
					while($filaVideos= $this->conexion->ObtenerSiguienteRegistro($resultadoVideos))
					{
						if(!$this->GenerarDatosMultimedia($filaVideos,$array[$fila['galeriacod']]['multimedias'],'multimediacod','gal'))
							return false;
					}
				}
				
				if(isset($array[$fila['galeriacod']]['multimedias']['fotos']) && count($array[$fila['galeriacod']]['multimedias']['fotos'])==0)
					unset($array[$fila['galeriacod']]['multimedias']['fotos']);
				if(isset($array[$fila['galeriacod']]['multimedias']['videos']) && count($array[$fila['galeriacod']]['multimedias']['videos'])==0)
					unset($array[$fila['galeriacod']]['multimedias']['videos']);
				if(isset($array[$fila['galeriacod']]['multimedias']['audios']) && count($array[$fila['galeriacod']]['multimedias']['audios'])==0)
					unset($array[$fila['galeriacod']]['multimedias']['audios']);

			}
							
		}
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
				if(isset($fila[$prefijo.'multimediaorden']))
					$arraytemp['orden'] = $fila[$prefijo.'multimediaorden'];
				if(isset($fila[$prefijo.'multimediamuestrahome']))
					$arraytemp['muestrahome'] = $fila[$prefijo.'multimediamuestrahome'];
				$arraytemp['idexterno'] = $fila['multimediaidexterno'];
				$arraytemp['url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$fila['multimediaubic'];
				if(isset($fila['previewubic']) && $fila['previewubic']!="")
					$arraytemp['url_preview'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$fila['previewubic'];
				$arraymultimedia['fotos'][$fila[$id]] = $arraytemp;
			break;
			case VIDEOS:
				$arraytemp['codigo'] = $fila[$id];
				$arraytemp['conjunto'] = $fila['multimediaconjuntocod'];
				$arraytemp['tipo'] = $fila['multimediatipocod'];
				$arraytemp['titulo'] = $fila[$prefijo.'multimediatitulo'];
				$arraytemp['descripcion'] = $fila[$prefijo.'multimediadesc'];
				$arraytemp['nombre_archivo'] = $fila['multimedianombre'];
				if(isset($fila[$prefijo.'multimediaorden']))
					$arraytemp['orden'] = $fila[$prefijo.'multimediaorden'];
				if(isset($fila[$prefijo.'multimediamuestrahome']))
					$arraytemp['muestrahome'] = $fila[$prefijo.'multimediamuestrahome'];
				$arraytemp['idexterno'] = $fila['multimediaidexterno'];
				if(isset($fila['multimediaidexterno']) && $fila['multimediaidexterno']!="")
					$arraytemp['url'] = "";
				else
					$arraytemp['url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS."videos/".$fila['multimediaubic'];
				if(isset($fila['previewubic']) && $fila['previewubic']!="")
					$arraytemp['url_preview'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$fila['previewubic'];
				$arraymultimedia['videos'][$fila[$id]] = $arraytemp;
			break;
			case AUDIOS:
				$arraytemp['codigo'] = $fila[$id];
				$arraytemp['conjunto'] = $fila['multimediaconjuntocod'];
				$arraytemp['tipo'] = $fila['multimediatipocod'];
				$arraytemp['titulo'] = $fila[$prefijo.'multimediatitulo'];
				$arraytemp['descripcion'] = $fila[$prefijo.'multimediadesc'];
				$arraytemp['nombre_archivo'] = $fila['multimedianombre'];
				if(isset($fila[$prefijo.'multimediaorden']))
					$arraytemp['orden'] = $fila[$prefijo.'multimediaorden'];
				if(isset($fila[$prefijo.'multimediamuestrahome']))
					$arraytemp['muestrahome'] = $fila[$prefijo.'multimediamuestrahome'];
				$arraytemp['idexterno'] = $fila['multimediaidexterno'];
				if(isset($fila['multimediaidexterno']) && $fila['multimediaidexterno']!="")
					$arraytemp['url'] = "";
				else
					$arraytemp['url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS."audios/".$fila['multimediaubic'];
				if(isset($fila['previewubic']) && $fila['previewubic']!="")
					$arraytemp['url_preview'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$fila['previewubic'];
				$arraymultimedia['audios'][$fila[$id]] = $arraytemp;
			break;
			case FILES:
				$arraytemp['codigo'] = $fila[$id];
				$arraytemp['conjunto'] = $fila['multimediaconjuntocod'];
				$arraytemp['tipo'] = $fila['multimediatipocod'];
				$arraytemp['titulo'] = $fila[$prefijo.'multimediatitulo'];
				$arraytemp['descripcion'] = $fila[$prefijo.'multimediadesc'];
				$arraytemp['nombre_archivo'] = $fila['multimedianombre'];
				if(isset($fila[$prefijo.'multimediaorden']))
					$arraytemp['orden'] = $fila[$prefijo.'multimediaorden'];
				if(isset($fila[$prefijo.'multimediamuestrahome']))
					$arraytemp['muestrahome'] = $fila[$prefijo.'multimediamuestrahome'];
				$arraytemp['idexterno'] = $fila['multimediaidexterno'];
				if(isset($fila['multimediaidexterno']) && $fila['multimediaidexterno']!="")
					$arraytemp['url'] = "";
				else
					$arraytemp['url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ARCHIVOS.$fila['multimediaubic'];
				if(isset($fila['previewubic']) && $fila['previewubic']!="")
					$arraytemp['url_preview'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$fila['previewubic'];
				$arraymultimedia['archivos'][$fila[$id]] = $arraytemp;
			break;
		}
		return true;
	}
	

	public function PublicarHtml()
	{
		$datos['galeriaestadocod'] = ACTIVO;
		if(!$this->BuscarAvanzadaxGaleria($datos,$resultado,$numfilas))
			return false;
			
		while($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))
		{
			if ($fila['multimediaconjuntocod']==FOTOS)
			{
				$oGaleriasMultimedia = new cGaleriasMultimedia($this->conexion,"");
				if(!$oGaleriasMultimedia->BuscarMultimediaFotosxCodigoGaleria($fila,$resultadoFoto,$numfilas))
					die();
				$datosFoto = $this->conexion->ObtenerSiguienteRegistro($resultadoFoto);
				$html = '<div class="galeriawidget clearfix">';
				$html .= '<div class="div-foto">';
				$html .= '<div class="ico-fotos">&nbsp;</div>';
				$html .= '</div>';
				$html .= '<div class="fotoGaleria">';
				$html .= '<a href="'.$fila['galeriadominio'].'" title="'. FuncionesPHPLocal::HtmlspecialcharsBigtree($datosFoto['multimediatitulo'],ENT_QUOTES).'">';
				$html .= '<img src="'.DOMINIO_SERVIDOR_MULTIMEDIA.$datosFoto['multimediacatcarpeta'].'Thumbs/'.$datosFoto['multimediaubic'].'" alt="'. FuncionesPHPLocal::HtmlspecialcharsBigtree($datosFoto['multimediatitulo'],ENT_QUOTES).'" />';
				$html .= '</a>';
				$html .= '</div>';
				$html .= '<div class="clearboth">&nbsp;</div>';
				$html .= '</div>';
				if(!FuncionesPHPLocal::GuardarArchivo(PUBLICA,$html,"galeria.html"))
				{
					FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_INF,"Error, al generar el archivo de la galeria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
					return false;
				}
				return true;	
			}
		}
		
		return true;
	}

}// FIN CLASE

?>