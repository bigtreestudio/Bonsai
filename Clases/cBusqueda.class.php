<?php 
include(DIR_DATA."busquedaData.php");
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con la lógica para el manejo de las paginas

class cBusqueda
{
	
	protected $conexion;
	
	// Constructor de la clase
	public function __construct($conexion){
		$this->conexion = &$conexion;
    } 
	
	// Destructor de la clase
	public function __destruct() {	

    } 	


	public function SetData(&$oData,$datos)
	{
		$oData->setCodigo($datos['codigo']);
		$oData->setTitulo( FuncionesPHPLocal::HtmlspecialcharsBigtree($datos['titulo'],ENT_QUOTES));
		$oData->setCopete(FuncionesPHPLocal::ProcesarHtmlCuerpoEditor($this->conexion,$datos['copete']));
		$oData->setDominio($datos['dominio']);
		return true;
	}
	
	
	
	public function Busqueda($termino,&$CantidadTotal,$limit="")
	{
		if(strlen(trim($termino))<3)
			return $this->BusquedaLike($termino,$CantidadTotal,$limit);
		
		$palabras = preg_split('/ /',trim($termino));
		$palabrasarreglo = implode(',',$palabras);
		$consultapalabra = " >".trim($termino)." <".trim($termino)."*";
		foreach($palabras as $valor) { 
				$consultapalabra .= " >".$valor." <".$valor."*";
		} 	
		$cantidadTotal = $this->BusquedaCantidad($consultapalabra);

		$spnombre="sel_busqueda";
		$sparam=array(
			'pterm'=> $consultapalabra,
			'plimit'=> ""
			);
			
		if ($limit!="")
			$sparam['plimit'] = $limit;

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al realizar la busqueda.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}

		$ArregloBusqueda = array();	
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))	
		{	
			$oBusquedaData = new BusquedaData();
			$this->SetData($oBusquedaData,$fila);
			$ArregloBusqueda[] = $oBusquedaData;
			unset($oBusquedaData);
		}

		return $ArregloBusqueda;	
		
	}
	


	private function BusquedaCantidad($termino,$limit="")
	{
		$spnombre="sel_busqueda_cantidad";
		$sparam=array(
			'pterm'=> $termino
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error realizar la busqueda de las cantidades de resultados.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		$cantidad = 0;
		if ($numfilas>0)
		{
			$datosTotales = $this->conexion->ObtenerSiguienteRegistro($resultado);
			$cantidad = $datosTotales['total'];	
		}

		return $cantidad;	
		
	}
	

	private function BusquedaLike($termino,&$CantidadTotal,$limit="")
	{
		$spnombre="sel_busqueda_like_cantidad";
		$sparam=array(
			'pterm'=> $termino
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar las noticias.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		
		$CantidadTotal = 0;
		if ($numfilas>0)
		{
			$datosTotales = $this->conexion->ObtenerSiguienteRegistro($resultado);
			$CantidadTotal = $datosTotales['total'];	
		}
		
		$spnombre="sel_busqueda_like";
		$sparam=array(
			'pterm'=> $termino,
			'plimit'=> ""
			);
		if ($limit!="")
			$sparam['plimit'] = $limit;

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar las noticias.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}

		$ArregloBusqueda = array();	
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))	
		{	
			$oBusquedaData = new BusquedaData();
			$this->SetData($oBusquedaData,$fila);
			$ArregloBusqueda[] = $oBusquedaData;
			unset($oBusquedaData);
		}


		return $ArregloBusqueda;	
		
	}
	

			
}//FIN CLASE
?>