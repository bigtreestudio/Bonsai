<?php  
include(DIR_CLASES_DB."cTemas.db.php");

class cTemas extends cTemasdb	
{
	protected $conexion;
	protected $formato;
	protected $activos;

	
// CLASE cCategorias 
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


//----------------------------------------------------------------------------------------- 
// Retorna en un arreglo con los datos de un tema

// Parámetros de Entrada:
//		temacod: catetoria a buscar

// Retorna:
//		errcat: el error en caso de que se produzca
//		datostema= Arreglo con todos los datos de un tema.
//		la función retorna true o false si se pudo ejecutar con éxito o no

	/*public function CategoriasSP(&$spnombre,&$sparam)
	{
		if (!parent::CategoriasSP($spnombre,$sparam))
			return false;
		return true;	
	}*/
//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de un tema

// Parámetros de Entrada:
//		temacod: catetoria a buscar

// Retorna:
//		resultado= Arreglo con todos los datos de un tema.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		
		return true;
	} 
//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de un tema

// Parámetros de Entrada:
//		temacodsuperior: tema superior a buscar

// Retorna:
//		resultado= Arreglo con todos los datos de un tema.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	public function BuscarTemasxTemaSuperior($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarTemasxTemaSuperior($datos,$resultado,$numfilas))
			return false;
		
		return true;
	}
//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de un tema 

// Parámetros de Entrada:
//		temacodsuperior: tema superior a buscar.Si vale "", entonces retorna el raiz de el temas

// Retorna:
//		resultado= Arreglo con todos los datos de un tema.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	public function BuscarAvanzadaxTemaSuperior($datos,&$resultado,&$numfilas)
	{
		$sparam=array(
			'xtemacodsuperior'=> 0,
			'xtemacodsuperior1'=> 0,
			'temacodsuperior1'=> "",
			'orderby'=> "temacod ASC",
			'limit'=> ""
			);
			
		if (isset ($datos['temacodsuperior']) && $datos['temacodsuperior']!="")
		{
			$sparam['temacodsuperior1']= $datos['temacodsuperior'];
			$sparam['xtemacodsuperior1']= 1;
		}
		else
			$sparam['xtemacodsuperior']= 1;
	
		if (isset ($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];
		if (isset ($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];

	
		if (!parent::BuscarAvanzadaxTemaSuperior($sparam,$resultado,$numfilas))
			return false;
		
		return true;
	}
	
//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos del raiz de una tema 


// Retorna:
//		resultado= Arreglo con todos los datos de un tema.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no		

	public function BuscaTemasRaiz(&$resultado,&$numfilas)
	{
		if (!parent::BuscaTemasRaiz($resultado,$numfilas))
			return false;
		
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna un arreglo con todos los padres de una tema

// Parámetros de Entrada:
//		temacod: temacod a buscar
//		nivelarbol= Se inicializa en 0.

// Retorna:
//		arrtemacod: devuelve el arreglo con todos los padres del tema
//		nivelarbol: Devuelve el nivel en que se encuentra el tema.
//		la función retorna true o false si se pudo ejecutar con éxito o no
 
 
 	public function ArregloPadres($temacod,&$arrcat,&$nivelarbol)
	{
		if ($temacod!="")
		{
			$datoscat['temacod'] = $temacod;
			if (!$this->BuscarxCodigo($datoscat,$resultado,$numfilas))
				return false;
			$result=true;
		
			if ($numfilas==0)
				$result=false;

			if ($result)
			{		
				while ($filasub=$this->conexion->ObtenerSiguienteRegistro($resultado))
				{
					$padre=$filasub['temacodsuperior'];
					
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
// Retorna un arreglo con todos los hijos de un tema

// Parámetros de Entrada:
//		temacod: tema a buscar
//		cantidadarreglo: Se inicializa en 0.

// Retorna:
//		arrcat: devuelve el arreglo con todos los hijos del tema
//		errcat: el error en caso de que se produzca
//		cantidadarreglo: La cantidad total del arreglo.
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function ArregloHijos($temacod,&$arrcat,&$cantidadarreglo)
	{

		$arrcat = array();
		if ($temacod!="")
		{
			$datoscat['temacodsuperior'] = $temacod;
			if (!$this->BuscarTemasxTemaSuperior($datoscat,$resultado,$numfilas))
				return false;
			
			$result=true;
			if ($numfilas==0)
				$result=false;

			if ($result)
			{		
				while ($filasub=$this->conexion->ObtenerSiguienteRegistro($resultado))
				{
					$arrcat[$cantidadarreglo]=$filasub;
					$cantidadarreglo++;
				}
			}
		}
		else
		{
			if (!$this->BuscaTemasRaiz($resultado,$numfilas))
				return false;
			
			while ($filasub=$this->conexion->ObtenerSiguienteRegistro($resultado))
			{
				$arrcat[$cantidadarreglo]=$filasub;
				$cantidadarreglo++;
			}
		}
	
		return true;
	} 




//----------------------------------------------------------------------------------------- 
// Retorna un ok si tiene hijos

// Parámetros de Entrada:
//		temacod: temacod a buscar

// Retorna:
//		errcat: el error en caso de que se produzca
//		ok: devulve verdadero en caso de que tenga hijos, falso si no tiene.
//		la función retorna true o false si se pudo ejecutar con éxito o no

	
	public function TieneHijos($temacod,&$ok)
	{
		
		$datoscat['temacodsuperior'] = $temacod;
		if (!$this->BuscarTemasxTemaSuperior($datoscat,$resultado,$numfilas))
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
// Retorna la rama ascendente de un tema con redirección

// Parámetros de Entrada:
//		temacod: tema a buscar

// Retorna:
//		jerarquia: un string con la ruta (href)
//		errcat: el error en caso de que se produzca
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function MostrarJerarquia($temacod,&$jerarquia,&$nivel)
	{
		$i=1;
		$jerarquia="";
		$nivel=0;
		$arrjerarquia = array();
		if(!$this->ArregloPadres($temacod,$arrjerarquia,$nivel))
			return false;

		if ($nivel!=0)
			$jerarquia.="<a href='tem_temas.php'>Inicio</a> &raquo; ";
		else
			$jerarquia.="<span class=\"bold\">Inicio</span>";
		
		foreach ($arrjerarquia as $clave=>$valor) 
		{
			
			if ($i!=$nivel)
			{ 
				FuncionesPHPLocal::ArmarLinkMD5("tem_temas.php",array("temacod"=>$valor['temacod']),$get,$md5);
				$jerarquia.="<a href='tem_temas.php?temacodsuperior=";
				$jerarquia.= FuncionesPHPLocal::HtmlspecialcharsBigtree($valor['temacod'],ENT_QUOTES);
				$jerarquia.="&md5=";
				$jerarquia.= FuncionesPHPLocal::HtmlspecialcharsBigtree($md5,ENT_QUOTES);
				$jerarquia.="' class='bold'>";
				$jerarquia.=$valor['tematitulo']."</a> &raquo; ";
			}
			else
				$jerarquia.="<span class=\"bold\">".$valor['tematitulo']."</span>";

			$i++;
		}
		$nivel=0;

		return true;
	} 
	
//----------------------------------------------------------------------------------------- 
// Retorna la rama ascendente de un tema

// Parámetros de Entrada:
//		temacod: tema a buscar

// Retorna:
//		jerarquia: un string con la ruta
//		errcat: el error en caso de que se produzca
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function MostrarArbolJerarquia($temacod,&$jerarquia,$estilos=true)
	{
		$arrcat=array();
		if(!$this->ArregloPadres($temacod,$arrjerarquia,$nivel))
			return false;

		$i=1;
		$jerarquia="";
		foreach ($arrjerarquia as $clave=>$valor) 
		{
			if ($i!=$nivel)
				$jerarquia.= FuncionesPHPLocal::HtmlspecialcharsBigtree($valor['tematitulo'],ENT_QUOTES)." &raquo; ";
			else
			{
				if($estilos)
					$jerarquia.="<span class='negrita'>". FuncionesPHPLocal::HtmlspecialcharsBigtree($valor['tematitulo'],ENT_QUOTES)."</span>";	
				else
					$jerarquia.= FuncionesPHPLocal::HtmlspecialcharsBigtree($valor['tematitulo'],ENT_QUOTES);	
			}
			$i++;
		}
		$nivel=0;
		
		return true;
	} 

//----------------------------------------------------------------------------------------- 
// Retorna un array con todo el arbol dependiente del temacod ingresado

// Parámetros de Entrada:
//		temacod: raiz del arbol a retornar. Si vale "", entonces retorna el arbol completo de temas

// Retorna:
//		arbol: array con el resultado de la consulta.
//					Además de la información del tema, se agregan los subindices:
//						subarbol: arbol con los temas dependientes del tema 
//						ruta: jerarquia ascendente desde el tema actual hasta la raiz
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ArmarArbolTemas($temacod,&$arbol)
	{
		//traigo primero todos los hijos del tema solicitado
		$total=0;
		if(!$this->ArregloHijos($temacod,$arbol,$total))
			return false;
		
		//ordeno por nombre los temas
/*		$arbol=FuncionesPHPLocal::array_column_sort($arbol,"tematitulo");*/
		
		//recorro todos los temas para asignar la ruta y armar el subarbol dependiente
		foreach($arbol as $indice => $datos)
		{
			$arbol[$indice]["subarbol"]=array();
	
			if(!$this->MostrarArbolJerarquia($datos["temacod"],$jerarquia))
				return false;
			$arbol[$indice]["ruta"]=$jerarquia;
			
			//si tiene hijos entonces llamo a la funcion recursivamente para armar el subarbol dependiente
			if($this->TieneHijos($datos["temacod"],$ok) && $ok)
			{
				if(!$this->ArmarArbolTemas($datos["temacod"],$arbol[$indice]["subarbol"]))
					return false;
			}
		}
		
		return true;
	}


//----------------------------------------------------------------------------------------- 
// Función recursiva que busca si un temacod está en el arbol dado

// Parámetros de Entrada:
//		arbol
//		temacod

// Retorna:
//		la función retorna true si no se produzco error y el tema está en el arbol, false en caso contrario

	public function BuscarTemaEnArbol(&$arbol,$temacod)
	{
		foreach($arbol as $clave => $datos)
		{
			if($datos["temacod"]==$temacod)
				return true;
				
			if(count($datos["subarbol"])>0)
			{
				if($this->BuscarTemaEnArbol($datos["subarbol"],$temacod))
					return true;
			}
		}

		return false;
	}





//----------------------------------------------------------------------------------------- 
//ABM DE CATEGORIAS.-
//----------------------------------------------------------------------------------------- 

//----------------------------------------------------------------------------------------- 

//----------------------------------------------------------------------------------------- 
// Modifica los datos de un tema 

// Parámetros de Entrada:

// Retorna:
//		errcat: el error en caso de que se produzca
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function ModificarCategoria($datos)
	{
		$datosjson = $datos;
		unset($datosjson['temadesc']);
		unset($datosjson['catcolor']);
		unset($datosjson['temacod']);
		unset($datosjson['tematitulo']);
		unset($datosjson['ultmodusuario']);
		unset($datosjson['ultmodfecha']);
		unset($datosjson['catdominio']);
		unset($datosjson['semuestramenu']);
		unset($datosjson['temacodsuperior']);
		$datos['catdatajson'] = json_encode($datosjson);
		$datos['temadesc'] = preg_replace("/<p[^>]*><\\/p[^>]*>/",'<div class="space">&nbsp;</div>',$datos['temadesc']);

		if (!$this->_ValidarDatosModificar($datos))
			return false;
		$datos['temacodsuperior'] ="NULL";
		if(!$this->Modificar($datos))
			return false;

		
		return true;
	} 
	
//----------------------------------------------------------------------------------------- 
// Inserta un tema nuevo.

// Parámetros de Entrada:

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function InsertarCategoria($datos,&$codigoinsertado)
	{

		if (!$this->_ValidarDatosAlta($datos))
			return false;
		
		if ($datos['temacodsuperior']=="")
			$datos['temacodsuperior']="NULL";
		$datos['temaestado'] = ACTIVO;

		$datosjson = $datos;
		unset($datosjson['temadesc']);
		unset($datosjson['catcolor']);
		unset($datosjson['temacod']);
		unset($datosjson['tematitulo']);
		unset($datosjson['ultmodusuario']);
		unset($datosjson['ultmodfecha']);
		unset($datosjson['catdominio']);
		unset($datosjson['semuestramenu']);
		unset($datosjson['temacodsuperior']);
		unset($datosjson['catorden']);
		unset($datosjson['temaestado']);
		$datos['catdatajson'] = json_encode($datosjson);
		$datos['temadesc'] = preg_replace("/<p[^>]*><\\/p[^>]*>/",'<div class="space">&nbsp;</div>',$datos['temadesc']);
		
		if(!$this->Insertar($datos,$codigoinsertado))
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
// Retorna True o false, de acuerdo si existe algún tema de la misma jerarquía que contenga el
// mismo nombre, tambien si alguno que esté pendiente de modificación.

// Parámetros de Entrada:
//		$padre= Codigo del tema padre.
//		Se pasa el padre pq busco solo los nombres que existen con el mismo padre
//		nombre= nombre del tema que deseo buscar.


// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function _VerificarNombre ($nombre,$padre,&$datosencontrados)
	{
		$datosencontrados = array();
		if ($padre=="NULL") 
		{
			$datos['tematitulo'] = $nombre;
			if (!parent::BuscaTemasNombreRaiz($datos,$resultado,$numfilas))
				return false;
		}
		else
		{
			$datos['tematitulo'] = $nombre;
			$datos['temacodsuperior'] = $padre;
			if (!parent::BuscaTemasNombrexTemaSuperior($datos,$resultado,$numfilas))
				return false;
		}	
		
		if ($numfilas!=0)
		{	
			$datosencontrados = $this->conexion->ObtenerSiguienteRegistro($resultado);
			return false;
		}
		
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false si algunos de los campos esta vacio

// Parámetros de Entrada:
//		tematitulo = nombre de el tema.
//      temacodsuperior = si existe el cogido de el tema: 		 

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function _ValidarDatosVacios($datos)
	{
		if (isset($datos['tematitulo']) && $datos['tematitulo']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un título del tema. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (isset($datos['temacodsuperior']) && $datos['temacodsuperior']!="")
		{
			$datoscat['temacod'] = $datos['temacodsuperior'];
			if (!$this->BuscarxCodigo($datoscat,$resultado,$numfilas))
				return false;
			if ($numfilas!=1)
			{	
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un título del tema. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
				return false;
			}
		}
		
/*		if (isset($datos['temadesc']) && $datos['temadesc']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una descripción. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
*/
		/*if (isset($datos['temacolor']) && $datos['temacolor']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un color. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}*/

		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false al dar de alta si algunos de los campos esta vacio o si exite otra tema con ese nombre

// Parámetros de Entrada:
//		tematitulo = nombre de el tema.
//      temacodsuperior = si existe el cogido de el tema: 		 

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	function _ValidarDatosAlta($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;

		if(!$this->_VerificarNombre($datos['tematitulo'],$datos['temacodsuperior'],$datosnombre))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_INF,"Ya existe una tema con ese nombre. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}

		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false al modificar si algunos de los campos esta vacio o si exite otra tema con ese nombre

// Parámetros de Entrada:
//		tematitulo = nombre de el tema.
//      temacodsuperior = si existe el cogido de el tema: 		 

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function _ValidarDatosModificar($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;

		if(!$this->_VerificarNombre($datos['tematitulo'],$datos['temacodsuperior'],$datosnombre))
		{
			if ($datosnombre['temacod']!=$datos['temacod'])
			{	
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_INF,"Ya existe un tema con ese nombre. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
				return false;
			}
		}


		return true;
	}
//----------------------------------------------------------------------------------------- 
// Retorna true o false si pudo eliminar el tema

// Parámetros de Entrada:
//		temacod = codigo de tema a eliminar.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function EliminarCategoria($datos)
	{
		if (!$this->PuedeEliminarTema($datos,true))
			return false;
			
		if (!parent::Eliminar($datos))
			return false;
			
			
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false si el tema tiene hijos

// Parámetros de Entrada:
//		temacod = codigo de tema a eliminar.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function PuedeEliminarTema($datos,$mostrarmsg=false)
	{
		if (!$this->ArregloHijos($datos['temacod'],$arrcat,$cantidadarreglo))
			return false;
	
		if ($cantidadarreglo>0)
		{
			if ($mostrarmsg)
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_INF,"El tema contiene subtemas asociadas. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		$datoscat = $this->conexion->ObtenerSiguienteRegistro($resultado);	
		if ($datoscat['temaestado']==NOACTIVO)	
		{
			if ($mostrarmsg)
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_INF,"El tema no se encuetra activa. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false si pudo cambiarle el estado a el tema

// Parámetros de Entrada:
//		temacod = codigo de un tema.
//      temaestado = nuevo estado de el tema

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function ModificarEstadoTema($datos)
	{
		if (!parent::ModificarEstadoTema($datos))
			return false;
			
		return true;	
	}
//----------------------------------------------------------------------------------------- 
// Retorna true o false si pudo cambiarle el estado a ACTIVO a el tema

// Parámetros de Entrada:
//		temacod = codigo del tema.
//      temaestado = nuevo estado del tema

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function ActivarTema($datos)
	{
		
		$datosmodificar['temacod'] = $datos['temacod'];
		$datosmodificar['temaestado'] = ACTIVO;
		if (!$this->ModificarEstadoTema($datosmodificar))
			return false;
		
		return true;
	}
//----------------------------------------------------------------------------------------- 
// Retorna true o false si pudo cambiarle el estado a DESACTIVAR a el temas

// Parámetros de Entrada:
//		temacod = codigo del tema.
//      temaestado = nuevo estado de el tema

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	public function DesActivarTema($datos)
	{
		
		$datosmodificar['temacod'] = $datos['temacod'];
		$datosmodificar['temaestado'] = NOACTIVO;
		if (!$this->ModificarEstadoTema($datosmodificar))
			return false;
		
		return true;
	}

}// FIN CLASE

?>