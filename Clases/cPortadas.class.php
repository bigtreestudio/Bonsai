<?php 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con la lógica para el manejo de las paginas

class cPortadas
{
	protected $conexion;
	protected $datosportada;
	
	// Constructor de la clase
	public function __construct($conexion){
		$this->conexion = &$conexion;
    } 
	
	// Destructor de la clase
	public function __destruct() {	

    } 	


	public function BuscarPortadaxDominio($datos)
	{

		$spnombre="sel_tap_tapas_tipos_xtapatipourlfriendly";
		$sparam=array(
			'ptapatipourlfriendly'=> $datos['tapatipourlfriendly']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar la portada.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		if ($numfilas!=1)
			return false;
			
		$this->datosportada = $this->conexion->ObtenerSiguienteRegistro($resultado);
		return true;	
	}



	public function BuscarPortadaxCodigo($datos)
	{

		$spnombre="sel_tap_tapas_tipos_xtapatipocod";
		$sparam=array(
			'ptapatipocod'=> $datos['tapatipocod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar la portada.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		if ($numfilas!=1)
			return false;
			
		$this->datosportada = $this->conexion->ObtenerSiguienteRegistro($resultado);
		return true;	
	}

	public function getCodigo($datos=array()){if (count($datos)==0) $datos['tapatipocod']= $this->datosportada['tapatipocod'] ;return  FuncionesPHPLocal::HtmlspecialcharsBigtree($datos['tapatipocod'],ENT_QUOTES);}
	public function getArchivo($datos=array()){if (count($datos)==0) $datos['tapatipoarchivo']= $this->datosportada['tapatipoarchivo'] ;return $datos['tapatipoarchivo'];}
			
}//FIN CLASE
?>