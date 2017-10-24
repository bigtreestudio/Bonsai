<?php 
include(DIR_DATA."encuestaData.php");
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con la lógica para el manejo de las noticias relacionadas

class cEncuestas
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
		if (isset($datos['encuestacod']))
			$oData->setCodigo($datos['encuestacod']);
	
		if (isset($datos['encuestapregunta']))
			$oData->setPregunta( FuncionesPHPLocal::HtmlspecialcharsBigtree($datos['encuestapregunta'],ENT_QUOTES));
		
		if (isset($datos['encuestapregunta']))
        {
              $dominio="";
              $dominioform = FuncionesPHPLocal::EscapearCaracteres($datos['encuestapregunta']);
              $dominioform=preg_replace('/[^a-zA-Z0-9-_ ]/', '-', trim($dominioform));
              $dominioform=str_replace(' ', '-', trim($dominioform));
              $oData->setDominio("encuesta/".$datos['encuestacod']."-".$dominioform);
        }
	
		return true;
	}


	public function BuscarxCodigo($datos)
	{
		$spnombre="sel_enc_encuestas_xcodigo";
		$sparam=array(
			'pencuestacod'=> $datos['encuestacod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar la encuesta",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		if ($numfilas!=1)
			return false;
			
		$datos = $this->conexion->ObtenerSiguienteRegistro($resultado);

		$oData = new EncuestaData();
		$this->SetData($oData,$datos);
		
		return $oData;	
	}


	public function CargarOpciones(&$oData)
	{
		$spnombre="sel_enc_encuestas_opciones_xcodigo";
		$sparam=array(
			'pencuestacod'=> $oData->getCodigo()
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar las opciones de la encuesta.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		$relacionadas = array();	
		$oOpcionesServices = new cEncuestasOpciones($this->conexion);
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))	
		{
			$oOpciones = new EncuestaOpcionesData();
			$oOpcionesServices->SetData($oOpciones,$fila);
			$opciones[] = $oOpciones;
			unset($oOpciones);
		}

		unset($oOpcionesServices);
		$oData->setOpciones($opciones);
		
		return true;	
	}
	
	
	
	public function ResponderEncuesta($datos,&$oEncuesta,&$error)
	{

		if (!$this->_ValidarResponderEncuesta($datos,$oEncuesta,$error))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al responder la encuesta.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}

		$datoscliente=FuncionesPHPLocal::ObtenerDatosCliente();
		
		if ($this->PuedeVotarIp($datoscliente))
		{
			$spnombre="ins_enc_encuestas_respuestas";
			$sparam=array(
				'pencuestacod'=> $datos['encuestacod'],
				'popcioncod'=> $datos['opcioncod'],
				'prespuestaip'=> $datoscliente[0],
				'prespuestaso'=> $datoscliente[1],
				'prespuestanavegador'=> $datoscliente[2],
				'prespuestafecha'=> date("Y/m/d H:i:s")
				);
				
			if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) || $numfilas!=1)
			{
				FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al responder la encuesta.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
				return false;
			}
				
			if (!$this->SumarVotoaOpcion($datos))	
				return false;
		}
		return true;
			
	}

	public function SumarVotoaOpcion($datos)
	{
		$spnombre="upd_enc_encuestas_opciones_sumavoto";
		$sparam=array(
			'pencuestacod'=> $datos['encuestacod'],
			'popcioncod'=> $datos['opcioncod']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) || $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al sumar un voto a la opcion de la encuesta.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		return true;
	}


	public function _ValidarResponderEncuesta($datos,&$oEncuesta,&$error)
	{
		$oEncuesta = $this->BuscarxCodigo($datos);
		if (!$oEncuesta)
		{
			$error = 1;
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error encuesta inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		if (!isset($datos['opcioncod']) || $datos['opcioncod']=="")
		{
			$error=1;
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error opcion inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;	
		}
		$oOpcionesService = new cEncuestasOpciones($this->conexion);
		$oOpcion = $oOpcionesService->BuscarxCodigoxEncuesta($datos);
		if (!$oOpcion)
		{
			$error = 1;
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error opcion inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		return true;	
	}


	public function PuedeVotarIp($datoscliente)
	{
		$spnombre="sel_enc_encuestas_respuesta_xverificavotante";
		$sparam=array(
			'prespuestaip'=> $datoscliente[0],
			'prespuestaso'=> $datoscliente[1],
			'prespuestanavegador'=> $datoscliente[2],
			'prespuestafecha'=> date("Y-m-d")
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al verificar si puede votar.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		if ($numfilas>0)
			return false;
			
		return true;
	}


			
}//FIN CLASE

?>