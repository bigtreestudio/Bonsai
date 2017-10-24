<?php 
include(DIR_DATA."departamentoData.php");
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con la lógica para el manejo de las noticias relacionadas

class cDepartamentos
{
	protected $conexion;
	protected $provinciacod;	



	// Constructor de la clase
	public function __construct($conexion,$provinciacod){
		$this->conexion = &$conexion;
		$this->provinciacod = $provinciacod;
    } 
	
	// Destructor de la clase
	public function __destruct() {	

    } 	

	public function SetData(&$DepartamentoData,$datosdepartamento)
	{
		if (isset($datosdepartamento['provinciacod']))
			$DepartamentoData->setProvinciaCodigo($datosdepartamento['provinciacod']);
		
		if (isset($datosdepartamento['provinciadesc']))
			$DepartamentoData->setProvinciaDescripcion($datosdepartamento['provinciadesc']);
		
		if (isset($datosdepartamento['departamentocod']))
			$DepartamentoData->setCodigo($datosdepartamento['departamentocod']);
		
		if (isset($datosdepartamento['departamentodesc']))
			$DepartamentoData->setDescripcion($datosdepartamento['departamentodesc']);
		return true;
	}

	public function DepartamentoSP(&$spnombre,&$sparam)
	{
		$spnombre="sel_departamentos_xprovinciacod";
		$sparam=array(
			'pprovinciacod'=> $this->provinciacod,
			'porderby'=> "departamentodesc"
			);
		return true;
	}
	
	public function BuscarxCodigo($datos)
	{

		$spnombre="sel_departamentos_xprovinciacod_departamentocod";
		$sparam=array(
			'pprovinciacod'=> $this->provinciacod,
			'pdepartamentocod'=> $datos['departamentocod']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar el departamento.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}

		if ($numfilas!=1)
			return false;
			
		$datosdepartamento = $this->conexion->ObtenerSiguienteRegistro($resultado);
		$oDepartamentoData = new DepartamentoData();
		$this->SetData($oDepartamentoData,$datosdepartamento);

		return $oDepartamentoData;	
	}
		
			
}//FIN CLASE
?>