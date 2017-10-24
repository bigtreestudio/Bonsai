<?php 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con la lógica para el manejo de las noticias relacionadas

class cMenu
{
	protected $conexion;
	
	// Constructor de la clase
	public function __construct($conexion){
		$this->conexion = &$conexion;
    } 
	
	// Destructor de la clase
	public function __destruct() {	

    } 	
	
	
	public function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_tap_menu_publicados_xmenucod";
		$sparam=array(
			'pmenucod'=> $datos['menucod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar el menu.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}

		return true;			
	}
	


	public function BuscarTipoxCte($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_tap_menu_tipos_xmenutipocte";
		$sparam=array(
			'pmenutipocte'=> $datos['menutipocte']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar el tipo de menu.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
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
			if(!$this->ArmarArbol($datostipo,$datos["menucod"],$arbol[$indice]["subarbol"]))
				return false;
		}
		
		return true;
	}
	
	
	
	private function BuscarMenuxSuperior($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_tap_menu_publicados_xmenucodsup_xtipo";
		$sparam=array(
			'pmenutipocte'=> $datos['menutipocte'],
			'pxmenucodsup'=> 0,
			'pmenucodsup'=> ""
			);
		
		if (isset($datos['menucodsup']) && $datos['menucodsup']!="")
		{
			$sparam['pmenucodsup'] = $datos['menucodsup'];
			$sparam['pxmenucodsup'] = 1;
		}
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar el menu.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
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
	
	private function ArregloHijos($datostipo,$menucod,&$arrcat,&$cantidadarreglo)
	{

		$arrcat = array();
		if ($menucod!="")
		{
			$datossup['menucodsup'] = $menucod;
			$datossup['menutipocte'] = $datostipo['menutipocte'];
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
			$datossup['menutipocte'] = $datostipo['menutipocte'];
			if (!$this->BuscarMenuxSuperior($datostipo,$resultado,$numfilas))
				return false;
			
			while ($filasub=$this->conexion->ObtenerSiguienteRegistro($resultado))
			{
				$arrcat[$cantidadarreglo]=$filasub;
				$cantidadarreglo++;
			}
		}
	
		return true;
	} 
	


	
			
}//FIN CLASE

?>