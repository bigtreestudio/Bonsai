<?php 
include(DIR_DATA."encuestaOpcionesData.php");
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con la lógica para el manejo de las noticias relacionadas

class cEncuestasOpciones
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
		if (isset($datos['opcioncod']))
			$oData->setCodigo($datos['opcioncod']);
	
		if (isset($datos['opcionnombre']))
			$oData->setOpcion( FuncionesPHPLocal::HtmlspecialcharsBigtree($datos['opcionnombre'],ENT_QUOTES));
		
		if (isset($datos['encuestacod']))
			$oData->setEncuesta($datos['encuestacod']);
		
		if (isset($datos['opcioncantvotos']))
			$oData->setCantidadVotos($datos['opcioncantvotos']);


		return true;
	}

	public function BuscarxCodigoxEncuesta($datos)
	{
		$spnombre="sel_enc_encuestas_opciones_xcodigo_xencuesta";
		$sparam=array(
			'pencuestacod'=> $datos['encuestacod'],
			'popcioncod'=> $datos['opcioncod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar la opcion por encuesta",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		if ($numfilas!=1)
			return false;
			
		$datos = $this->conexion->ObtenerSiguienteRegistro($resultado);

		$oData = new EncuestaOpcionesData();
		$this->SetData($oData,$datos);
		
		return $oData;	
	}


			
}//FIN CLASE

?>