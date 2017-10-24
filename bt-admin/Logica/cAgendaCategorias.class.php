<?php  
include(DIR_CLASES_DB."cAgendaCategorias.db.php");

class cAgendaCategorias extends cAgendaCategoriasdb	
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
// Retorna en un arreglo con los datos de un categoria

// Parámetros de Entrada:
//		catcod: catetoria a buscar

// Retorna:
//		errcat: el error en caso de que se produzca
//		datoscategoria= Arreglo con todos los datos de un categoria.
//		la función retorna true o false si se pudo ejecutar con éxito o no

	/*public function CategoriasSP(&$spnombre,&$sparam)
	{
		if (!parent::CategoriasSP($spnombre,$sparam))
			return false;
		return true;	
	}*/
//------------------------------------------------------------------------------------------	
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
// Retorna en un arreglo con los datos de un categoria

// Parámetros de Entrada:
//		catsuperior: categoria superior a buscar

// Retorna:
//		resultado= Arreglo con todos los datos de un categoria.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	public function BuscarCategoriasxCategoriaSuperior($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarCategoriasxCategoriaSuperior($datos,$resultado,$numfilas))
			return false;
		
		return true;
	}

	
//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos del raiz de una categoria 


// Retorna:
//		resultado= Arreglo con todos los datos de un categoria.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no		

	public function BuscaCategoriasRaiz(&$resultado,&$numfilas)
	{
		if (!parent::BuscaCategoriasRaiz($resultado,$numfilas))
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
	public function BuscarAvanzadaxCategoriaSuperior($datos,&$resultado,&$numfilas)
	{
		$sparam=array(
			'xcatsuperior'=> 0,
			'xcatsuperior1'=> 0,
			'catsuperior1'=> "",
			'orderby'=> "catnom ASC",
			'limit'=> ""
			);
			
		if (isset ($datos['catsuperior']) && $datos['catsuperior']!="")
		{
			$sparam['catsuperior1']= $datos['catsuperior'];
			$sparam['xcatsuperior1']= 1;
		}
		else
			$sparam['xcatsuperior']= 1;
	
		if (isset ($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];
		if (isset ($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];

	
		if (!parent::BuscarAvanzadaxCategoriaSuperior($sparam,$resultado,$numfilas))
			return false;
		
		return true;
	}
	

//----------------------------------------------------------------------------------------- 
// Retorna un arreglo con todos los padres de una categoria

// Parámetros de Entrada:
//		catcod: catcod a buscar
//		nivelarbol= Se inicializa en 0.

// Retorna:
//		arrcatcod: devuelve el arreglo con todos los padres del categoria
//		nivelarbol: Devuelve el nivel en que se encuentra el categoria.
//		la función retorna true o false si se pudo ejecutar con éxito o no
 
 
 	public function ArregloPadres($catcod,&$arrcat,&$nivelarbol)
	{
		if ($catcod!="")
		{
			$datoscat['catcod'] = $catcod;
			if (!$this->BuscarxCodigo($datoscat,$resultado,$numfilas))
				return false;
			$result=true;
		
			if ($numfilas==0)
				$result=false;

			if ($result)
			{		
				while ($filasub=$this->conexion->ObtenerSiguienteRegistro($resultado))
				{
					$padre=$filasub['catsuperior'];
					
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
// Retorna un arreglo con todos los hijos de un categoria

// Parámetros de Entrada:
//		catcod: categoria a buscar
//		cantidadarreglo: Se inicializa en 0.

// Retorna:
//		arrcat: devuelve el arreglo con todos los hijos del categoria
//		errcat: el error en caso de que se produzca
//		cantidadarreglo: La cantidad total del arreglo.
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function ArregloHijos($catcod,&$arrcat,&$cantidadarreglo)
	{

		$arrcat = array();
		if ($catcod!="")
		{
			$datoscat['catsuperior'] = $catcod;
			if (!$this->BuscarCategoriasxCategoriaSuperior($datoscat,$resultado,$numfilas))
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
			if (!$this->BuscaCategoriasRaiz($resultado,$numfilas))
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
//		catcod: catcod a buscar

// Retorna:
//		errcat: el error en caso de que se produzca
//		ok: devulve verdadero en caso de que tenga hijos, falso si no tiene.
//		la función retorna true o false si se pudo ejecutar con éxito o no

	
	public function TieneHijos($catcod,&$ok)
	{
		
		$datoscat['catsuperior'] = $catcod;
		if (!$this->BuscarCategoriasxCategoriaSuperior($datoscat,$resultado,$numfilas))
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
		if(!$this->ArregloPadres($catcod,$arrjerarquia,$nivel))
			return false;

		if ($nivel!=0)
			$jerarquia.="<a href='age_agenda_categorias.php'>Inicio</a> &raquo; ";
		else
			$jerarquia.="<span class=\"bold\">Inicio</span>";
		
		foreach ($arrjerarquia as $clave=>$valor) 
		{
			
			if ($i!=$nivel)
			{ 
				FuncionesPHPLocal::ArmarLinkMD5("age_agenda_categorias.php",array("catcod"=>$valor['catcod']),$get,$md5);
				$jerarquia.="<a href='age_agenda_categorias.php?catsuperior=";
				$jerarquia.= FuncionesPHPLocal::HtmlspecialcharsBigtree($valor['catcod'],ENT_QUOTES);
				$jerarquia.="&md5=";
				$jerarquia.= FuncionesPHPLocal::HtmlspecialcharsBigtree($md5,ENT_QUOTES);
				$jerarquia.="' class='bold'>";
				$jerarquia.=$valor['catnom']."</a> &raquo; ";
			}
			else
				$jerarquia.="<span class=\"bold\">".$valor['catnom']."</span>";

			$i++;
		}
		$nivel=0;

		return true;
	} 
	
//----------------------------------------------------------------------------------------- 
// Retorna la rama ascendente de un categoria

// Parámetros de Entrada:
//		catcod: categoria a buscar

// Retorna:
//		jerarquia: un string con la ruta
//		errcat: el error en caso de que se produzca
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function MostrarArbolJerarquia($catcod,&$jerarquia,$estilos=true)
	{
		$arrjerarquia=array();
		if(!$this->ArregloPadres($catcod,$arrjerarquia,$nivel))
			return false;
		
		$i=1;
		$jerarquia="";
		foreach ($arrjerarquia as $clave=>$valor) 
		{
			if ($i!=$nivel)
				$jerarquia.= FuncionesPHPLocal::HtmlspecialcharsBigtree($valor['catnom'],ENT_QUOTES)." &raquo; ";
			else
			{
				if($estilos)
					$jerarquia.="<span class='negrita'>". FuncionesPHPLocal::HtmlspecialcharsBigtree($valor['catnom'],ENT_QUOTES)."</span>";	
				else
					$jerarquia.= FuncionesPHPLocal::HtmlspecialcharsBigtree($valor['catnom'],ENT_QUOTES);	
			}
			$i++;
		}
		$nivel=0;
		
		return true;
	} 

//----------------------------------------------------------------------------------------- 
// Retorna un array con todo el arbol dependiente del catcod ingresado

// Parámetros de Entrada:
//		catcod: raiz del arbol a retornar. Si vale "", entonces retorna el arbol completo de categorias

// Retorna:
//		arbol: array con el resultado de la consulta.
//					Además de la información del categoria, se agregan los subindices:
//						subarbol: arbol con los categorias dependientes del categoria 
//						ruta: jerarquia ascendente desde el categoria actual hasta la raiz
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ArmarArbolCategorias($catcod,&$arbol)
	{
		//traigo primero todos los hijos del categoria solicitado
		$total=0;
		if(!$this->ArregloHijos($catcod,$arbol,$total))
			return false;
		
		//ordeno por nombre los categorias
/*		$arbol=FuncionesPHPLocal::array_column_sort($arbol,"catnom");*/
		
		
		//recorro todos los categorias para asignar la ruta y armar el subarbol dependiente
		foreach($arbol as $indice => $datos)
		{
			$arbol[$indice]["subarbol"]=array();
	
			if(!$this->MostrarArbolJerarquia($datos["catcod"],$jerarquia))
				return false;
			$arbol[$indice]["ruta"]=$jerarquia;
			
			//si tiene hijos entonces llamo a la funcion recursivamente para armar el subarbol dependiente
			if($this->TieneHijos($datos["catcod"],$ok) && $ok)
			{
				if(!$this->ArmarArbolCategorias($datos["catcod"],$arbol[$indice]["subarbol"]))
					return false;
			}
		}
		
		return true;
	}


//----------------------------------------------------------------------------------------- 
// Función recursiva que busca si un catcod está en el arbol dado

// Parámetros de Entrada:
//		arbol
//		catcod

// Retorna:
//		la función retorna true si no se produzco error y el categoria está en el arbol, false en caso contrario

	public function BuscarCategoriaEnArbol(&$arbol,$catcod)
	{
		foreach($arbol as $clave => $datos)
		{
			if($datos["catcod"]==$catcod)
				return true;
				
			if(count($datos["subarbol"])>0)
			{
				if($this->BuscarCategoriaEnArbol($datos["subarbol"],$catcod))
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
// Modifica los datos de un categoria 

// Parámetros de Entrada:

// Retorna:
//		errcat: el error en caso de que se produzca
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function ModificarCategoria($datos)
	{
		
		if (!$this->_ValidarDatosModificar($datos))
			return false;

		if(!$this->Modificar($datos))
			return false;

		
		return true;
	} 
	
//----------------------------------------------------------------------------------------- 
// Inserta un categoria nuevo.

// Parámetros de Entrada:

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function InsertarCategoria($datos,&$codigoinsertado)
	{

		if (!$this->_ValidarDatosAlta($datos))
			return false;
		
		if ($datos['catsuperior']=="")
			$datos['catsuperior']="NULL";
		$this->ObtenerProximoOrden($datos,$proxorden);	
		$datos['catorden']= $proxorden;
		$datos['catestado'] = ACTIVO;
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
// Retorna true o false si algunos de los campos esta vacio

// Parámetros de Entrada:
//		catnom = nombre de la categoria.
//      catsuperior = si existe el cogido de la categoria: 		 

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function _ValidarDatosVacios($datos)
	{
		if ($datos['catnom']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un nombre de categoria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if ($datos['planthtmlcod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una plantilla. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}		
		if ($datos['catsuperior']!="")
		{
			$datoscat['catcod'] = $datos['catsuperior'];
			if (!$this->BuscarxCodigo($datoscat,$resultado,$numfilas))
				return false;
			if ($numfilas!=1)
			{	
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un nombre de categoria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
				return false;
			}
		}
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false al dar de alta si algunos de los campos esta vacio o si exite otra categoria con ese nombre

// Parámetros de Entrada:
//		catnom = nombre de la categoria.
//      catsuperior = si existe el cogido de la categoria: 		 

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	function _ValidarDatosAlta($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;


		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false al modificar si algunos de los campos esta vacio o si exite otra categoria con ese nombre

// Parámetros de Entrada:
//		catnom = nombre de la categoria.
//      catsuperior = si existe el cogido de la categoria: 		 

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function _ValidarDatosModificar($datos)
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

	public function EliminarCategoria($datos)
	{
		if (!$this->PuedeEliminarCategoria($datos,true))
			return false;
			
		if (!parent::Eliminar($datos))
			return false;
			
			
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false si la categoria tiene hijos

// Parámetros de Entrada:
//		catcod = codigo de categoria a eliminar.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function PuedeEliminarCategoria($datos,$mostrarmsg=false)
	{
		if (!$this->ArregloHijos($datos['catcod'],$arrcat,$cantidadarreglo))
			return false;
	
		if ($cantidadarreglo>0)
		{
			if ($mostrarmsg)
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_INF,"La categoria contiene subcategorias asociadas. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		$datoscat = $this->conexion->ObtenerSiguienteRegistro($resultado);	
		if ($datoscat['catestado']==NOACTIVO)	
		{
			if ($mostrarmsg)
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_INF,"La categoria no se encuetra activa. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		$oAgenda = new cAgenda($this->conexion,$this->formato);
		if (!$oAgenda->BuscarxCategoria($datos,$resultadocatnot,$numfilaseventos))
			return false;		

			
		if($numfilaseventos>0)
			return false;
		
		
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna las ccategorias de una noticia

// Parámetros de Entrada:
//		catcod = codigo de categoria.


// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function BuscarCategoriasNoticiasxCatcod($datos,&$resultadocatnot,&$numfilascatnot)
	{
		if (!parent::BuscarCategoriasNoticiasxCatcod($datos,$resultadocatnot,$numfilascatnot))
			return false;
			
		return true;	
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false si pudo cambiarle el estado a la categorias

// Parámetros de Entrada:
//		catcod = codigo de categoria.
//      catestado = nuevo estado de la categoria

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function ModificarEstadoCategoria($datos)
	{
		if (!parent::ModificarEstadoCategoria($datos))
			return false;
			
		return true;	
	}
//----------------------------------------------------------------------------------------- 
// Retorna true o false si pudo cambiarle el estado a ACTIVO a la categorias

// Parámetros de Entrada:
//		catcod = codigo de categoria.
//      catestado = nuevo estado de la categoria

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function ActivarCategoria($datos)
	{
		
		$datosmodificar['catcod'] = $datos['catcod'];
		$datosmodificar['catestado'] = ACTIVO;
		if (!$this->ModificarEstadoCategoria($datosmodificar))
			return false;
		
		return true;
	}
//----------------------------------------------------------------------------------------- 
// Retorna true o false si pudo cambiarle el estado a DESACTIVAR a la categorias

// Parámetros de Entrada:
//		catcod = codigo de categoria.
//      catestado = nuevo estado de la categoria

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	public function DesActivarCategoria($datos)
	{
		
		$datosmodificar['catcod'] = $datos['catcod'];
		$datosmodificar['catestado'] = NOACTIVO;
		if (!$this->ModificarEstadoCategoria($datosmodificar))
			return false;
		
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna proxorden. proximo orden de la categoria

// Parámetros de Entrada:
//		catcod = codigo de categoria.
//      catestado = nuevo estado de la categoria

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	private function ObtenerProximoOrden($datos,&$proxorden)
	{
		$proxorden = 0;
		if (!parent::BuscarNoticiaUltimoOrden($datos,$resultado,$numfilas))
			return false;
			
		if ($numfilas!=0)
		{
			$datos = $this->conexion->ObtenerSiguienteRegistro($resultado);
			$proxorden = $datos['maximo'] + 1;
		}
		return true;
	}
//----------------------------------------------------------------------------------------- 
//Retorna true o false si pudo cambiar el orden de las categorias

// Parámetros de Entrada:
//		catorden = orden de las categorias.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no		
	public function ModificarOrden($datos)
	{
		$arreglocategorias = explode(",",$datos['orden']);
		
		$datosmodif['catorden'] = 1;
		foreach ($arreglocategorias as $catcod)
		{
			$datosmodif['catcod'] = $catcod;
			if (!parent::ModificarOrden($datosmodif))
					return false;
			$datosmodif['catorden']++;
		}
		
		return true;
	}
	
	






}// FIN CLASE

?>