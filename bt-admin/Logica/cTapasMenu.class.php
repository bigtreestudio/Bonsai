<?php  
include(DIR_CLASES_DB."cTapasMenu.db.php");

class cTapasMenu extends cTapasMenudb	
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

// Trae las tapas

// Parámetros de Entrada:
//	datos: Array asociativo de datos
//		menutipocod: Tipo del menú
//

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarxTipo($datos,&$resultado,&$numfilas)
	{
		$datos['orderby'] = "menuorden";
		if (!parent::BuscarxTipo ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}


// Trae los dominios del sitio

// Parámetros de Entrada:
//	datos: Array asociativo de datos
//

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarDominios($datos,&$resultado,&$numfilas)
	{
		$sparam=array(
			'orderby'=> "tipo DESC",
			'limit'=> "",
			'pxtipo'=> 0,
			'ptipo'=> "",
		);	
	

		if (isset ($datos['tipo']) && $datos['tipo']!=""){
			$sparam['ptipo']= $datos['tipo'];
			$sparam['pxtipo']=1;
		}

		if (isset ($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];
			
		if (isset ($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];		
		
		if (!parent::BuscarDominios ($sparam,$resultado,$numfilas))
			return false;
		return true;			
	}

// Trae las tapas por codigo

// Parámetros de Entrada:
//	datos: Array asociativo de datos
//		menucod: Tipo del menú
//

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCodigo ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}


	public function BuscarMenuxSuperior($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarMenuxSuperior ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}


//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos del raiz de un menu 


// Retorna:
//		resultado= Arreglo con todos los datos de un categoria.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no		

	public function BuscaMenusRaiz($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscaMenusRaiz($datos,$resultado,$numfilas))
			return false;
		
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
	
	public function ArregloHijos($datostipo,$menucod,&$arrcat,&$cantidadarreglo)
	{

		$arrcat = array();
		if ($menucod!="")
		{
			$datossup['menucodsup'] = $menucod;
			$datossup['menutipocod'] = $datostipo['menutipocod'];
			if (!$this->BuscarMenuxSuperior($datossup,$resultado,$numfilas))
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
			if (!$this->BuscaMenusRaiz($datostipo,$resultado,$numfilas))
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

	
	public function TieneHijos($datostipo,$menucod,&$ok)
	{
		
		$datosmenu['menucodsup'] = $menucod;
		$datosmenu['menutipocod'] = $datostipo['menutipocod'];
		if (!$this->BuscarMenuxSuperior($datosmenu,$resultado,$numfilas))
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
// Retorna un array con todo el arbol dependiente del catcod ingresado

// Parámetros de Entrada:
//		catcod: raiz del arbol a retornar. Si vale "", entonces retorna el arbol completo de categorias

// Retorna:
//		arbol: array con el resultado de la consulta.
//					Además de la información del categoria, se agregan los subindices:
//						subarbol: arbol con los categorias dependientes del categoria 
//						ruta: jerarquia ascendente desde el categoria actual hasta la raiz
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ArmarArbol($datostipo,$menucod,&$arbol)
	{
		//traigo primero todos los hijos del categoria solicitado
		$total=0;
		if(!$this->ArregloHijos($datostipo,$menucod,$arbol,$total))
			return false;
		
		//recorro todos los categorias para asignar la ruta y armar el subarbol dependiente
		foreach($arbol as $indice => $datos)
		{
			$arbol[$indice]["subarbol"]=array();
				
			//si tiene hijos entonces llamo a la funcion recursivamente para armar el subarbol dependiente
			if($this->TieneHijos($datostipo,$datos["menucod"],$ok) && $ok)
			{
				if(!$this->ArmarArbol($datostipo,$datos["menucod"],$arbol[$indice]["subarbol"]))
					return false;
			}
		}
		
		return true;
	}



//----------------------------------------------------------------------------------------- 
// Insertar los datos del menu

// Parámetros de Entrada:
//		datos: arreglo de datos
//			menudesc= descripcion del menu
//			menulink= link del menu
//			menutitle= titulo del link del menu
//			menuaccesskey= codigo de acceso de teclado

// Retorna:
//			menucod= codigo del menu a modificar

	public function Insertar($datos,&$menucod)
	{	

		if (!$this->_ValidarInsertar($datos))
			return false;
			
			
		$this->ObtenerProximoOrden($datos,$proxorden);	
		$datos['menuorden'] = $proxorden;	
		$datos['menucodsup'] = "NULL";
		if(!parent::Insertar($datos,$menucod))
			return false;
		
		
		return true;
	} 
	


//----------------------------------------------------------------------------------------- 
// Modifica los datos del menu

// Parámetros de Entrada:
//		datos: arreglo de datos
//			menudesc= descripcion del menu
//			menulink= link del menu
//			menutitle= titulo del link del menu
//			menuaccesskey= codigo de acceso de teclado
//			menucod= codigo del menu a modificar

// Retorna:

	public function Modificar($datos)
	{	
		if (!$this->_ValidarModificar($datos))
			return false;
			

		if (!isset($datos['menulink']) || $datos['menulink']=="")
		{
			$datos["menulink"]="javascript:void(0);";
		}		
		
		if(!parent::Modificar($datos))
			return false;

		return true;
	} 

//----------------------------------------------------------------------------------------- 
// Eliminar los datos del menu

// Parámetros de Entrada:
//		datos: arreglo de datos
//			menucod= codigo del menu a eliminar

// Retorna:

	public function Eliminar($datos)
	{	
		if (!$this->_ValidarEliminar($datos))
			return false;
			
		if(!parent::Eliminar($datos))
			return false;

		return true;
	} 


//----------------------------------------------------------------------------------------- 
// Modifica el orden del menú

// Parámetros de Entrada:
//		datos: arreglo de datos
//			menucod= codigo del menu a modificar
//			menutipocod= codigo del tipo de menu a modificar

// Retorna:

	
	public function ModificarOrden($datos)
	{
		$datosinsertar['menutipocod'] = $datos['menutipocod'];
		$menuorden = 1;
		if (isset($datos['menu']) && count($datos['menu'])>0)
		{
			foreach ($datos['menu'] as $menucod=>$menucodsup)
			{
				if ($menucodsup=="null")
					$menucodsup = "NULL";;
				$datosinsertar['menucod'] = $menucod;
				$datosinsertar['menuorden'] = $menuorden;
				$datosinsertar['menucodsup'] = $menucodsup;
				if (!parent::ModificarOrden ($datosinsertar))
					return false;

				$menuorden++;	
			}
		}
		return true;			
	}



	public function Publicar($datos)
	{
		$oMenuTipo = new cTapasMenuTipos($this->conexion,$this->formato);
		if(!$oMenuTipo->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, tipo de menu inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}	
			
		$datostipo = $this->conexion->ObtenerSiguienteRegistro($resultado);

		$oTapasMenuPublicadas = new cTapasMenuPublicadas($this->conexion,$this->formato);
		if(!$oTapasMenuPublicadas->Publicar($datostipo))
			return false;
		
		$this-> ArmarArbol($datostipo,"",$arbol);
		
		$numfilas = count($arbol);
		$htmlmenu="";
		if ($numfilas>0)
		{
			$i = 100;
			$width = floor($i/$numfilas);
			$ultimo = 100 - ($numfilas*$width);
			
			$widthultimo = 	$width + $ultimo;
			//$htmlmenu = '<div class="'.$datostipo['menuclass'].'">';
			$htmlmenu .= '<ul class="'.$datostipo['menuclass'].'">';
			$i = 1;
			$nivel = 1;
			
			$anchoautomatico = true;
			if ($datostipo['menuanchoautomatico']==0)
				$anchoautomatico = false;
				
			foreach($arbol as $fila)
			{
				$ancho = $width;
				if ($i==$numfilas)
					$ancho = $widthultimo;
				
				$estiloancho = "";	
				if ($anchoautomatico)
					$estiloancho = 'style="width:'.$ancho.'%"';
				
				$target="_self";
				if ($fila['menutarget']!="")
					$target = $fila['menutarget'];
				$htmlmenu .= '<li '.$estiloancho.' class="'.$fila['menuclassli'].'">';
				$htmlmenu .= '<a accesskey="'.$fila['menuaccesskey'].'" target="'.$target.'" title="'. FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['menutitle'],ENT_QUOTES).'" href="'.$fila['menulink'].'" class="'.$fila['menuclass'].'" data-toggle="'.$fila['menuclassli'].'">';
				
				$htmlmenu .= FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['menudesc'],ENT_QUOTES);
				
				if (isset($fila["menuclasshtml"]) || $fila["menuclasshtml"]!="")
					$htmlmenu .= $fila['menuclasshtml'];	
				$htmlmenu .='</a>';
				if (isset($fila['subarbol']) && count($fila['subarbol'])>0)
				{
					$nivel ++;
					$html = "";
					$this->ArmarMenu($fila['subarbol'],$nivel,$html);
					$htmlmenu .=$html;
					$nivel --;
				}
				$htmlmenu .='</li>';
				$i++;
			}
			$htmlmenu .= '</ul>';
			//$htmlmenu .= '</div>';
		}
		FuncionesPHPLocal::GuardarArchivo(PUBLICA,$htmlmenu,$datostipo['menutipoarchivo']);
		
		return true;
	}


	private function ArmarMenu($arbol,$nivel,&$htmlmenu)
	{
		$i = 100;
		$numfilas = count($arbol);
		$width = floor($i/$numfilas);
		$ultimo = 100 - ($numfilas*$width);
		
		$widthultimo = 	$width + $ultimo;
		$htmlmenu .= '<ul class="nivel_'.$nivel.' dropdown-menu">';
		
		foreach($arbol as $fila)
		{
			$ancho = $width;
			if ($i==$numfilas)
				$ancho = $widthultimo;
				
			$target="_self";
			if ($fila['menutarget']!="")
				$target = $fila['menutarget'];

			$htmlmenu .= '<li  class="'.$fila['menuclassli'].'">';
			$htmlmenu .= '<a accesskey="'.$fila['menuaccesskey'].'" target="'.$target.'" title="'. FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['menutitle'],ENT_QUOTES).'" href="'.$fila['menulink'].'" class="'.$fila['menuclasslink'].'">';
			$htmlmenu .= FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['menudesc'],ENT_QUOTES);
			$htmlmenu .='</a>';
			if (isset($fila['subarbol']) && count($fila['subarbol'])>0)
			{
				$nivel ++;
				$html = "";
				$this->ArmarMenu($fila['subarbol'],$nivel,$html);
				$htmlmenu .=$html;
				$nivel --;
			}
			$htmlmenu .='</li>';
			$i++;
		}
		$htmlmenu .= '</ul>';

		return true;
	}


//----------------------------------------------------------------------------------------- 
// Valida los datos de modificación

// Parámetros de Entrada:
//		datos: arreglo de datos

// Retorna:
	public function _ValidarModificar($datos)
	{
		
		if (!$this->_ValidarDatosVacios($datos))
			return false;

		if(!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;

		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un codigo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		return true;
	}


//----------------------------------------------------------------------------------------- 
// Valida los datos de modificación

// Parámetros de Entrada:
//		datos: arreglo de datos

// Retorna:
	public function _ValidarEliminar($datos)
	{
		
		if(!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;

		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un codigo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		return true;
	}


//----------------------------------------------------------------------------------------- 
// Valida los datos antes de insertar

// Parámetros de Entrada:
//		datos: arreglo de datos

// Retorna:
	public function _ValidarInsertar($datos)
	{
		
		if (!$this->_ValidarDatosVacios($datos))
			return false;

		
		return true;
	}


//----------------------------------------------------------------------------------------- 
// Valida datos obligatorios

// Parámetros de Entrada:
//		datos: arreglo de datos
//			menucod= codigo del menu a modificar
//			menutipocod= codigo del tipo de menu a modificar

// Retorna:
	public function _ValidarDatosVacios($datos)
	{
		
		if ($datos['menudesc']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un titulo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}

		$datos['menuaccesskey']=0;
		if ($datos['menuaccesskey']!="")
		{
			if(!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['menuaccesskey'],"NumericoEntero"))
			{
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un access key y debe ser numerico. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
				return false;
			}
		}

		if ($datos['menutarget']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar si abre o no en ventana nueva. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}


		return true;
	}

	
	
//----------------------------------------------------------------------------------------- 
// Retorna proxorden. proximo orden del menu

// Parámetros de Entrada:
//		menutipocod = codigo del menutipocod.


// Retorna:
//		proxorden= el proximo mayor orden del album de galeria.
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	private function ObtenerProximoOrden($datos,&$proxorden)
	{
		$proxorden = 0;
		if (!$this->BuscarUltimoOrden($datos,$resultado,$numfilas))
			return false;
			
		if ($numfilas!=0)
		{
			$datos = $this->conexion->ObtenerSiguienteRegistro($resultado);
			$proxorden = $datos['maximo'] + 1;
		}
		return true;
	}
	
	
}//FIN CLASS
?>